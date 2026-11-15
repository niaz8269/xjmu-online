<?php

namespace App\Support;

/** Builds the entries of the schema.org FAQPage `mainEntity` array (the JSON-LD block of the FAQ pages). */
class FaqSchema
{
    /**
     * @param  iterable<int, array{0: string, 1: string}|array<string, string>>  $pairs  rows of [question, answer]
     * @return string comma-separated Question objects, ready to be dropped inside `"mainEntity": [ ... ]`
     */
    public static function items(iterable $pairs): string
    {
        $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        $items = [];
        foreach ($pairs as [$question, $answer]) {
            $items[] = '{"@type":"Question","name":' . json_encode((string) $question, $flags)
                . ',"acceptedAnswer":{"@type":"Answer","text":' . json_encode((string) $answer, $flags) . '}}';
        }

        return implode(",\n    ", $items);
    }
}
