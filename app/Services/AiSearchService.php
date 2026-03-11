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
        'chapter'
    ];

    protected float $learningRate = 0.5;   
    protected int $defaultEpochs = 50;     
    protected float $errorScaling = 0.1;   
    protected float $biasScaling = 0.5;    
    protected float $minScoreThreshold = 0.5; 

    public function __construct(
        protected TensorService $tensor,
        protected SimpleAiModel $model
    ) {}

    public function train(string $input, array $targets, ?float $lr = null, ?int $epochs = null): void
    {
        $tokens = $this->model->tokenize($input);
        if (empty($tokens)) return;

        $lr = $lr ?? $this->learningRate;
        $epochs = $epochs ?? $this->defaultEpochs;

        $targetVector = [];
        foreach ($this->mapKeys as $key) {
            $targetVector[] = $targets[$key] ?? 0;
        }

        for ($epoch = 0; $epoch < $epochs; $epoch++) {
            $currentScores = $this->getRawScores($input);
            
            foreach ($tokens as $tokenId) {
                $emb = $this->model->getEmbedding($tokenId);
                
                foreach ($targetVector as $i => $targetVal) {
                    if ($targetVal <= 0) continue;

                    $error = $targetVal - $currentScores[$i];

                    foreach ($emb as $dim => $val) {
                        $emb[$dim] += $lr * $error * $this->errorScaling;
                    }

                    $this->model->data['bias'][$i] += $lr * $error * $this->biasScaling;
                }
                $this->model->data['embeddings'][$tokenId] = $emb;
            }
        }

        $this->model->save();
    }

    public function extractParameters(string $input): array
    {
        try {
            $scores = $this->getRawScores($input);
            if (empty($scores)) return [];

            return $this->mapToDatabaseParams($scores);
        } catch (\Exception $e) {
            Log::error("AI Extraction Error: " . $e->getMessage());
            return [];
        }
    }

    protected function getRawScores(string $input): array
    {
        $tokens = $this->model->tokenize($input);
        if (empty($tokens)) return [];

        $embeddings = array_map(fn($id) => $this->model->getEmbedding($id), $tokens);
        $sentenceVector = $this->tensor->meanPooling($embeddings);
        
        $projection = $this->tensor->multiply([$sentenceVector], $this->model->getProjectionWeights());
        return $this->tensor->addVectors($projection[0], $this->model->getBias());
    }

    private function mapToDatabaseParams(array $scores): array
    {
        $result = [];
        foreach ($this->mapKeys as $index => $key) {
            if (isset($scores[$index]) && $scores[$index] > $this->minScoreThreshold) {
                $result[$key] = (int)round($scores[$index]);
            }
        }
        return $result;
    }

    /**
     * ডাইনামিক কাউন্টসহ মডেল রিসেট
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