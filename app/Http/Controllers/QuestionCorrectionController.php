<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class QuestionCorrectionController extends Controller
{
    private const DS_TOKEN = "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";
    private const API_URL = 'https://openrouter.ai/api/v1/chat/completions';
    private const LLM_MODEL = 'x-ai/grok-4.1-fast';
    private const QUESTION_TYPES = ['CQ', 'MCQ', 'Writing'];

    public function index()
    {
        $question = Post::whereIn('category', self::QUESTION_TYPES)
            ->where('is_verified', false)
            ->where('institution_id', 4)
            ->whereIn('subject_id', [86, 87])
            ->first();

        if (!$question) return "All questions verified!";
    
        return view('admin.verify-questions', compact('question'));
    }

    /**
     * Store clipboard text to 'article' field if empty.
     */
    public function articleStore(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ]);

        $post = Post::findOrFail($request->post_id);

        if (empty($post->article)) {
            $post->update(['article' => $request->content]);
            return response()->json(['success' => true, 'message' => '<b>Article stored!</b>']);
        }

        return response()->json(['success' => false, 'message' => 'Article already contains data.'], 409);
    }
    
    public function getAiSuggestion(Post $post)
    {
        if (!in_array($post->category, self::QUESTION_TYPES)) {
            return response()->json(['error' => 'Not a valid question type'], 400);
        }

        // Using json_encode to prevent broken prompt syntax if content has quotes
        $payload = json_encode([
            "article" => $post->article,
            "a" => $post->a,
            "b" => $post->b,
            "c" => $post->c,
            "d" => $post->d,
            "ans" => $post->ans,
            "explanation" => $post->explanation
        ]);

        $prompt = "Fix typos in this JSON: {$payload}. Return ONLY raw JSON.";

        $response = Http::withHeaders([
            'Authorization' => "Bearer " . self::DS_TOKEN,
            'Content-Type'  => 'application/json',
        ])->post(self::API_URL, [
            "model" => self::LLM_MODEL,
            "messages" => [["role" => "user", "content" => $prompt]],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $text = $data['choices'][0]['message']['content'] ?? '';
            $cleanJson = preg_replace('/^```json|```$/m', '', $text);
            return response()->json(json_decode(trim($cleanJson), true));
        }

        return response()->json(['error' => 'AI Suggestion failed'], 500);
    }

    public function update(Request $request, Post $post)
    {
        if ($request->action === 'update') {
            $post->update($request->except(['_token', 'action']) + ['is_verified' => true]);
        } else {
            $post->update(['is_verified' => true]); 
        }

        return redirect()->back()->with('success', 'Database updated!');
    }

    public function bulkFixTypos()
    {
        set_time_limit(0); // Prevent timeout
        $wrong = "  imes"; 
        $correct = "\times ";
        $count = 0;
    
        // Updated to use chunking to prevent memory crash
        Post::whereIn('category', self::QUESTION_TYPES)
            ->where(function($query) use ($wrong) {
                $query->where('article', 'LIKE', "%$wrong%")
                    ->orWhere('a', 'LIKE', "%$wrong%")
                    ->orWhere('b', 'LIKE', "%$wrong%")
                    ->orWhere('c', 'LIKE', "%$wrong%")
                    ->orWhere('d', 'LIKE', "%$wrong%")
                    ->orWhere('explanation', 'LIKE', "%$wrong%");
            })
            ->chunk(100, function ($posts) use ($wrong, $correct, &$count) {
                foreach ($posts as $post) {
                    $post->update([
                        'article'     => str_replace($wrong, $correct, $post->article),
                        'a'           => str_replace($wrong, $correct, $post->a),
                        'b'           => str_replace($wrong, $correct, $post->b),
                        'c'           => str_replace($wrong, $correct, $post->c),
                        'd'           => str_replace($wrong, $correct, $post->d),
                        'explanation' => str_replace($wrong, $correct, $post->explanation),
                    ]);
                    $count++;
                }
            });
    
        return "Fixed $count questions! Please delete this route now.";
    }
    
    public function autoPopulateTopic()
    {
        set_time_limit(150); // Give LLM time to respond
        $post = Post::whereIn('category', self::QUESTION_TYPES)
                ->where(function($query) {
                    $query->whereNull('topic_name')->orWhere('topic_name', '');
                })
                ->where('institution_id', 4)
                ->with('subject') 
                ->first();
    
        if (!$post) return "Done! All topics populated.";
    
        $subjectName = $post->subject ? $post->subject->name : 'General Knowledge';
        $prompt = "Identify the most specific TOPIC name for: {$post->article}. Use {$subjectName} context. Return ONLY 1-3 words.";
    
        try {
            $response = Http::timeout(120)->withHeaders([
                'Authorization' => "Bearer " . self::DS_TOKEN,
                'Content-Type'  => 'application/json',
            ])->post(self::API_URL, [
                "model" => self::LLM_MODEL,
                "messages" => [
                    ["role" => "system", "content" => "Provide topic names only."],
                    ["role" => "user", "content" => $prompt]
                ],
                "max_tokens" => 50,
            ]);
    
            if ($response->successful()) {
                $resData = $response->json();
                $suggestedTopic = $resData['choices'][0]['message']['content'] ?? null;
                if ($suggestedTopic) {
                    $cleanTopic = trim(str_replace(['"', "'", '*', '#'], '', $suggestedTopic));
                    $post->update(['topic_name' => Str::limit($cleanTopic, 100)]);
                    return "Updated ID: {$post->id} to <b>{$cleanTopic}</b>. <script>setTimeout(() => { location.reload(); }, 1200);</script>";
                }
            }
            return "Error: " . $response->status();
        } catch (\Exception $e) {
            return "Retrying... <script>setTimeout(() => { location.reload(); }, 3000);</script>";
        }
    }

    public function fixLatex() {
        set_time_limit(0);
        $fields = ['article', 'a', 'b', 'c', 'd', 'explanation'];
        
        // Standard LaTeX keywords
        $keywords = [
            // --- Layout, Functions & Structures ---
            'frac', 'sqrt', 'text', 'vec', 'hat', 'dot', 'bar', 'overline', 'underline', 
            'sum', 'prod', 'int', 'oint', 'log', 'ln', 'sin', 'cos', 'tan', 'cot', 
            'sec', 'csc', 'arcsin', 'arccos', 'arctan', 'mathbb', 'begin', 'end',
        
            // --- Math Operators & Symbols ---
            'pm', 'mp', 'times', 'div', 'approx', 'neq', 'le', 'ge', 'infty', 
            'degree', 'circ', 'angle', 'bullet', 'cdot', 'propto', 'hbar', 'ell', 
            'wp', 'Re', 'Im', 'nabla', 'partial', 'parallel', 'dots', 'quad',
            'det', 'lim', 'to',
        
            // --- Logic & Sets ---
            'forall', 'exists', 'in', 'notin', 'subset', 'supset', 'cup', 'cap', 
            'therefore', 'because', 'implies', 'impliedby', 'iff',
        
            // --- Greek Lowercase ---
            'alpha', 'beta', 'gamma', 'delta', 'epsilon', 'zeta', 'eta', 'theta', 
            'iota', 'kappa', 'lambda', 'mu', 'nu', 'xi', 'omicron', 'pi', 'rho', 
            'sigma', 'tau', 'upsilon', 'phi', 'chi', 'psi', 'omega',
        
            // --- Greek Uppercase ---
            'Delta', 'Gamma', 'Theta', 'Lambda', 'Xi', 'Pi', 'Sigma', 'Phi', 'Psi', 'Omega',
        
            // --- Chemistry & Arrows ---
            'ce', 'bond', 'pH', 'isotope', 'xleftarrow', 'xrightarrow', 
            'rightleftharpoons', 'longrightarrow', 'uparrow', 'downarrow',
            
            //--Added--
            'left', 'right',
        ];
    
        // Sort by length descending to ensure longer commands (like \rightarrow) 
        // are matched before shorter ones (like \right)
        usort($keywords, fn($a, $b) => strlen($b) - strlen($a));
        $keywordListPipe = implode('|', array_map('preg_quote', $keywords));
        
        $updatedCount = 0;
    
        Post::chunk(100, function ($posts) use ($fields, $keywordListPipe, &$updatedCount) {
            foreach ($posts as $post) {
                $hasChanged = false;
                foreach ($fields as $field) {
                    $original = $post->$field;
                    if (empty($original)) continue;
    
                    // Process only content inside $ ... $
                    $current = preg_replace_callback('/\$([^\$]+)\$/', function ($matches) use ($keywordListPipe) {
                        $latex = $matches[1];
    
                        // --- STEP 1: FIX DOUBLE BACKSLASHES BEFORE KEYWORDS ---
                        // Matches two or more backslashes followed by a keyword
                        // e.g., \\dot -> \dot or \\\\frac -> \frac
                        $latex = preg_replace('/\\\\\\\\+(' . $keywordListPipe . ')(?![a-zA-Z])/', '\\\\$1', $latex);
    
                        // --- STEP 2: FIX MISSING BACKSLASHES BEFORE KEYWORDS ---
                        // e.g., "frac" -> "\frac" (if not already preceded by a slash)
                        $latex = preg_replace('/(?<!\\\\)\b(' . $keywordListPipe . ')(?![a-zA-Z])/', '\\\\$1', $latex);
    
                        // --- STEP 3: FIX 4-SLASH MATRIX BREAKS ---
                        // In LaTeX row breaks are \\. If you see \\\\, change to \\
                        // In PHP Regex, \\\\\\\\ matches literal \\\\ 
                        $latex = str_replace('\\\\\\\\', '\\\\', $latex);
    
                        // --- STEP 4: SPECIAL HANDLERS ---
                        // Handle pH specifically to ensure it's \text{pH}
                        $latex = preg_replace('/(?<!\\\\text\{)\\\\pH\b/', '\\text{pH}', $latex);
                        $latex = str_replace('\\text{\\text{pH}}', '\\text{pH}', $latex);
    
                        // --- STEP 5: BENGALI IN FRAC ---
                        $latex = preg_replace_callback('/\\\\frac\{([^{}]+)\}\{([^{}]+)\}/u', function ($fracMatches) {
                            $bengaliRegex = '/[\x{0980}-\x{09FF}]/u';
                            $p1 = $fracMatches[1];
                            $p2 = $fracMatches[2];
                            
                            $newP1 = (preg_match($bengaliRegex, $p1) && !str_contains($p1, '\\text{')) ? "\\text{{$p1}}" : $p1;
                            $newP2 = (preg_match($bengaliRegex, $p2) && !str_contains($p2, '\\text{')) ? "\\text{{$p2}}" : $p2;
                            
                            return "\\frac{{$newP1}}{{$newP2}}";
                        }, $latex);
    
                        return '$' . $latex . '$';
                    }, $original);
    
                    if ($current !== $original) {
                        $post->$field = $current;
                        $hasChanged = true;
                    }
                }
    
                if ($hasChanged) {
                    $post->save();
                    $updatedCount++;
                }
            }
        });
    
        return "Successfully updated $updatedCount rows.";
    }

    public function fixSvg()
    {
        $updatedCount = 0;
        Post::where('article', 'like', '%<svg%')->chunk(100, function ($posts) use (&$updatedCount) {
            foreach ($posts as $post) {
                $original = $post->article;
                $updated = preg_replace_callback('/<svg\b([^>]*)>/i', function ($matches) {
                    $attr = $matches[1];
                    $attr = preg_match('/width=["\']/i', $attr) ? preg_replace('/width=["\'][^"\']*["\']/i', 'width="100%"', $attr) : $attr . ' width="100%"';
                    $attr = preg_replace('/height=["\'][^"\']*["\']/i', '', $attr);
                    return '<svg ' . preg_replace('/\s+/', ' ', trim($attr)) . '>';
                }, $original);

                if ($updated !== $original) {
                    $post->article = $updated;
                    $post->save();
                    $updatedCount++;
                }
            }
        });
        return "Updated SVGs in $updatedCount rows.";
    }

    public function fixPre()
    {
        $this->convertAnsToBangla();
        $updatedCount = 0;
        $fields = ['explanation', 'article'];
    
        // Query posts that contain at least one <pre> tag, excluding subject 5
        Post::where('subject_id', '!=', 5)
            ->where(function ($query) {
                $query->where('explanation', 'LIKE', '%<pre>%')
                      ->orWhere('article', 'LIKE', '%<pre>%');
            })
            ->chunk(100, function ($posts) use ($fields, &$updatedCount) {
                foreach ($posts as $post) {
                    $hasChanged = false;
                    
                    foreach ($fields as $field) {
                        $originalText = $post->$field;
                        
                        if (empty($originalText)) continue;
    
                        // Regex to find <pre> and </pre> anywhere and remove them
                        // /i makes it case-insensitive, /s allows '.' to match newlines
                        $newText = preg_replace('/<\/?pre[^>]*>/i', '', $originalText);
                        
                        if ($newText !== $originalText) {
                            $post->$field = $newText;
                            $hasChanged = true;
                        }
                    }
                    
                    if ($hasChanged) {
                        $post->save();
                        $updatedCount++;
                    }
                }
            });
    
        return response()->json([
            'message' => "Updated {$updatedCount} rows."
        ]);
    }
    
    public function convertAnsToBangla()
    {
        // Array map for replacement
        $mapping = [
            'a' => 'ক',
            'b' => 'খ',
            'c' => 'গ',
            'd' => 'ঘ'
        ];
    
        // Select only rows where 'ans' matches English options inside subjects 21 and 22
        $query = Post::whereIn('ans', array_keys($mapping));
    
        $totalCount = $query->count();
    
        if ($totalCount === 0) {
            return response()->json([
                'status' => 'success',
                'message' => "No matching rows found with English options in 'ans' field.",
                'updated_ids' => []
            ]);
        }
    
        $updatedIds = [];
    
        // Chunking to keep database operation memory safe
        $query->orderBy('id')->chunk(100, function ($posts) use ($mapping, &$updatedIds) {
            foreach ($posts as $post) {
                // Translate English option to Bangla option
                $newAns = $mapping[$post->ans] ?? null;
    
                if ($newAns) {
                    $post->update(['ans' => $newAns]);
                    $updatedIds[] = $post->id;
                }
            }
        });
    
        return response()->json([
            'status' => 'success',
            'message' => "Successfully converted 'ans' options from English to Bangla.",
            'total_found' => $totalCount,
            'successfully_updated' => count($updatedIds),
            'updated_ids' => $updatedIds
        ]);
    }
}