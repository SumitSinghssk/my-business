<?php

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * Cleans HTML from the admin rich-text editor before it is stored. Formatting, tables, images,
 * links and YouTube/Vimeo/Maps embeds are kept; scripts, event handlers, javascript: links,
 * forms and embeds from other sites are removed, so editor content can't run code on the site.
 */
class RichText
{
    private const EMBED_HOSTS = [
        'www.youtube.com', 'youtube.com', 'www.youtube-nocookie.com', 'player.vimeo.com',
        'www.google.com', 'maps.google.com',
    ];

    public static function clean(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        return self::stripForeignEmbeds(self::sanitizer()->sanitize($html));
    }

    private static function sanitizer(): HtmlSanitizer
    {
        static $sanitizer;

        return $sanitizer ??= new HtmlSanitizer(
            (new HtmlSanitizerConfig)
                ->allowSafeElements()
                ->allowElement('details', ['open', 'class', 'style'])
                ->allowElement('summary', ['class', 'style'])
                ->allowElement('video', ['src', 'controls', 'width', 'height', 'poster', 'class', 'style'])
                ->allowElement('source', ['src', 'type'])
                ->allowElement('iframe', ['src', 'width', 'height', 'title', 'allow', 'allowfullscreen', 'loading', 'referrerpolicy', 'class', 'style'])
                ->allowAttribute('style', '*')
                ->allowAttribute('class', '*')
                ->allowAttribute('id', '*')
                ->allowAttribute('target', 'a')
                ->allowAttribute('rel', 'a')
                ->allowLinkSchemes(['http', 'https', 'mailto', 'tel'])
                ->allowMediaSchemes(['http', 'https'])
                ->allowRelativeLinks()
                ->allowRelativeMedias()
                ->allowMediaHosts(null)
                ->withMaxInputLength(-1)
        );
    }

    /** Iframes may only embed from known video/map hosts. */
    private static function stripForeignEmbeds(string $html): string
    {
        return preg_replace_callback('#<iframe\b[^>]*>.*?</iframe>#is', function ($m) {
            preg_match('#\ssrc="([^"]*)"#i', $m[0], $src);
            $host = parse_url(html_entity_decode($src[1] ?? ''), PHP_URL_HOST);

            return in_array(strtolower((string) $host), self::EMBED_HOSTS, true) ? $m[0] : '';
        }, $html) ?? '';
    }
}
