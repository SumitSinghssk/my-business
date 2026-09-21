import Swiper from 'swiper';
import 'swiper/css/a11y';
import { A11y, Autoplay, Keyboard, Navigation } from 'swiper/modules';

/** Testimonials slider on the home page (loaded on demand from app.js). */
export default function initTestimonials(roots) {
    roots.forEach((root) => {
        const el = root.querySelector('[data-testimonials-swiper]');
        const current = root.querySelector('[data-testimonials-current]');
        const multiple = el.querySelectorAll('.swiper-slide').length > 1;

        new Swiper(el, {
            modules: [Navigation, Keyboard, A11y, Autoplay],
            slidesPerView: 1,
            spaceBetween: 32,
            speed: 600,
            loop: multiple,
            allowTouchMove: multiple,
            autoHeight: false,
            keyboard: { enabled: true },
            a11y: { enabled: true },
            autoplay: multiple ? { delay: 7000, disableOnInteraction: false, pauseOnMouseEnter: true } : false,
            navigation: {
                prevEl: root.querySelector('[data-testimonials-prev]'),
                nextEl: root.querySelector('[data-testimonials-next]'),
            },
            on: {
                slideChange: (swiper) => {
                    if (current) current.textContent = String(swiper.realIndex + 1).padStart(2, '0');
                },
            },
        });
    });
}
