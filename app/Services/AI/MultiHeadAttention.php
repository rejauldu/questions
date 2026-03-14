<?php

namespace App\Services\AI;

use App\Services\AI\TensorService;

class MultiHeadAttention {
    public function __construct(
        protected Head $head, 
        protected TensorService $tensor
    ) {}

    public function forward(array $x, array $w, int $n_embd, int $n_head): array {
        $T = count($x); // Sequence length
        $head_dim = (int) ($n_embd / $n_head);

        // ১. Linear projections (Q, K, V)
        $q_all = $this->tensor->multiply($x, $w['q']);
        $k_all = $this->tensor->multiply($x, $w['k']);
        $v_all = $this->tensor->multiply($x, $w['v']);
        
        $heads_out = [];

        // ২. Multi-head logic: প্রতিটি হেডের জন্য আলাদা সাব-ভেক্টর প্রসেস করা
        for ($i = 0; $i < $n_head; $i++) {
            $offset = $i * $head_dim;
            
            // স্লাইস করে প্রতিটি হেডের জন্য নির্দিষ্ট ডাইমেনশন আলাদা করা
            $q = array_map(fn($row) => array_slice($row, $offset, $head_dim), $q_all);
            $k = array_map(fn($row) => array_slice($row, $offset, $head_dim), $k_all);
            $v = array_map(fn($row) => array_slice($row, $offset, $head_dim), $v_all);

            $heads_out[] = $this->head->forward($q, $k, $v, $head_dim);
        }

        // ৩. Concatenation: সব হেডের আউটপুটকে আবার একটি বড় ভেক্টরে জোড়া লাগানো
        $out = [];
        for ($t = 0; $t < $T; $t++) {
            $combined_row = [];
            foreach ($heads_out as $h) {
                $combined_row = array_merge($combined_row, $h[$t]);
            }
            $out[] = $combined_row;
        }
        
        // ৪. Final projection layer: মডেলে ডাটা ফ্লো সামঞ্জস্য করা
        return $this->tensor->multiply($out, $w['proj']);
    }
}