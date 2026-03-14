<?php

namespace App\Services\AI;

class Head {
    /**
     * forward pass for a single attention head
     */
    public function forward(array $q, array $k, array $v, int $head_size): array {
        $T = count($q);
        $out = [];
        $scale = 1.0 / sqrt($head_size);

        foreach ($q as $i => $qv) {
            $weights = [];
            
            // ১. Dot Product ক্যালকুলেশন ও স্কেলিং
            foreach ($k as $j => $kv) {
                $dot = 0;
                foreach ($qv as $d => $val) {
                    $dot += $val * $kv[$d];
                }
                $weights[$j] = $dot * $scale;
            }

            // ২. Softmax Stabilization (Max বিয়োগ করা যাতে exp() ইনফিনিটি না হয়)
            $max_w = !empty($weights) ? max($weights) : 0.0;
            $exp_weights = [];
            foreach ($weights as $j => $w) {
                $exp_weights[$j] = exp($w - $max_w);
            }
            
            $sum = array_sum($exp_weights) ?: 1.0; // Division by zero সুরক্ষা

            // ৩. Value-র সাথে ওয়েট গুণ করে আউটপুট তৈরি
            $res = array_fill(0, count($v[0]), 0.0);
            foreach ($v as $j => $vv) {
                $normalized_w = $exp_weights[$j] / $sum;
                foreach ($vv as $d => $val) {
                    $res[$d] += $val * $normalized_w;
                }
            }
            $out[] = $res;
        }
        return $out;
    }
}