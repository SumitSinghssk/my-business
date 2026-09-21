import './bootstrap';

import Alpine from 'alpinejs';
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

window.Alpine = Alpine;

/**
 * Image upload with a fixed-ratio cropper (see <x-admin.image-upload>).
 * The original file is uploaded together with the crop box; the server
 * crops/resizes to the exact preset size (config/images.php).
 */
Alpine.data('imageUpload', (config) => ({
    preview: config.current || '',
    hasFile: false,
    removed: false,
    cropping: false,
    warning: '',
    error: '',
    crop: { x: '', y: '', width: '', height: '' },
    cropper: null,
    sourceUrl: null,

    pick() {
        this.$refs.input.click();
    },

    onFileChange(event) {
        const file = event.target.files[0];
        this.error = '';
        this.warning = '';

        if (!file) {
            return;
        }

        if (!file.type.startsWith('image/')) {
            this.reset('Please choose an image file (JPG, PNG, WebP or GIF).');
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            this.reset('This image is larger than 5 MB. Please choose a smaller file.');
            return;
        }

        this.sourceUrl = URL.createObjectURL(file);
        const probe = new Image();

        probe.onload = () => {
            const { naturalWidth: w, naturalHeight: h } = probe;

            if (w < config.minWidth || h < config.minHeight) {
                this.reset(
                    `This image is ${w} × ${h} px. It must be at least ${config.minWidth} × ${config.minHeight} px (recommended ${config.width} × ${config.height} px).`,
                );
                return;
            }

            if (w < config.width || h < config.height) {
                this.warning = `This image is ${w} × ${h} px, smaller than the recommended ${config.width} × ${config.height} px, so it may look slightly soft.`;
            }

            this.openCropper();
        };

        probe.onerror = () => this.reset('This file could not be read as an image.');
        probe.src = this.sourceUrl;
    },

    openCropper() {
        this.cropping = true;

        this.$nextTick(() => {
            this.$refs.cropImage.src = this.sourceUrl;
            this.cropper?.destroy();
            this.cropper = new Cropper(this.$refs.cropImage, {
                aspectRatio: config.width / config.height,
                viewMode: 1,
                autoCropArea: 1,
                dragMode: 'move',
                background: false,
                responsive: true,
                zoomOnWheel: true,
                checkOrientation: true,
            });
        });
    },

    applyCrop() {
        if (!this.cropper) {
            return;
        }

        const data = this.cropper.getData(true);
        this.crop = { x: data.x, y: data.y, width: data.width, height: data.height };
        this.preview = this.cropper.getCroppedCanvas({ width: Math.min(config.width, 1200) }).toDataURL('image/jpeg', 0.85);
        this.hasFile = true;
        this.removed = false;
        this.closeCropper();
    },

    cancelCrop() {
        this.closeCropper();

        // Nothing applied yet for this file: forget it.
        if (!this.hasFile) {
            this.reset();
        }
    },

    recrop() {
        if (this.sourceUrl) {
            this.openCropper();
        }
    },

    closeCropper() {
        this.cropping = false;
        this.cropper?.destroy();
        this.cropper = null;
    },

    remove() {
        this.reset();
        this.preview = '';
        this.removed = true;
    },

    reset(error = '') {
        this.$refs.input.value = '';
        this.hasFile = false;
        this.crop = { x: '', y: '', width: '', height: '' };
        this.preview = this.removed ? '' : config.current || '';
        this.error = error;
        if (error) {
            this.warning = '';
        }
    },
}));

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
