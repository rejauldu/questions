<?php

namespace App\Services\Transformer;

class TensorMathService
{
    /**
     * Matrix Multiplication (Dot Product)
     */
    public function dotProduct(array $matrixA, array $matrixB): array
    {
        $result = [];
        $rowsA = count($matrixA);
        if ($rowsA === 0) return [];
        $colsA = count($matrixA[0]);
        $colsB = count($matrixB[0]);

        for ($i = 0; $i < $rowsA; $i++) {
            for ($j = 0; $j < $colsB; $j++) {
                $sum = 0;
                for ($k = 0; $k < $colsA; $k++) {
                    $sum += $matrixA[$i][$k] * $matrixB[$k][$j];
                }
                // Relaxed clipping: 100 is safe for 64-bit floats
                $result[$i][$j] = max(-100, min(100, $sum));
            }
        }
        return $result;
    }

    /**
     * Vector Addition
     */
    public function addVectors(array $a, array $b): array
    {
        return array_map(fn($val, $idx) => $val + ($b[$idx] ?? 0.0), $a, array_keys($a));
    }

    /**
     * Matrix Addition with Broadcasting
     */
    public function addMatrices(array $a, array $b): array
    {
        $result = [];
        $isBias = count($b) === 1;

        foreach ($a as $i => $row) {
            $biasRow = $isBias ? $b[0] : ($b[$i] ?? []);
            foreach ($row as $j => $val) {
                $result[$i][$j] = $val + ($biasRow[$j] ?? 0.0);
            }
        }
        return $result;
    }

    /**
     * Scaled Dot-Product Attention
     */
    public function scaledDotProductAttention(array $q, array $k, array $v): array
    {
        $dk = count($q[0]);
        $scale = sqrt($dk) ?: 1.0; // Avoid division by zero

        $kt = $this->transpose($k);
        $scores = $this->dotProduct($q, $kt);

        $attendedWeights = [];
        foreach ($scores as $row) {
            $scaledRow = array_map(fn($s) => $s / $scale, $row);
            $attendedWeights[] = $this->softmax($scaledRow);
        }

        return $this->dotProduct($attendedWeights, $v);
    }

    /**
     * Multi-Head Concatenation
     */
    public function concatenateHeads(array $heads): array
    {
        if (empty($heads)) return [];
        $result = [];
        $seqLen = count($heads[0]);

        for ($i = 0; $i < $seqLen; $i++) {
            $combinedRow = [];
            foreach ($heads as $head) {
                $combinedRow = array_merge($combinedRow, $head[$i]);
            }
            $result[] = $combinedRow;
        }
        return $result;
    }

    /**
     * Layer Normalization
     */
    public function layerNormalization(array $x, array $gamma, array $beta): array
    {
        $epsilon = 1e-6;
        $normalized = [];

        foreach ($x as $row) {
            $n = count($row);
            if ($n === 0) continue;
            
            $mean = array_sum($row) / $n;
            $variance = array_reduce($row, fn($c, $v) => $c + pow($v - $mean, 2), 0) / $n;
            $stdDev = sqrt($variance + $epsilon);

            $normRow = [];
            foreach ($row as $i => $val) {
                // Apply learnable parameters gamma and beta
                $normRow[] = (($val - $mean) / $stdDev) * ($gamma[$i] ?? 1.0) + ($beta[$i] ?? 0.0);
            }
            $normalized[] = $normRow;
        }

        return $normalized;
    }

    /**
     * ReLU Activation
     */
    public function applyActivation(array $matrix, string $type = 'relu'): array
    {
        return array_map(function ($row) use ($type) {
            return array_map(function ($val) use ($type) {
                if ($type === 'relu') return max(0, $val);
                if ($type === 'tanh') return tanh($val);
                return $val;
            }, $row);
        }, $matrix);
    }

    /**
     * Softmax with Improved Numerical Stability
     */
    public function softmax(array $vector): array
    {
        if (empty($vector)) return [];
        
        $max = max($vector); 
        $exps = array_map(fn($v) => exp($v - $max), $vector);
        $sum = array_sum($exps);
        
        // Use a tiny epsilon to prevent division by zero
        $epsilon = 1e-12;
        if ($sum < $epsilon) return array_fill(0, count($vector), 1.0 / count($vector));
        
        return array_map(fn($e) => $e / $sum, $exps);
    }

    /**
     * Mean Pooling
     */
    public function meanPooling(array $matrix): array
    {
        $rows = count($matrix);
        if ($rows === 0) return [];
        $cols = count($matrix[0]);
        $sums = array_fill(0, $cols, 0.0);

        foreach ($matrix as $row) {
            foreach ($row as $j => $val) {
                $sums[$j] += $val;
            }
        }

        return array_map(fn($s) => $s / $rows, $sums);
    }

    /**
     * Xavier/Glorot Initialization
     * $fanIn = input units, $fanOut = output units
     */
    public function randomMatrix(int $rows, int $cols, float $scale = null): array
    {
        // If no scale is provided, use Xavier Initialization
        if ($scale === null) {
            $scale = sqrt(2.0 / ($rows + $cols));
        }

        $matrix = [];
        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                // Box-Muller transform for Gaussian distribution (better than uniform for DL)
                $u1 = mt_rand() / mt_getrandmax();
                $u2 = mt_rand() / mt_getrandmax();
                $z0 = sqrt(-2.0 * log($u1 ?: 1e-10)) * cos(2.0 * M_PI * $u2);
                $matrix[$i][$j] = $z0 * $scale;
            }
        }
        return $matrix;
    }

    public function transpose(array $matrix): array
    {
        if (empty($matrix)) return [];
        $result = [];
        foreach ($matrix as $i => $row) {
            foreach ($row as $j => $val) {
                $result[$j][$i] = $val;
            }
        }
        return $result;
    }
}