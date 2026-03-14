<?php

namespace App\Services\AI;

class AiSearchService {
    
    public function __construct(
        public BigramEncoderModel $model,
        protected Block $block,
        protected TensorService $tensor
    ) {}

    /**
     * মডেলের ফরোয়ার্ড পাস
     */
    public function forward(array $tokens): array {
        $T = count($tokens);
        // ৬টি প্যারামিটার প্রেডিকশন (institution, subject, year, board, chapter, category)
        if ($T === 0) return array_fill(0, 6, 0.0);

        // ১. Token Embedding নেওয়া
        $tok_emb = array_map(fn($id) => $this->model->data['token_embedding_table'][$id] ?? array_fill(0, $this->model->n_embd, 0.0), $tokens);
        
        // ২. Position Embedding নেওয়া
        $pos_emb = array_slice($this->model->data['position_embedding_table'], 0, $T);
        
        // ৩. ভেক্টর অ্যাডিশন
        $x = [];
        foreach ($tok_emb as $i => $emb) {
            if (!isset($pos_emb[$i])) break; 
            $x[] = $this->tensor->addVectors($emb, $pos_emb[$i]);
        }

        // ৪. Transformer Blocks প্রসেসিং
        foreach ($this->model->data['blocks'] as $w) {
            $x = $this->block->forward($x, $w, $this->model->n_embd, $this->model->n_head);
        }

        // ৫. Pooling and Head
        $x_final = $this->tensor->meanPooling($x);
        $logits = $this->tensor->multiply([$x_final], $this->model->data['lm_head'])[0];
        
        return $this->tensor->addVectors($logits, $this->model->data['bias']);
    }

    /**
     * কোর ট্রেইনিং মেথড (কন্ট্রোলার থেকে কল হবে)
     */
    public function train(string $input, array $targets): float {
        $tokens = $this->tokenize($input);
        if (empty($tokens)) return 0.0;

        // টার্গেট ভেক্টর সাজানো (Normalization লজিক কন্ট্রোলার থেকে আসবে)
        $targetVec = [
            $targets['institution_id'] ?? 0, 
            $targets['subject_id'] ?? 0, 
            $targets['year'] ?? 0, 
            $targets['board_id'] ?? 0, 
            $targets['chapter'] ?? 0,
            $targets['category_id'] ?? 0 
        ];

        $totalEpochLoss = 0;
        $epochs = 30; // লার্নিং ইটারেশন

        for ($e=0; $e < $epochs; $e++) {
            $logits = $this->forward($tokens);
            $currentLoss = 0;
            $activeTargets = 0;
            
            foreach ($targetVec as $i => $val) {
                if ($val <= 0) continue;
                
                $activeTargets++;
                $err = $val - $logits[$i];
                $currentLoss += pow($err, 2);

                // Gradient Clipping (যাতে মডেল ক্র্যাশ না করে)
                $err_clipped = max(-10.0, min(10.0, $err));

                // Bias আপডেট
                $lr = 0.01 / (1 + $e * 0.1); // প্রতি ইপোক-এ লার্নিং রেট সামান্য কমবে
                $this->model->data['bias'][$i] += $lr * $err_clipped;
                
                // Embedding টেবিল আপডেট (Weight Learning)
                foreach ($tokens as $t) {
                    foreach ($this->model->data['token_embedding_table'][$t] as &$v) {
                        $v += 0.005 * $err_clipped;
                        if (!is_finite($v)) $v = 0.0;
                    }
                }
            }
            
            if ($activeTargets > 0) {
                $totalEpochLoss += ($currentLoss / $activeTargets);
            }
        }
        
        $this->model->save();
        return $totalEpochLoss / $epochs;
    }

    /**
     * টেক্সট থেকে প্রেডিকশন জেনারেট করা
     */
    public function generate(string $text): array {
        return $this->forward($this->tokenize($text));
    }

    /**
     * টোকেনাইজেশন এবং ভোকাবুলারি ম্যানেজমেন্ট
     */
    public function tokenize($t): array {
        if (empty($t)) return [];
        
        // ক্লিন টেক্সট প্রসেসিং
        $text = strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', '', (string)$t));
        $words = array_filter(explode(' ', $text));
        $tokens = [];

        foreach ($words as $w) {
            // নতুন শব্দ হলে ভোকাবুলারিতে যোগ করো
            if (!isset($this->model->data['vocab'][$w])) {
                $newId = count($this->model->data['vocab']);
                $this->model->data['vocab'][$w] = $newId;
                
                // নতুন শব্দের জন্য র‍্যান্ডম এমবেডিং ভেক্টর
                $this->model->data['token_embedding_table'][$newId] = array_map(
                    fn() => (mt_rand() / mt_getrandmax() - 0.5) * 0.1, 
                    range(1, $this->model->n_embd)
                );
            }
            $tokens[] = $this->model->data['vocab'][$w];
        }

        return array_values($tokens);
    }
}