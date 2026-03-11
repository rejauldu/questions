<?php

namespace App\Services;

class SimpleAiModel
{
    private string $modelPath;
    public array $data;
    private int $embeddingDim = 16;
    private int $outputParams = 5; 

    private array $defaultVocab = [
        '<unk>' => 0, 'physics' => 1, 'chemistry' => 2, 'math' => 3, 'ict' => 4,
        'biology' => 5, 'dhaka' => 6, 'cumilla' => 7, 'rajshahi' => 8, 'board' => 9,
        'question' => 10, 'solution' => 11, 'hsc' => 12, 'ssc' => 13, 'bcs' => 14,
        'mcq' => 15, 'chapter' => 16, 'force' => 17, 'energy' => 18, 'acid' => 19,
        'logic' => 20, 'gate' => 21, '2024' => 22, '2023' => 23, '2022' => 24,
        'du' => 25, 'buet' => 26, 'medical' => 27, 'admission' => 28, 'unit' => 29,
    ];

    public function __construct()
    {
        $this->modelPath = storage_path('app/ai/weights.php');
        
        if (!file_exists($this->modelPath)) {
            $this->install();
        }

        $this->data = require $this->modelPath;
    }

    /**
     * $paramCount এখন ডাইনামিক্যালি AiSearchService থেকে আসবে
     */
    public function install(int $paramCount = 5): void
    {
        $this->outputParams = $paramCount;

        $initialData = [
            'vocab' => $this->defaultVocab,
            'embeddings' => [],
            'weights' => [],
            'bias' => array_fill(0, $this->outputParams, 0.0)
        ];

        foreach ($initialData['vocab'] as $word => $id) {
            $initialData['embeddings'][$id] = $this->randomVector($this->embeddingDim);
        }

        for ($i = 0; $i < $this->embeddingDim; $i++) {
            $initialData['weights'][$i] = $this->randomVector($this->outputParams);
        }

        $this->data = $initialData;
        $this->save();
    }

    private function randomVector(int $size): array
    {
        return array_map(fn() => (mt_rand() / mt_getrandmax() - 0.5) * 0.1, range(1, $size));
    }

    public function tokenize(string $text): array
    {
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', '', strtolower($text));
        $words = explode(' ', trim($text));
        
        return array_map(function($w) {
            return $this->data['vocab'][$w] ?? $this->data['vocab']['<unk>'];
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