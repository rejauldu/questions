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
    function question_meta_text($post): string
    {
        $parts = [];

        // Institution (take first part if there's a slash)
        if (!empty($post->institution->name)) {
            $parts[] = explode('/', $post->institution->name)[0];
        }

        // Subject
        if (!empty($post->subject->name)) {
            $parts[] = $post->subject->name;
        }

        // Class with ordinal suffix
        if (!empty($post->class)) {
            $parts[] = ordinal_suffix($post->class) . ' year';
        }

        // Board
        if (!empty($post->board->name)) {
            $parts[] = $post->board->name . " Board";
        }

        // Year
        if (!empty($post->year)) {
            $parts[] = $post->year;
        }

        return implode(' - ', $parts);
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
                $parts[] = $board->name . " Board";
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

        // ICT should stay as-is
        if (strtoupper($subject) === 'ICT') {
            return 'ICT';
        }

        return $subject . ' paper';
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
