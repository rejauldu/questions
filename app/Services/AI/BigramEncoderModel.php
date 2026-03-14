<?php

namespace App\Services\AI;

class BigramEncoderModel
{
    public array $data;
    public int $n_embd = 16;
    public int $n_head = 4;
    public int $n_layer = 2;
    public int $block_size = 32;
    private string $path;

    public function __construct() {
        $this->path = storage_path('app/ai/transformer_v2.php');
        $this->data = file_exists($this->path) ? require $this->path : $this->install();
    }

    private function install(): array {
        // প্রাথমিক ভোকাবুলারি
        $vocab = ['<unk>' => 0];
        
        return [
            'vocab' => $vocab,
            'token_embedding_table' => $this->initMatrix(count($vocab), $this->n_embd),
            'position_embedding_table' => $this->initMatrix($this->block_size, $this->n_embd),
            'blocks' => array_map(fn() => $this->initBlockParams(), range(1, $this->n_layer)),
            'lm_head' => $this->initMatrix($this->n_embd, 6), // ৬টি আউটপুট
            'bias' => array_fill(0, 6, 0.0) 
        ];
    }

    /**
     * নতুন শব্দ আসলে ভোকাবুলারিতে যোগ করা এবং এমবেডিং টেবিল বড় করা
     */
    public function addToVocab(string $word): void {
        if (!isset($this->data['vocab'][$word])) {
            $newId = count($this->data['vocab']);
            $this->data['vocab'][$word] = $newId;
            
            // নতুন শব্দের জন্য এমবেডিং টেবিলে একটি নতুন র‍্যান্ডম ভেক্টর যোগ করা
            $this->data['token_embedding_table'][] = array_map(
                fn() => (mt_rand()/mt_getrandmax()-0.5)*0.1, 
                range(1, $this->n_embd)
            );
        }
    }

    private function initBlockParams(): array {
        return [
            'mha' => [
                'q' => $this->initMatrix($this->n_embd, $this->n_embd),
                'k' => $this->initMatrix($this->n_embd, $this->n_embd),
                'v' => $this->initMatrix($this->n_embd, $this->n_embd),
                'proj' => $this->initMatrix($this->n_embd, $this->n_embd),
            ],
            'ffwd' => [
                'w1' => $this->initMatrix($this->n_embd, 4 * $this->n_embd),
                'w2' => $this->initMatrix(4 * $this->n_embd, $this->n_embd),
            ]
        ];
    }

    private function initMatrix($r, $c) {
        return array_map(fn() => array_map(fn() => (mt_rand()/mt_getrandmax()-0.5)*0.1, range(1, $c)), range(1, $r));
    }

    public function save(): void {
        if (!is_dir(dirname($this->path))) {
            mkdir(dirname($this->path), 0755, true);
        }
        file_put_contents($this->path, "<?php\nreturn " . var_export($this->data, true) . ";");
    }
}