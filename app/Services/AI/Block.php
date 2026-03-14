<?php

namespace App\Services\AI;

class Block {
    public function __construct(
        protected MultiHeadAttention $mha, 
        protected FeedForward $ffwd
    ) {}

    public function forward(array $x, array $w, int $n_embd, int $n_head): array {
        if (empty($x)) return [];

        // ১. Communication: Multi-Head Attention
        $attn = $this->mha->forward($x, $w['mha'], $n_embd, $n_head);
        
        // ২. Residual Connection (Add & Norm এর 'Add' অংশ)
        $x = $this->residualAdd($x, $attn);
        
        // ৩. Computation: Feed Forward
        $ff = $this->ffwd->forward($x, $w['ffwd']);
        
        // ৪. Second Residual Connection
        return $this->residualAdd($x, $ff);
    }

    /**
     * Residual Connection: Original X + Delta
     * ইনডেক্স চেক যোগ করা হয়েছে যাতে কোনো কারণে ডাইমেনশন মিসম্যাচ হলে ক্রাশ না করে।
     */
    private function residualAdd(array $x, array $delta): array {
        $result = [];
        foreach ($x as $i => $row) {
            $newRow = [];
            foreach ($row as $d => $value) {
                // যদি ডেল্টা বা অ্যাটেনশন আউটপুটে ওই ইনডেক্স থাকে তবে যোগ হবে, নাহলে আগের ভ্যালুই থাকবে
                $newRow[$d] = $value + ($delta[$i][$d] ?? 0.0);
            }
            $result[$i] = $newRow;
        }
        return $result;
    }
}