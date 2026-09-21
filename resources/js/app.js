import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Blog listing: category tabs and pagination swap the grid in place (links still work without JS).
Alpine.data('blogListing', ({ titles }) => ({
    active: new URL(location.href).searchParams.get('category') ?? '',
    loading: false,
    controller: null,

    init() {
        // Keep the tabs, grid and title in sync with back/forward navigation.
        window.addEventListener('popstate', () => {
            const url = new URL(location.href);
            this.active = url.searchParams.get('category') ?? '';
            this.load(url.href, false);
        });
    },

    select(tab) {
        if (tab.dataset.category === this.active && !new URL(location.href).searchParams.has('page')) return;
        this.active = tab.dataset.category;
        this.load(tab.href);
    },

    paginate(event) {
        const link = event.target.closest('a[href]');
        if (!link || !link.closest('[data-blog-pagination]')) return;
        event.preventDefault();
        this.load(link.href, true, true);
    },

    async load(href, push = true, scroll = false) {
        this.controller?.abort();
        this.controller = new AbortController();
        this.loading = true;

        try {
            const response = await fetch(href, {
                headers: { 'X-Blog-Fragment': '1', Accept: 'text/html' },
                signal: this.controller.signal,
            });
            if (!response.ok) throw new Error(response.status);

            this.$refs.listing.innerHTML = await response.text();
            if (push) history.pushState({}, '', href);
            document.title = titles[this.active] ?? document.title;
            if (scroll) this.$root.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (error) {
            if (error.name !== 'AbortError') location.href = href;
        } finally {
            this.loading = false;
        }
    },
}));

Alpine.start();

// Testimonials slider (home page only): Swiper and its CSS load when the section comes near the viewport,
// so other pages (and the first paint of the home page) don't pay for them.
const testimonials = document.querySelectorAll('[data-testimonials]');

if (testimonials.length) {
    const load = () => import('./testimonials').then(({ default: initTestimonials }) => initTestimonials(testimonials));

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                if (entries.some((entry) => entry.isIntersecting)) {
                    observer.disconnect();
                    load();
                }
            },
            { rootMargin: '400px 0px' },
        );
        testimonials.forEach((root) => observer.observe(root));
    } else {
        load();
    }
}
