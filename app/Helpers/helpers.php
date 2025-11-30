<?php

if (!function_exists('url_slug')) {
    function url_slug($text = '', $fallback = 'no-title'): string
    {
        $text = (string) $text;
        $text = trim($text);

        if ($text === '') {
            return $fallback;
        }

        $text = mb_strtolower($text, 'UTF-8'); // Keep Unicode
        $text = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $text); // Keep letters (any language) and numbers
        $text = preg_replace('/[\s_]+/u', '-', $text);           // Replace spaces/underscores with -
        $text = preg_replace('/-+/u', '-', $text);               // Merge multiple dashes
        $text = trim($text, '-');

        return mb_substr($text, 0, 100, 'UTF-8');
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