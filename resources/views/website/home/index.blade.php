<x-website :image="asset('images/website/hero/dashboard.jpg')">
    <div class="bg-surface text-on-surface selection:bg-primary-container flex w-full flex-col selection:text-white">
        @include('website.home.sections.hero')
        @include('website.home.sections.tech-strip')
        @include('website.home.sections.about')
        @include('website.home.sections.delivery')
        @include('website.home.sections.selected-work')
        @include('website.home.sections.technology')
        @include('website.home.sections.why-us')
        @include('website.home.sections.industries')
        @include('website.home.sections.testimonial')
        @include('website.home.sections.insights')
        <x-website.faq />
        @include('website.home.sections.cta')
    </div>
</x-website>
