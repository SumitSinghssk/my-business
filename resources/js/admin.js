import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

const adminThemeKey = 'admin-theme';
const systemDarkMode = window.matchMedia('(prefers-color-scheme: dark)');

function getSavedTheme() {
    const theme = localStorage.getItem(adminThemeKey);

    return theme === 'dark' || theme === 'light' ? theme : systemDarkMode.matches ? 'dark' : 'light';
}

function applyTheme(theme) {
    const isDark = theme === 'dark';

    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.style.colorScheme = theme;
    window.dispatchEvent(new CustomEvent('admin-theme-changed', { detail: { theme, isDark } }));

    return isDark;
}

Alpine.store('notif', {
    open: false,

    toggle() {
        this.open = !this.open;
    },

    close() {
        this.open = false;
    },

    openSheet() {
        this.open = true;
    },
});

Alpine.store('theme', {
    theme: getSavedTheme(),
    isDark: false,

    init() {
        this.isDark = applyTheme(this.theme);

        systemDarkMode.addEventListener('change', (event) => {
            if (localStorage.getItem(adminThemeKey)) {
                return;
            }

            this.theme = event.matches ? 'dark' : 'light';
            this.isDark = applyTheme(this.theme);
        });
    },

    toggle() {
        this.theme = this.isDark ? 'light' : 'dark';
        localStorage.setItem(adminThemeKey, this.theme);
        this.isDark = applyTheme(this.theme);
    },
});

Alpine.start();
