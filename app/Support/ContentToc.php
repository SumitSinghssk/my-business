<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Adds an id to every <h2> in rich-text HTML (for anchor links) and returns
 * the matching table of contents.
 */
class ContentToc
{
    /**
     * @return array{html: string, toc: array<int, array{id: string, title: string}>}
     */
    public static function build(?string $html): array
    {
        $toc = [];
        $used = [];

        $html = preg_replace_callback('/<h2(\s[^>]*)?>(.*?)<\/h2>/is', function (array $match) use (&$toc, &$used) {
            $attributes = $match[1] ?? '';
            $title = trim(html_entity_decode(strip_tags($match[2])));

            if (preg_match('/\sid=["\']([^"\']+)["\']/i', $attributes, $existing)) {
                $id = $existing[1];
            } else {
                $base = Str::slug($title) ?: 'section';
                $id = $base;
                for ($i = 2; in_array($id, $used, true); $i++) {
                    $id = "{$base}-{$i}";
                }
                $attributes .= ' id="'.$id.'"';
            }

            $used[] = $id;
            $toc[] = ['id' => $id, 'title' => $title];

            return "<h2{$attributes}>{$match[2]}</h2>";
        }, (string) $html);

        return ['html' => $html, 'toc' => $toc];
    }
}
