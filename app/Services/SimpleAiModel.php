<?php

namespace App\Services;

class SimpleAiModel
{
    private string $modelPath;
    public array $data;
    private int $embeddingDim = 64; // Set to 64 to match Service
    private int $outputParams = 6;  // Matches your new mapKeys count

    public function __construct()
    {
        $this->modelPath = storage_path('app/ai/weights.php');
        
        // Ensure directory exists
        if (!is_dir(dirname($this->modelPath))) {
            mkdir(dirname($this->modelPath), 0755, true);
        }

        if (!file_exists($this->modelPath)) {
            $this->install($this->outputParams);
        }

        $this->data = require $this->modelPath;
    }

    public function install(int $paramCount = 6): void
    {
        $this->outputParams = $paramCount;

        $initialData = [
            'dictionary' => [
                '<unk>' => 0, 'physics' => 1, 'chemistry' => 2, 'math' => 3, 'ict' => 4,
                'biology' => 5, 'mcq' => 6, 'chapter' => 7, '2024' => 8, '2023' => 9,
            ],
            'embeddings' => [],
            'weights' => [],
            'bias' => array_fill(0, $this->outputParams, 0.0)
        ];

        // Initialize embeddings for default vocab
        foreach ($initialData['dictionary'] as $word => $id) {
            $initialData['embeddings'][$id] = $this->randomVector($this->embeddingDim);
        }

        // Initialize Projection Weights (Dimensions x Params)
        for ($i = 0; $i < $this->embeddingDim; $i++) {
            $initialData['weights'][$i] = $this->randomVector($this->outputParams);
        }

        $this->data = $initialData;
        $this->save();
    }

    private function randomVector(int $size): array
    {
        // Xavier/Glorot-like initialization
        $scale = sqrt(2.0 / $size);
        return array_map(fn() => (mt_rand() / mt_getrandmax() * 2 - 1) * $scale, range(1, $size));
    }

    /**
     * Note: We now use the getOrAddTokens logic in AiSearchService 
     * for auto-adding words, but we keep this for legacy safety.
     */
    public function tokenize(string $text): array
    {
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', strtolower($text));
        $words = explode(' ', trim($text));
        
        return array_map(function($w) {
            return $this->data['dictionary'][$w] ?? $this->data['dictionary']['<unk>'];
        }, array_filter($words));
    }

    public function save(): void
    {
        $content = "<?php\nreturn " . var_export($this->data, true) . ";";
        file_put_contents($this->modelPath, $content);
    }

    public function getEmbedding(int $tokenId): array
    {
        return $this->data['embeddings'][$tokenId] ?? array_fill(0, $this->embeddingDim, 0.0);
    }

    public function getProjectionWeights(): array
    {
        return $this->data['weights'];
    }

    public function getBias(): array
    {
        return $this->data['bias'];
    }
}