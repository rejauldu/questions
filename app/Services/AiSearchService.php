<?php

namespace App\Services;

use App\Services\TensorService;
use App\Services\SimpleAiModel;
use Illuminate\Support\Facades\Log;

class AiSearchService
{
    protected array $mapKeys = [
        'institution_id', 
        'subject_id', 
        'year', 
        'board_id', 
        'chapter',
        'category'
    ];

    // Settings for stable learning
    protected float $learningRate = 0.1;   
    protected int $defaultEpochs = 50;     
    protected float $errorScaling = 0.2;  
    protected float $biasScaling = 0.1;    
    protected float $minScoreThreshold = 0.3; 

    public function __construct(
        protected TensorService $tensor,
        protected SimpleAiModel $model
    ) {
        $this->ensureModelCompatibility();
    }

    /**
     * Ensures the model on disk matches our current parameter keys.
     */
    protected function ensureModelCompatibility(): void
    {
        if (!isset($this->model->data['dictionary']) || !isset($this->model->data['bias'])) {
            $this->resetModel();
            return;
        }

        if (count($this->model->data['bias']) !== count($this->mapKeys)) {
            $this->resetModel();
        }
    }

    /**
     * Train the model using Gradient Descent with Clipping.
     */
    public function train(string $input, array $targets, ?float $lr = null, ?int $epochs = null): void
    {
        $tokens = $this->getOrAddTokens($input);
        if (empty($tokens)) return;

        $lr = $lr ?? $this->learningRate;
        $epochs = $epochs ?? $this->defaultEpochs;

        $targetVector = [];
        foreach ($this->mapKeys as $key) {
            $targetVector[] = (float)($targets[$key] ?? 0);
        }

        for ($epoch = 0; $epoch < $epochs; $epoch++) {
            // Recalculate raw scores to track current error state
            $currentScores = $this->getRawScores($input);
            
            foreach ($tokens as $tokenId) {
                $emb = $this->model->getEmbedding($tokenId);
                
                foreach ($targetVector as $i => $targetVal) {
                    // Error = Expected - Actual
                    $error = $targetVal - ($currentScores[$i] ?? 0);

                    // Update Embedding dimensions
                    foreach ($emb as $dim => $val) {
                        $update = $lr * $error * $this->errorScaling;
                        // Clip updates to prevent NAN explosion
                        $emb[$dim] += max(-2, min(2, $update)); 
                    }

                    // Update Bias
                    $biasUpdate = $lr * $error * $this->biasScaling;
                    $this->model->data['bias'][$i] += max(-1, min(1, $biasUpdate));
                }
                
                // Keep values within a stable range (-15 to 15)
                $this->model->data['embeddings'][$tokenId] = array_map(
                    fn($v) => max(-15, min(15, (float)$v)), 
                    $emb
                );
            }
        }

        $this->model->save();
    }

    /**
     * Convert text input into predicted database parameters.
     */
    public function extractParameters(string $input): array
    {
        try {
            $scores = $this->getRawScores($input);
            return $this->mapToDatabaseParams($scores);
        } catch (\Exception $e) {
            Log::error("AI Parameter Extraction Failed: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Calculate raw AI scores based on embeddings, pooling, and projection.
     */
    public function getRawScores(string $input): array
    {
        $tokens = $this->getOrAddTokens($input);
        if (empty($tokens)) {
            return array_fill(0, count($this->mapKeys), 0.0);
        }

        $embeddings = array_map(fn($id) => $this->model->getEmbedding($id), $tokens);
        
        // Combine word embeddings into a single sentence vector
        $sentenceVector = $this->tensor->meanPooling($embeddings);
        
        // Project sentence vector through the weights and add bias
        $projection = $this->tensor->multiply([$sentenceVector], $this->model->getProjectionWeights());
        $scores = $this->tensor->addVectors($projection[0], $this->model->getBias());

        // Safety: ensure no NAN values remain in the final output
        return array_map(fn($s) => is_nan($s) ? 0.0 : (float)$s, $scores);
    }

    /**
     * Cleans input and converts words into IDs, adding new words to dictionary.
     */
    protected function getOrAddTokens(string $input): array
    {
        $input = preg_replace('/[^\p{L}\p{N}\s]/u', '', strtolower($input));
        $words = explode(' ', trim($input));
        $tokens = [];

        // Safety check for dictionary structure
        if (!isset($this->model->data['dictionary'])) {
            $this->model->data['dictionary'] = [];
        }

        foreach ($words as $word) {
            if (empty($word)) continue;
            
            if (!isset($this->model->data['dictionary'][$word])) {
                $newId = count($this->model->data['dictionary']);
                $this->model->data['dictionary'][$word] = $newId;
                
                // Initialize new words with random embeddings at 0.5 scale
                $this->model->data['embeddings'][$newId] = array_map(
                    fn($v) => $v * 0.5, 
                    $this->tensor->randomVector(64)
                );
            }
            $tokens[] = $this->model->data['dictionary'][$word];
        }

        return $tokens;
    }

    /**
     * Map raw decimals to integer IDs (rounding) if they exceed the threshold.
     */
    private function mapToDatabaseParams(array $scores): array
    {
        $result = [];
        foreach ($this->mapKeys as $index => $key) {
            $val = $scores[$index] ?? 0.0;
            
            // If the AI is confident enough, round the float to a DB ID
            if ($val > $this->minScoreThreshold) {
                $result[$key] = (int)round($val);
            }
        }
        return $result;
    }

    /**
     * Wipe and reinstall the model.
     */
    public function resetModel(): void
    {
        $this->model->install(count($this->mapKeys));
    }

    public function getMapKeys(): array
    {
        return $this->mapKeys;
    }
}