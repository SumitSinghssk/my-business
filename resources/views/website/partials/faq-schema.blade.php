@push('scripts')
    <script type="application/ld+json">
        {!!
            json_encode(
                [
                    '@context' => 'https://schema.org',
                    '@type' => 'FAQPage',
                    'mainEntity' => collect($faqs)
                        ->map(
                            fn ($faq) => [
                                '@type' => 'Question',
                                'name' => $faq['q'],
                                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
                            ],
                        )
                        ->all(),
                ],
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG,
            )
        !!}
    </script>
@endpush
