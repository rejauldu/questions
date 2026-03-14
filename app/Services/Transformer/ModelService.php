<?php

namespace App\Services\Transformer;

use App\Services\Transformer\TensorMathService;
use Illuminate\Support\Facades\Storage;

class ModelService
{
    // Strict constraints for higher resolution
    private const YEAR_BASE = 2024;
    private const YEAR_MAX_HSC = 2026;
    private const INST_MIN = 2;
    private const INST_MAX = 4;
    private const CAT_MIN = 1;
    private const CAT_MAX = 3;
    private const BOARD_MIN = 1;
    private const BOARD_MAX = 10;
    private const CHAPTER_MIN = 1;
    private const CHAPTER_MAX = 12;
    private const SUBJECT_MIN = 1;
    private const SUBJECT_MAX = 20;

    private const VOCAB_LIMIT = 500;

    protected array $weights = [];
    protected array $posEncoding = [];
    protected string $storageKey = 'transformer_v4_weights.json';
    
    public function __construct(
        protected TensorMathService $tensorMath,
        protected int $vocabSize = 500,
        protected int $dModel = 64,
        protected int $nHeads = 4,
        protected int $dFf = 256,
        protected int $maxSeqLen = 128,
        protected float $defaultLearningRate = 0.008, 
        protected float $dropoutProb = 0.0,
        protected float $weightDecay = 0.001 
    ) {
        $this->posEncoding = $this->generatePositionalEncoding();
        $this->loadModel();
    }

    public function predict(string $text): array
    {
        $tokens = $this->tokenize($text);
        $rawFloats = $this->forward($tokens, false);
        
        return [
            'predictions' => $this->denormalize($rawFloats),
            'confidence' => array_map(fn($v) => round($v, 4), $rawFloats)
        ];
    }

    public function learn(string $text, array $targets, ?float $lr = null): float
    {
        $tokens = $this->tokenize($text);
        return $this->train($tokens, $targets, $lr);
    }

    public function tokenize(string $text): array
    {
        $text = strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', $text));
        $words = array_filter(explode(' ', $text));
        
        $tokens = [];
        foreach ($words as $word) {
            $tokens[] = abs(crc32($word)) % self::VOCAB_LIMIT;
        }

        return empty($tokens) ? [0] : array_slice($tokens, 0, $this->maxSeqLen - 1);
    }

    public function forward(array $inputTokens, bool $isTraining = false): array
    {
        $sequence = array_merge([0], $inputTokens);
        $sequence = array_slice($sequence, 0, $this->maxSeqLen);

        $x = [];
        foreach ($sequence as $pos => $id) {
            $emb = $this->weights['token_emb'][$id] ?? array_fill(0, $this->dModel, 0.0);
            $x[] = $this->tensorMath->addVectors($emb, $this->posEncoding[$pos]);
        }

        $attended = $this->multiHeadAttention($x, $isTraining);
        $x = $this->layerNorm($this->tensorMath->addMatrices($x, $attended));

        $ff = $this->feedForward($x);
        $x = $this->layerNorm($this->tensorMath->addMatrices($x, $ff));

        $clsRepresentation = $x[0]; 

        $output = $this->tensorMath->dotProduct([$clsRepresentation], $this->weights['w_out'])[0];
        $raw = $this->tensorMath->addVectors($output, $this->weights['b_out']);

        return array_map(fn($v) => max(-1.5, min(1.5, $v)), $raw);
    }

