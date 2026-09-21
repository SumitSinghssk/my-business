<x-website>
    <section class="py-space-xl w-full">
        <div class="site-container">
            <div
                class="article-prose is-plain bg-surface-container-lowest min-w-0 rounded-lg p-4 shadow-sm md:p-6"
            >
                {!! $content['html'] !!}
            </div>
        </div>
    </section>

    <x-website.faq class="bg-white" />
</x-website>
