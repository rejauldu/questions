<?php

namespace App\Services\AI;

class FeedForward {
    public function __construct(protected TensorService $tensor) {}

    public function forward(array $x, array $w): array {
        // ১. Linear layer 1: Expand embedding to 4 * n_embd
        $h = $this->tensor->multiply($x, $w['w1']);
        
        // ২. ReLU Activation: নন-লিনিয়ারিটি যোগ করা
        // $v < 0 হলে এটি ০ করে দেয়, যা মডেলকে অপ্রয়োজনীয় সিগন্যাল ফিল্টার করতে সাহায্য করে
        $h = array_map(function($row) {
            return array_map(fn($v) => max(0.0, $v), $row);
        }, $h);
        
        // ৩. Linear layer 2: Project back to n_embd
        return $this->tensor->multiply($h, $w['w2']);
    }
}