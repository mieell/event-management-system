<?php

if (! function_exists('event_image_url')) {
    function event_image_url(?string $image): ?string
    {
        if (! $image) {
            return null;
        }

        if (str_starts_with($image, 'assets/')) {
            return base_url($image);
        }

        return site_url('uploads/events/' . $image);
    }
}

if (! function_exists('event_excerpt')) {
    function event_excerpt(?string $text, int $limit = 120): string
    {
        $text = trim((string) $text);

        if ($text === '') {
            return '';
        }

        if (function_exists('mb_strlen') && function_exists('mb_substr')) {
            if (mb_strlen($text) <= $limit) {
                return $text;
            }

            $slice = mb_substr($text, 0, max(1, $limit - 3));
            if (function_exists('mb_strrpos')) {
                $lastSpace = mb_strrpos($slice, ' ');
                if ($lastSpace !== false && $lastSpace > (int) ($limit * .6)) {
                    $slice = mb_substr($slice, 0, $lastSpace);
                }
            }

            return rtrim($slice, " \t\n\r\0\x0B.") . '...';
        }

        if (strlen($text) <= $limit) {
            return $text;
        }

        $slice = substr($text, 0, max(1, $limit - 3));
        $lastSpace = strrpos($slice, ' ');
        if ($lastSpace !== false && $lastSpace > (int) ($limit * .6)) {
            $slice = substr($slice, 0, $lastSpace);
        }

        return rtrim($slice, " \t\n\r\0\x0B.") . '...';
    }
}
