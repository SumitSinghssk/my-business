<?php

namespace App\Support;

use App\Helpers\Settings;
use App\Models\Blog;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

/**
 * JSON-LD (schema.org) nodes for the website. Views print them with <x-website.json-ld :data="…" />.
 * Empty values are dropped so Google never sees null properties.
 */
class StructuredData
{
    public static function organization(): array
    {
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => url('/').'#organization',
            'name' => Settings::appName(),
            'url' => url('/'),
            'logo' => Settings::logoLight(),
            'email' => Settings::emails()[0] ?? null,
            'telephone' => Settings::phones()[0] ?? null,
            // Multi-line address from the settings on one line.
            'address' => ($address = Settings::addresses()[0]['text'] ?? null) ? trim(preg_replace('/\s*\R\s*/', ', ', $address)) : null,
            'sameAs' => array_column(Settings::socialLinks(), 'url') ?: null,
        ]);
    }

    public static function website(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => url('/').'#website',
            'name' => Settings::appName(),
            'url' => url('/'),
            'publisher' => ['@id' => url('/').'#organization'],
        ];
    }

    public static function blogPosting(Blog $blog, string $authorName): array
    {
        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'mainEntityOfPage' => route('blog.show', $blog->slug),
            'headline' => Str::limit($blog->title, 110, ''),
            'description' => $blog->excerpt,
            'image' => $blog->featured_image_url,
            'datePublished' => $blog->published_date->toAtomString(),
            'dateModified' => $blog->updated_at?->toAtomString(),
            'wordCount' => str_word_count(strip_tags((string) $blog->content)),
            'articleSection' => $blog->categories->first()?->name,
            'author' => ['@type' => 'Person', 'name' => $authorName],
            'publisher' => ['@id' => url('/').'#organization'],
        ]);
    }

    public static function service(Service $service): array
    {
        $highlights = $service->highlights ?? [];

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service->title,
            'description' => $service->excerpt,
            'url' => route('services.show', $service->slug),
            'image' => $service->featured_image_url,
            'serviceType' => $service->title,
            'provider' => ['@id' => url('/').'#organization'],
            'hasOfferCatalog' => $highlights ? [
                '@type' => 'OfferCatalog',
                'name' => $service->title,
                'itemListElement' => array_map(fn ($item) => ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => $item]], $highlights),
            ] : null,
        ]);
    }

    public static function project(Project $project, ?Service $service): array
    {
        $tags = $project->tags ?? [];

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $project->title,
            'headline' => $project->title,
            'description' => $project->excerpt,
            'url' => route('work.show', $project->slug),
            'image' => $project->featured_image_url,
            'dateCreated' => $project->year,
            'dateModified' => $project->updated_at?->toAtomString(),
            'genre' => $project->industry,
            'keywords' => $tags ? implode(', ', $tags) : null,
            'about' => $service ? ['@type' => 'Service', 'name' => $service->title, 'url' => route('services.show', $service->slug)] : null,
            'sourceOrganization' => $project->client ? ['@type' => 'Organization', 'name' => $project->client] : null,
            'creator' => ['@id' => url('/').'#organization'],
        ]);
    }

    /** The projects on one page of the Work listing. */
    public static function projectList(LengthAwarePaginator $projects, ?Service $service): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => $service ? $service->title.' projects' : 'Case studies',
            'itemListElement' => collect($projects->items())->values()->map(fn ($project, $i) => [
                '@type' => 'ListItem',
                'position' => $projects->firstItem() + $i,
                'url' => route('work.show', $project->slug),
                'name' => $project->title,
            ])->all(),
        ];
    }

    /** @param  array<int, array{question: string, answer: string}>  $faqs */
    public static function faqPage(array $faqs): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
            ], $faqs),
        ];
    }

    /** Encode for a <script type="application/ld+json"> tag (JSON_HEX_TAG keeps "</script>" in text harmless). */
    public static function encode(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    }
}