    protected function train(array $inputTokens, array $targetVector, ?float $lr = null): float
    {
        $currentLr = $lr ?? ($this->weights['current_lr'] ?? $this->defaultLearningRate);
        $gradClip = 0.05; 
        
        $instId = $targetVector[0];
        $normTargets = [];

        // 1. Strict Normalization Logic
        $normTargets[] = ($targetVector[0] - self::INST_MIN) / (self::INST_MAX - self::INST_MIN);
        $normTargets[] = ($targetVector[1] - self::SUBJECT_MIN) / (self::SUBJECT_MAX - self::SUBJECT_MIN);
        
        if ($instId == 4) {
            $normTargets[] = $targetVector[2] / 100; // BCS (e.g. 45 -> 0.45)
        } else {
            $normTargets[] = ($targetVector[2] - self::YEAR_BASE) / (self::YEAR_MAX_HSC - self::YEAR_BASE);
        }

        $normTargets[] = ($targetVector[3] - self::BOARD_MIN) / (self::BOARD_MAX - self::BOARD_MIN);
        $normTargets[] = ($targetVector[4] - self::CHAPTER_MIN) / (self::CHAPTER_MAX - self::CHAPTER_MIN);
        $normTargets[] = ($targetVector[5] - self::CAT_MIN) / (self::CAT_MAX - self::CAT_MIN);

        $prediction = $this->forward($inputTokens, true);

        $loss = 0.0;
        $errors = [];
        foreach ($normTargets as $i => $target) {
            $err = $target - $prediction[$i];
            $errors[$i] = $err;
            $loss += pow($err, 2);

            // Snapping force to help differentiate close IDs
            $force = (abs($err) > 0.01) ? $err * 1.5 : $err;
            $deltaBias = $currentLr * $force;
            $this->weights['b_out'][$i] += max(-$gradClip, min($gradClip, $deltaBias)); 
            
            foreach ($this->weights['w_out'] as $dimIdx => &$weightRow) {
                $grad = $force - ($this->weightDecay * $weightRow[$i]);
                $weightRow[$i] += max(-$gradClip, min($gradClip, $currentLr * $grad));
            }
        }

        // Token Update Logic
        $fullSequence = array_merge([0], $inputTokens);
        $avgError = array_sum($errors) / count($errors);
        foreach ($fullSequence as $tokenId) {
            foreach ($this->weights['token_emb'][$tokenId] as $dim => &$val) {
                $tokenInfluence = ($currentLr > 0.002) ? 0.02 : 0.005;
                $val += max(-$gradClip, min($gradClip, $currentLr * $avgError * $tokenInfluence));
            }
        }

        $this->weights['current_lr'] = max(0.0005, $currentLr * 0.992);
        $this->saveModel();
        
        return $loss / count($normTargets);
    }

    protected function denormalize(array $rawOutput): array
    {
        $mapped = [];
        $e = 0.00001; // Epsilon for rounding stability

        // Institution: 0-1 -> 2-4
        $instId = (int)round((max(0, min(1, $rawOutput[0])) * (self::INST_MAX - self::INST_MIN)) + self::INST_MIN);
        $mapped['institution_id'] = $instId;

        // Subject: 0-1 -> 1-20
        $mapped['subject_id'] = (int)round((max(0, min(1, $rawOutput[1])) * (self::SUBJECT_MAX - self::SUBJECT_MIN)) + self::SUBJECT_MIN);

        // Year Logic
        $yVal = max(0, min(1, $rawOutput[2]));
        if ($instId === 4) {
            $mapped['year'] = (int)round($yVal * 100);
        } else {
            $mapped['year'] = (int)round(($yVal * (self::YEAR_MAX_HSC - self::YEAR_BASE)) + self::YEAR_BASE);
        }

        // Board: 1-10, Chapter: 1-12, Category: 1-3
        $mapped['board_id'] = (int)round((max(0, min(1, $rawOutput[3])) * (self::BOARD_MAX - self::BOARD_MIN)) + self::BOARD_MIN);
        $mapped['chapter'] = (int)round((max(0, min(1, $rawOutput[4])) * (self::CHAPTER_MAX - self::CHAPTER_MIN)) + self::CHAPTER_MIN);
        $mapped['category'] = (int)round((max(0, min(1, $rawOutput[5])) * (self::CAT_MAX - self::CAT_MIN)) + self::CAT_MIN);

        return $mapped;
    }

