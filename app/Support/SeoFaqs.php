<?php

namespace App\Support;

/** Code-side FAQs (config/seo_faqs.php) merged after admin-managed ones, without repeating a question. */
class SeoFaqs
{
    /**
     * @param  array<int, array{0: string, 1: string}>  $existing  [question, answer] rows already on the page
     * @return array<int, array{0: string, 1: string}>  $existing followed by the configured rows not already present
     */
    public static function merge(array $existing, string $key): array
    {
        $seen = [];
        foreach ($existing as [$question]) {
            $seen[self::norm($question)] = true;
        }
        foreach (config('seo_faqs.' . $key, []) as [$question, $answer]) {
            if (! isset($seen[self::norm($question)])) {
                $existing[] = [$question, $answer];
                $seen[self::norm($question)] = true;
            }
        }

        return $existing;
    }

    private static function norm(string $question): string
    {
        return strtolower(preg_replace('/[^a-z0-9]+/i', '', $question));
    }
}
