<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Typography\FontFactory;

class ShareImageController extends Controller
{
    private function wordLimitWrap($string, $limit = 12)
    {
        $words = explode(' ', $string);
        $lines = [];
        $currentLine = [];

        foreach ($words as $word) {
            $currentLine[] = $word;
            if (count($currentLine) == $limit) {
                $lines[] = implode(' ', $currentLine);
                $currentLine = [];
            }
        }

        if (!empty($currentLine)) {
            $lines[] = implode(' ', $currentLine);
        }

        return implode("\n", $lines);
    }

    public function generate($id)
    {
        $post = Post::findOrFail($id);
        $fontPath = public_path('fonts/HindSiliguri-Regular.ttf');

        // 1. Prepare Text
        $rawText = strip_tags($post->article);
        $wrappedText = $this->wordLimitWrap($rawText, 12);
        $lineCount = substr_count($wrappedText, "\n") + 1;
        $charCount = mb_strlen($rawText);

        // 2. RESTORED: Your preferred line heights
        if ($charCount > 250) {
            $fontSize = 32; $lineHeight = 1.8;
        } elseif ($charCount > 120) {
            $fontSize = 40; $lineHeight = 2;
        } else {
            $fontSize = 48; $lineHeight = 2.2;
        }

        // 3. Layout Constants
        $leftMargin = 100;
        $optFontSize = 30;
        $optLineHeight = 2; // Keep consistent with your update

        // 4. Handle Options
        $optionKeys = ['a', 'b', 'c', 'd'];
        $processedOptions = [];
        $optionLineCounts = 0;

        foreach ($optionKeys as $key) {
            if (isset($post->$key)) {
                $wrappedOpt = $this->wordLimitWrap(strip_tags($post->$key), 12);
                $processedOptions[$key] = $wrappedOpt;
                $optionLineCounts += (substr_count($wrappedOpt, "\n") + 1);
            }
        }

        // 5. Calculate Dynamic Canvas Height
        // Updated to use your higher line heights so nothing is cut off
        $estimatedQuestionHeight = $lineCount * ($fontSize * $lineHeight);
        
        if ($post->category == 'MCQ') {
            $estimatedOptionsHeight = ($optionLineCounts / 2) * ($optFontSize * $optLineHeight);
        } else {
            $estimatedOptionsHeight = $optionLineCounts * ($optFontSize * $optLineHeight);
        }
        
        // Final buffer padding
        $canvasHeight = max(500, (int)($estimatedQuestionHeight + $estimatedOptionsHeight + 150));

        $img = Image::create(1200, $canvasHeight)->fill('#4338ca');

        // 6. Border
        $img->drawRectangle(20, 20, function ($draw) use ($canvasHeight) {
            $draw->size(1160, $canvasHeight - 40);
            $draw->border('#fbbf24', 2);
        });

        // 7. Branding
        $img->text('ExamDAO', 60, 50, function (FontFactory $font) use ($fontPath) {
            $font->filename($fontPath);
            $font->size(32);
            $font->color('#fbbf24');
            $font->align('left');
            $font->valign('top');
        });

        // 8. Draw Question
        $questionStartY = 120; 
        $img->text($wrappedText, $leftMargin, $questionStartY, function (FontFactory $font) use ($fontPath, $fontSize, $lineHeight) {
            $font->filename($fontPath);
            $font->size($fontSize);
            $font->color('#ffffff');
            $font->align('left');
            $font->valign('top');
            $font->lineHeight($lineHeight);
        });

        // 9. Draw Options (ZERO extra gap - strictly following the text height)
        if (!empty($processedOptions)) {
            $optColor = '#fbbf24';
            // Start right where the question text technically ends
            $optionsStartY = $questionStartY + $estimatedQuestionHeight; 

            if ($post->category == 'MCQ') {
                $col2Offset = 500;
                
                // Column 1
                $img->text("ক) {$processedOptions['a']}", $leftMargin, $optionsStartY, function($f) use ($fontPath, $optFontSize, $optColor, $optLineHeight) {
                    $f->filename($fontPath); $f->size($optFontSize); $f->color($optColor); 
                    $f->align('left'); $f->valign('top'); $f->lineHeight($optLineHeight);
                });
                
                $row2Y = $optionsStartY + ((substr_count($processedOptions['a'], "\n") + 1) * $optFontSize * $optLineHeight);
                
                $img->text("গ) {$processedOptions['c']}", $leftMargin, $row2Y, function($f) use ($fontPath, $optFontSize, $optColor, $optLineHeight) {
                    $f->filename($fontPath); $f->size($optFontSize); $f->color($optColor); 
                    $f->align('left'); $f->valign('top'); $f->lineHeight($optLineHeight);
                });

                // Column 2
                $img->text("খ) {$processedOptions['b']}", $leftMargin + $col2Offset, $optionsStartY, function($f) use ($fontPath, $optFontSize, $optColor, $optLineHeight) {
                    $f->filename($fontPath); $f->size($optFontSize); $f->color($optColor); 
                    $f->align('left'); $f->valign('top'); $f->lineHeight($optLineHeight);
                });

                $img->text("ঘ) {$processedOptions['d']}", $leftMargin + $col2Offset, $row2Y, function($f) use ($fontPath, $optFontSize, $optColor, $optLineHeight) {
                    $f->filename($fontPath); $f->size($optFontSize); $f->color($optColor); 
                    $f->align('left'); $f->valign('top'); $f->lineHeight($optLineHeight);
                });
            } else {
                // Vertical List (No added padding between items)
                $currentY = $optionsStartY;
                $prefixes = ['a' => 'ক) ', 'b' => 'খ) ', 'c' => 'গ) ', 'd' => 'ঘ) '];
                
                foreach ($processedOptions as $key => $optText) {
                    $img->text($prefixes[$key] . $optText, $leftMargin, $currentY, function ($f) use ($fontPath, $optFontSize, $optColor, $optLineHeight) {
                        $f->filename($fontPath); $f->size($optFontSize); $f->color($optColor); 
                        $f->align('left'); $f->valign('top'); $f->lineHeight($optLineHeight);
                    });
                    $currentY += (substr_count($optText, "\n") + 1) * $optFontSize * $optLineHeight;
                }
            }
        }

        // 10. Website URL
        $img->text('examdao.com', 1140, $canvasHeight - 50, function (FontFactory $font) use ($fontPath) {
            $font->filename($fontPath);
            $font->size(18);
            $font->color('#a5b4fc');
            $font->align('right');
            $font->valign('bottom');
        });

        return response($img->toJpeg(85)->toString(), 200, [
            'Content-Type' => 'image/jpeg',
        ]);
    }
}