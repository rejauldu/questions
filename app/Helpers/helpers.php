<?php

if (!function_exists('url_slug')) {
    function url_slug($text = '', $fallback = 'no-title'): string
    {
        $text = (string) $text;
        $text = trim($text);

        if ($text === '') {
            return $fallback;
        }

        $text = mb_strtolower($text, 'UTF-8');

        // Keep letters (\p{L}), numbers (\p{N}) and combining marks (\p{M})
        $text = preg_replace('/[^\p{L}\p{N}\p{M}]+/u', '-', $text);

        // Merge multiple dashes
        $text = preg_replace('/-+/u', '-', $text);

        // Trim dashes
        $text = trim($text, '-');

        return mb_substr($text, 0, 100, 'UTF-8') ?: $fallback;
    }
}

if (!function_exists('ordinal_suffix')) {
    /**
     * Add ordinal suffix to a number (st, nd, rd, th)
     *
     * @param int $num
     * @return string
     */
    function ordinal_suffix($num)
    {
        $num = (int) $num; 
        if ($num % 100 >= 11 && $num % 100 <= 13) {
            return $num . 'th';
        }
        switch ($num % 10) {
            case 1:  return $num . 'st';
            case 2:  return $num . 'nd';
            case 3:  return $num . 'rd';
            default: return $num . 'th';
        }
    }
}

if (!function_exists('question_meta_text')) {
    /**
     * Generate a meta string for a question, joining institution, subject, class, board, year.
     *
     * @param  object  $post
     * @return string
     */
    function question_meta_text($post, $implode = " - "): string
    {
        $parts = [];
        
        // Check if it's a BCS category
        $categoryName = $post->institution->name ?? '';
        $isBCS = stripos($categoryName, 'BCS') !== false;
    
        if ($isBCS) {
            // Format: "38th BCS English"
            $bcsPart = '';
            if (!empty($post->year)) {
                $bcsPart = ordinal_suffix($post->year) . ' BCS';
            } else {
                $bcsPart = 'BCS';
            }
    
            $subjectPart = $post->subject->name ?? '';
            
            return trim("$bcsPart $subjectPart");
        }
    
        // Original logic for HSC/SSC
        if (!empty($post->institution->name)) {
            $parts[] = explode('/', $post->institution->name)[0];
        }
    
        if (!empty($post->subject->name)) {
            $parts[] = $post->subject->name;
        }
    
        if (!empty($post->class)) {
            $parts[] = ordinal_suffix($post->class) . ' year';
        }
    
        if (!empty($post->board->name)) {
            $parts[] = $post->board->name;
        }
    
        if (!empty($post->year)) {
            $parts[] = $post->year;
        }
    
        return implode($implode, $parts);
    }
}
if (!function_exists('question_image_basename')) {
    /**
     * Generate base filename (WITHOUT ID)
     */
    function question_image_basename(array $data): string
    {
        $parts = [];

        if (!empty($data['institution_id'])) {
            $institution = \App\Models\Institution::find($data['institution_id']);
            if ($institution?->name) {
                $parts[] = explode('/', $institution->name)[0];
            }
        }

        if (!empty($data['subject_id'])) {
            $subject = \App\Models\Subject::find($data['subject_id']);
            if ($subject?->name) {
                $parts[] = $subject->name;
            }
        }
        
        if (!empty($data['board_id'])) {
            $board = \App\Models\Board::find($data['board_id']);
            if ($board?->name) {
                $parts[] = $board->name;
            }
        }

        if (!empty($data['class'])) {
            $parts[] = ordinal_suffix($data['class']) . ' year';
        }

        if (!empty($data['year'])) {
            $parts[] = $data['year'];
        }

        return \Illuminate\Support\Str::slug(implode(' ', $parts));
    }
}

if (!function_exists('firstPart')) {
    /**
     * Return the first part of a string separated by slash (/)
     *
     * @param string|null $name
     * @return string|null
     */
    function firstPart(?string $name): ?string
    {
        if (!$name) return null;

        // Split by slash and trim whitespace
        $parts = explode('/', $name);

        return trim($parts[0]);
    }
}
if (!function_exists('public_html_path')) {
    /**
     * Get the path to the public_html folder.
     *
     * @param  string  $path
     * @return string
     */
    function public_html_path($path = '')
    {
        return base_path('public_html' . ($path ? DIRECTORY_SEPARATOR . $path : ''));
    }
}
if (!function_exists('institution')) {
    function institution(?string $name): string
    {
        if (!$name) {
            return '';
        }

        return trim(explode('/', $name)[0]);
    }
}
if (!function_exists('subject')) {
    function subject(?string $subject): string
    {
        if (!$subject) {
            return '';
        }

        $subject = trim($subject);

        // Append ' paper' only if the string ends with 1st or 2nd (case-insensitive)
        if (preg_match('/(1st|2nd)$/i', $subject)) {
            return $subject . ' paper';
        }

        return $subject;
    }
}
if (!function_exists('slug')) {
    /**
     * Convert a string into a URL-friendly slug.
     *
     * @param string $name
     * @return string
     */
    function slug(string $name): string
    {
        // Use Laravel Str helper for slug
        return \Illuminate\Support\Str::slug($name);
    }
}

if (!function_exists('clean_html_between_tags')) {
    /**
     * Convert a string into a URL-friendly slug.
     *
     * @param string $name
     * @return string
     */
    function clean_html_between_tags(string $html): string
    {
        return preg_replace('/>\s+</', '><', $html);
    }
}

function smart_nl2br(string $html): string
{
    $parts = preg_split('/(<pre\b[^>]*>.*?<\/pre>)/si', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

    foreach ($parts as $i => $part) {
        if (!preg_match('/^<pre\b/i', $part)) {
            $parts[$i] = nl2br($part);
        }
    }

    return implode('', $parts);
}
if (!function_exists('bnNum')) {
    /**
     * Convert English numbers to Bengali numbers.
     *
     * @param  mixed  $number
     * @return string
     */
    function bnNum($number): string
    {
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];

        return str_replace($en, $bn, (string)$number);
    }
}
if (!function_exists('enNum')) {
    function enNum($number): string {
        $bn = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        return str_replace($bn, $en, (string)$number);
    }
}
if (!function_exists('bnBoard')) {
    /**
     * Translate English Board names to Bengali.
     *
     * @param  string|null  $name
     * @return string
     */
    function bnBoard($name): string
    {
        $boards = [
            'Dhaka'      => 'ঢাকা',
            'Chittagong' => 'চট্টগ্রাম',
            'Comilla'    => 'কুমিল্লা',
            'Rajshahi'   => 'রাজশাহী',
            'Jessore'    => 'যশোর',
            'Barishal'   => 'বরিশাল',
            'Sylhet'     => 'সিলেট',
            'Dinajpur'   => 'দিনাজপুর',
            'Mymensingh' => 'ময়মনসিংহ',
            'Madrasah'   => 'মাদ্রাসা',
            'Technical'  => 'কারিগরি',
            'All'        => 'সকল'
        ];

        return $boards[$name] ?? ($name ?? 'সকল');
    }
}