    protected function multiHeadAttention(array $x, bool $isTraining): array
    {
        $allHeads = [];
        for ($i = 0; $i < $this->nHeads; $i++) {
            $w = $this->weights['heads'][$i];
            $q = $this->tensorMath->addMatrices($this->tensorMath->dotProduct($x, $w['wq']), [$w['bq']]);
            $k = $this->tensorMath->addMatrices($this->tensorMath->dotProduct($x, $w['wk']), [$w['bk']]);
            $v = $this->tensorMath->addMatrices($this->tensorMath->dotProduct($x, $w['wv']), [$w['bv']]);
            $allHeads[] = $this->tensorMath->scaledDotProductAttention($q, $k, $v);
        }
        $concatenated = $this->tensorMath->concatenateHeads($allHeads);
        $out = $this->tensorMath->dotProduct($concatenated, $this->weights['wo']);
        return $this->tensorMath->addMatrices($out, [$this->weights['bo']]);
    }

    protected function feedForward(array $x): array
    {
        $h = $this->tensorMath->dotProduct($x, $this->weights['w1']);
        $h = $this->tensorMath->addMatrices($h, [$this->weights['b1']]);
        $h = $this->tensorMath->applyActivation($h, 'relu'); 
        $out = $this->tensorMath->dotProduct($h, $this->weights['w2']);
        return $this->tensorMath->addMatrices($out, [$this->weights['b2']]);
    }

    protected function generatePositionalEncoding(): array
    {
        $pe = [];
        for ($pos = 0; $pos < $this->maxSeqLen; $pos++) {
            for ($i = 0; $i < $this->dModel; $i++) {
                $divTerm = pow(10000, (2 * (int)($i / 2)) / $this->dModel);
                $pe[$pos][$i] = ($i % 2 === 0) ? sin($pos / $divTerm) : cos($pos / $divTerm);
            }
        }
        return $pe;
    }

    public function initializeWeights(): void
    {
        $headDim = $this->dModel / $this->nHeads;
        $this->weights = [
            'current_lr' => $this->defaultLearningRate,
            'token_emb' => $this->tensorMath->randomMatrix($this->vocabSize, $this->dModel),
            'heads' => [],
            'wo' => $this->tensorMath->randomMatrix($this->dModel, $this->dModel),
            'bo' => array_fill(0, $this->dModel, 0.0),
            'w1' => $this->tensorMath->randomMatrix($this->dModel, $this->dFf),
            'b1' => array_fill(0, $this->dFf, 0.0),
            'w2' => $this->tensorMath->randomMatrix($this->dFf, $this->dModel),
            'b2' => array_fill(0, $this->dModel, 0.0),
            'w_out' => $this->tensorMath->randomMatrix($this->dModel, 6),
            'b_out' => array_fill(0, 6, 0.0),
            'gamma' => array_fill(0, $this->dModel, 1.0),
            'beta' => array_fill(0, $this->dModel, 0.0),
        ];

        for ($i = 0; $i < $this->nHeads; $i++) {
            $this->weights['heads'][$i] = [
                'wq' => $this->tensorMath->randomMatrix($this->dModel, $headDim),
                'wk' => $this->tensorMath->randomMatrix($this->dModel, $headDim),
                'wv' => $this->tensorMath->randomMatrix($this->dModel, $headDim),
                'bq' => array_fill(0, $headDim, 0.0),
                'bk' => array_fill(0, $headDim, 0.0),
                'bv' => array_fill(0, $headDim, 0.0),
            ];
        }
        $this->saveModel();
    }

    protected function layerNorm(array $x): array
    {
        return $this->tensorMath->layerNormalization($x, $this->weights['gamma'], $this->weights['beta']);
    }

    protected function loadModel(): void 
    {
        if (Storage::exists($this->storageKey)) {
            $this->weights = json_decode(Storage::get($this->storageKey), true);
        } else { 
            $this->initializeWeights(); 
        }
    }

    public function saveModel(): void 
    {
        Storage::put($this->storageKey, json_encode($this->weights));
    }
}