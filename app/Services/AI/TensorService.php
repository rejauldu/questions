<?php

namespace App\Services\AI;

use Exception;

class TensorService
{
    /**
     * Matrix Multiplication: C = A * B
     * i-k-j অর্ডার ব্যবহার করা হয়েছে যা ক্যাশ মেমরির জন্য ফাস্ট।
     */
    public function multiply(array $A, array $B): array
    {
        $rowsA = count($A);
        $colsA = count($A[0]);
        $rowsB = count($B);
        $colsB = count($B[0]);

        if ($colsA !== $rowsB) {
            throw new Exception("Matrix dimension mismatch: A-cols ($colsA) must match B-rows ($rowsB)");
        }

        $C = array_fill(0, $rowsA, array_fill(0, $colsB, 0.0));

        for ($i = 0; $i < $rowsA; $i++) {
            for ($k = 0; $k < $colsA; $k++) {
                $temp = $A[$i][$k];
                if ($temp == 0) continue; // Optimization for sparse tokens
                for ($j = 0; $j < $colsB; $j++) {
                    $C[$i][$j] += $temp * $B[$k][$j];
                }
            }
        }

        return $C;
    }

    /**
     * Softmax Function
     * ভেক্টর বা ম্যাট্রিক্সের শেষ ডাইমেনশনের উপর কাজ করে।
     */
    public function softmax(array $vector): array
    {
        if (empty($vector)) return [];
        $max = max($vector);
        $exps = array_map(fn($v) => exp($v - $max), $vector);
        $sum = array_sum($exps);
        return array_map(fn($v) => $v / ($sum ?: 1.0), $exps);
    }

    /**
     * Mean Pooling
     * অনেকগুলো এমবেডিং ভেক্টরকে একটি বাক্যের ভেক্টরে রূপান্তর করে।
     */
    public function meanPooling(array $embeddings): array
    {
        $count = count($embeddings);
        if ($count === 0) return [];
        
        $dim = count($embeddings[0]);
        $mean = array_fill(0, $dim, 0.0);

        foreach ($embeddings as $vec) {
            foreach ($vec as $i => $val) {
                $mean[$i] += $val;
            }
        }

        return array_map(fn($v) => $v / $count, $mean);
    }

    /**
     * Scale and Add
     * লিনিয়ার লেয়ারে Bias যোগ করার জন্য উপযোগী।
     */
    public function addVectors(array $v1, array $v2): array
    {
        return array_map(fn($a, $b) => $a + $b, $v1, $v2);
    }

    /**
     * Transpose Matrix
     */
    public function transpose(array $matrix): array
    {
        return array_map(null, ...$matrix);
    }
}