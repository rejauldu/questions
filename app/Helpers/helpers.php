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
        if (!empty($post->board)) {
            $parts[] = $post->board;
        }

        // Year
        if (!empty($post->year)) {
            $parts[] = $post->year;
        }

        return implode(' - ', $parts);
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