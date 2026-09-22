/**
 * Admin form controls behind the Blade components in resources/views/components/admin/form/
 * (select, multi-select, date-picker). Each keeps its value in hidden inputs, so forms submit
 * normally, and exposes it through x-modelable="value" so a parent can use x-model on it.
 * Menus are teleported to #admin-portal and positioned against the viewport, so cards or tables
 * with overflow hidden never clip them.
 */

const MENU_MAX_HEIGHT = 280;

/** Shared open/close + positioning for anything with a trigger button and a floating panel. */
const popover = () => ({
    open: false,
    menuStyle: '',

    place() {
        const rect = this.$refs.trigger.getBoundingClientRect();
        const below = window.innerHeight - rect.bottom;
        const height = this.$refs.panel?.offsetHeight || MENU_MAX_HEIGHT;
        const flipUp = below < height + 8 && rect.top > below;
        // Panels with their own content width (the calendar) keep it instead of stretching to the trigger.
        const width = Math.min(this.panelWidth ?? Math.max(rect.width, this.minWidth ?? 0), window.innerWidth - 16);
        const left = Math.max(8, Math.min(rect.left, window.innerWidth - width - 8));

        this.menuStyle = flipUp
            ? `position:fixed;left:${left}px;width:${width}px;bottom:${window.innerHeight - rect.top + 4}px`
            : `position:fixed;left:${left}px;width:${width}px;top:${rect.bottom + 4}px`;
    },

    show() {
        if (this.disabled || this.open) return;
        this.open = true;
        this.place();
        this._reposition = () => this.place();
        window.addEventListener('scroll', this._reposition, true);
        window.addEventListener('resize', this._reposition);
        this.$nextTick(() => {
            this.place();
            this.opened?.();
        });
    },

    hide(focusTrigger = true) {
        if (!this.open) return;
        this.open = false;
        window.removeEventListener('scroll', this._reposition, true);
        window.removeEventListener('resize', this._reposition);
        this.closed?.();
        if (focusTrigger) (this.$refs[this.focusRef ?? 'trigger'] ?? this.$refs.trigger).focus();
    },

    /** Clicks outside both the trigger and the (teleported) panel close it. */
    outside(event) {
        if (!this.open) return;
        if (this.$root.contains(event.target) || this.$refs.panel?.contains(event.target)) return;
        this.hide(false);
    },

    /** Let a parent listen with x-on:change (e.g. filter forms that submit on change). */
    changed() {
        this.$nextTick(() => this.$root.dispatchEvent(new Event('change', { bubbles: true })));
    },
});

/** Single choice. options: [{ value, label, description?, group? }] */
export const adminSelect = ({ id = null, options = [], value = '', searchable = false, disabled = false, minWidth = 0 }) => ({
    ...popover(),
    // Base for the ids of the trigger, listbox and options (unique per copy when used inside x-for).
    uid: id,

    init() {
        this.uid ??= this.$id('admin-select');
    },
    options,
    value: value === null ? '' : String(value),
    searchable,
    disabled,
    minWidth,
    query: '',
    active: -1,

    get selected() {
        return this.options.find((o) => o.value === this.value) ?? null;
    },

    get filtered() {
        const q = this.query.trim().toLowerCase();
        if (!q) return this.options;
        return this.options.filter((o) => o.label.toLowerCase().includes(q) || (o.description ?? '').toLowerCase().includes(q));
    },

    /** Group header shown before this option (only when the group changes). */
    groupBefore(index) {
        const option = this.filtered[index];
        return option.group && option.group !== this.filtered[index - 1]?.group ? option.group : '';
    },

    opened() {
        this.query = '';
        this.active = Math.max(
            0,
            this.filtered.findIndex((o) => o.value === this.value),
        );
        if (this.searchable) this.$refs.search.focus();
        this.scrollActive();
    },

    toggle() {
        this.open ? this.hide() : this.show();
    },

    choose(option) {
        if (!option) return;
        const changed = option.value !== this.value;
        this.value = option.value;
        this.hide();
        if (changed) this.changed();
    },

    move(step) {
        if (!this.open) return this.show();
        const count = this.filtered.length;
        if (!count) return;
        this.active = (this.active + step + count) % count;
        this.scrollActive();
    },

    scrollActive() {
        this.$nextTick(() => this.$refs.panel?.querySelector(`[data-index="${this.active}"]`)?.scrollIntoView({ block: 'nearest' }));
    },

    /** Keyboard on the trigger (and the search box): arrows, Enter/Space, Home/End, type-ahead. */
    key(event) {
        const keys = {
            ArrowDown: () => this.move(1),
            ArrowUp: () => this.move(-1),
            Home: () => this.open && ((this.active = 0), this.scrollActive()),
            End: () => this.open && ((this.active = this.filtered.length - 1), this.scrollActive()),
            Enter: () => (this.open ? this.choose(this.filtered[this.active]) : this.show()),
            Escape: () => this.hide(),
            // From the search box, hand focus back to the trigger so Tab continues from there.
            Tab: () => this.hide(this.searchable),
        };

        if (event.key === ' ' && !(this.searchable && this.open)) {
            event.preventDefault();
            return this.open ? this.choose(this.filtered[this.active]) : this.show();
        }

        if (keys[event.key]) {
            if (event.key !== 'Tab' && !(event.key === 'Escape' && !this.open)) event.preventDefault();
            return keys[event.key]();
        }

        // Type-ahead when there is no search box: jump to the next option starting with that letter.
        if (!this.searchable && event.key.length === 1 && /\S/.test(event.key)) {
            const start = this.open ? this.active + 1 : this.options.findIndex((o) => o.value === this.value) + 1;
            const list = this.filtered;
            for (let i = 0; i < list.length; i++) {
                const index = (start + i) % list.length;
                if (list[index].label.toLowerCase().startsWith(event.key.toLowerCase())) {
                    if (this.open) {
                        this.active = index;
                        this.scrollActive();
                    } else {
                        this.choose(list[index]);
                    }
                    break;
                }
            }
        }
    },
});

/** Several choices, shown as chips. options: [{ value, label, group?, depth? }] */
export const adminMultiSelect = ({ options = [], value = [], disabled = false, max = null }) => ({
    ...popover(),
    options,
    value: (value ?? []).map(String),
    disabled,
    max,
    focusRef: 'search',
    query: '',
    active: 0,

    get filtered() {
        const q = this.query.trim().toLowerCase();
        return q ? this.options.filter((o) => o.label.toLowerCase().includes(q)) : this.options;
    },

    closed() {
        this.query = '';
    },

    labelOf(value) {
        return this.options.find((o) => o.value === value)?.label ?? value;
    },

    isSelected(value) {
        return this.value.includes(value);
    },

    opened() {
        // Keep what was typed: typing in the box is what opens the menu.
        this.active = 0;
        this.$refs.search.focus();
    },

    toggleOption(option) {
        if (!option || this.disabled) return;
        if (this.isSelected(option.value)) {
            this.value = this.value.filter((v) => v !== option.value);
        } else if (this.max === null || this.value.length < this.max) {
            this.value = [...this.value, option.value];
        }
        this.changed();
    },

    remove(value) {
        if (this.disabled) return;
        this.value = this.value.filter((v) => v !== value);
        this.changed();
    },

    move(step) {
        const count = this.filtered.length;
        if (!count) return;
        this.active = (this.active + step + count) % count;
        this.$nextTick(() => this.$refs.panel?.querySelector(`[data-index="${this.active}"]`)?.scrollIntoView({ block: 'nearest' }));
    },

    key(event) {
        if (event.key === 'ArrowDown') (event.preventDefault(), this.open ? this.move(1) : this.show());
        else if (event.key === 'ArrowUp') (event.preventDefault(), this.move(-1));
        else if (event.key === 'Enter') (event.preventDefault(), this.open ? this.toggleOption(this.filtered[this.active]) : this.show());
        else if (event.key === 'Escape' && this.open) (event.preventDefault(), this.hide());
        else if (event.key === 'Tab') this.hide(false);
        else if (event.key === 'Backspace' && !this.query && this.value.length) this.remove(this.value[this.value.length - 1]);
    },
});

const pad = (n) => String(n).padStart(2, '0');
const ymd = (d) => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
const parseYmd = (s) => {
    const m = /^(\d{4})-(\d{2})-(\d{2})/.exec(s ?? '');
    return m ? new Date(+m[1], +m[2] - 1, +m[3]) : null;
};
const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

/**
 * Calendar date picker. The hidden input holds "YYYY-MM-DD", or "YYYY-MM-DDTHH:MM" with withTime.
 * mode "range" keeps { start, end } and fills two hidden inputs.
 */
export const adminDatePicker = ({ value = '', withTime = false, mode = 'single', min = '', max = '', disabled = false }) => ({
    ...popover(),
    mode,
    withTime,
    min,
    max,
    disabled,
    panelWidth: 300,
    value: mode === 'range' ? { start: value?.start || '', end: value?.end || '' } : value || '',
    hour: '09',
    minute: '00',
    view: new Date(),
    focusDay: null,
    hoverDay: '',
    weekdays: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
    hours: Array.from({ length: 24 }, (_, i) => pad(i)),
    minutes: Array.from({ length: 12 }, (_, i) => pad(i * 5)),

    init() {
        if (this.mode === 'single' && this.withTime && /T(\d{2}):(\d{2})/.test(this.value)) {
            [, this.hour, this.minute] = /T(\d{2}):(\d{2})/.exec(this.value);
            if (!this.minutes.includes(this.minute)) this.minutes = [...this.minutes, this.minute].sort();
        }
    },

    get anchor() {
        return this.mode === 'range' ? this.value.start || this.value.end : this.value;
    },

    get monthLabel() {
        return `${MONTHS[this.view.getMonth()]} ${this.view.getFullYear()}`;
    },

    /** 6 weeks starting on Monday, as { date: 'YYYY-MM-DD', day, outside } cells. */
    get cells() {
        const first = new Date(this.view.getFullYear(), this.view.getMonth(), 1);
        const start = new Date(first);
        start.setDate(1 - ((first.getDay() + 6) % 7));
        return Array.from({ length: 42 }, (_, i) => {
            const d = new Date(start);
            d.setDate(start.getDate() + i);
            return { date: ymd(d), day: d.getDate(), outside: d.getMonth() !== this.view.getMonth() };
        });
    },

    /** Text on the trigger, e.g. "22 Sep 2026, 10:30" or "1 Sep 2026 – 22 Sep 2026". */
    get display() {
        const fmt = (s) => {
            const d = parseYmd(s);
            return d ? `${d.getDate()} ${MONTHS[d.getMonth()].slice(0, 3)} ${d.getFullYear()}` : '';
        };
        if (this.mode === 'range') {
            const { start, end } = this.value;
            if (!start && !end) return '';
            return `${fmt(start) || '…'} – ${fmt(end) || '…'}`;
        }
        if (!this.value) return '';
        return this.withTime ? `${fmt(this.value)}, ${this.value.slice(11, 16)}` : fmt(this.value);
    },

    isDisabledDay(date) {
        // Always a boolean: Alpine treats an empty string as "set the attribute", which would disable every day.
        return Boolean((this.min && date < this.min) || (this.max && date > this.max));
    },

    isSelected(date) {
        if (this.mode === 'range') return date === this.value.start || date === this.value.end;
        return this.value.slice(0, 10) === date;
    },

    inRange(date) {
        if (this.mode !== 'range' || !this.value.start) return false;
        const end = this.value.end || this.hoverDay;
        return !!end && date > this.value.start && date < end;
    },

    isToday(date) {
        return date === ymd(new Date());
    },

    opened() {
        const d = parseYmd(this.anchor) ?? new Date();
        this.view = new Date(d.getFullYear(), d.getMonth(), 1);
        this.focusDay = ymd(d);
        this.$nextTick(() => this.focusCell());
    },

    focusCell() {
        this.$refs.panel?.querySelector(`[data-date="${this.focusDay}"]`)?.focus();
    },

    shiftMonth(step) {
        this.view = new Date(this.view.getFullYear(), this.view.getMonth() + step, 1);
    },

    /** Arrow keys move a day/week, PageUp/PageDown a month, Enter picks. */
    gridKey(event) {
        const steps = { ArrowLeft: -1, ArrowRight: 1, ArrowUp: -7, ArrowDown: 7 };
        const d = parseYmd(this.focusDay) ?? new Date();
        if (steps[event.key] !== undefined) d.setDate(d.getDate() + steps[event.key]);
        else if (event.key === 'PageUp') d.setMonth(d.getMonth() - 1);
        else if (event.key === 'PageDown') d.setMonth(d.getMonth() + 1);
        else if (event.key === 'Escape') return (event.preventDefault(), this.hide());
        else return;

        event.preventDefault();
        this.focusDay = ymd(d);
        if (d.getMonth() !== this.view.getMonth() || d.getFullYear() !== this.view.getFullYear()) {
            this.view = new Date(d.getFullYear(), d.getMonth(), 1);
        }
        this.$nextTick(() => this.focusCell());
    },

    pick(date) {
        if (this.isDisabledDay(date)) return;
        this.focusDay = date;

        if (this.mode === 'range') {
            const { start, end } = this.value;
            if (!start || end || date < start) {
                this.value = { start: date, end: '' };
                return;
            }
            this.value = { start, end: date };
            this.changed();
            return this.hide();
        }

        this.value = this.withTime ? `${date}T${this.hour}:${this.minute}` : date;
        this.changed();
        if (!this.withTime) this.hide();
    },

    setTime() {
        if (!this.value) return;
        this.value = `${this.value.slice(0, 10)}T${this.hour}:${this.minute}`;
        this.changed();
    },

    today() {
        const now = new Date();
        if (this.withTime && !this.value) {
            this.hour = pad(now.getHours());
            this.minute = pad(now.getMinutes() - (now.getMinutes() % 5));
        }
        this.view = new Date(now.getFullYear(), now.getMonth(), 1);
        this.pick(ymd(now));
    },

    clear() {
        this.value = this.mode === 'range' ? { start: '', end: '' } : '';
        this.changed();
        this.hide();
    },

    /** Quick ranges shown above the calendar in range mode. */
    presets: [
        { key: 'today', label: 'Today' },
        { key: '7', label: 'Last 7 days' },
        { key: '30', label: 'Last 30 days' },
        { key: 'month', label: 'This month' },
        { key: 'last-month', label: 'Last month' },
    ],

    applyPreset(key) {
        const now = new Date();
        const today = ymd(now);
        const range = {
            today: [today, today],
            7: [ymd(new Date(now.getFullYear(), now.getMonth(), now.getDate() - 6)), today],
            30: [ymd(new Date(now.getFullYear(), now.getMonth(), now.getDate() - 29)), today],
            month: [ymd(new Date(now.getFullYear(), now.getMonth(), 1)), today],
            'last-month': [ymd(new Date(now.getFullYear(), now.getMonth() - 1, 1)), ymd(new Date(now.getFullYear(), now.getMonth(), 0))],
        }[key];
        if (!range) return;
        this.value = { start: range[0], end: range[1] };
        this.changed();
        this.hide();
    },
});

export default function registerAdminForms(Alpine) {
    Alpine.data('adminSelect', adminSelect);
    Alpine.data('adminMultiSelect', adminMultiSelect);
    Alpine.data('adminDatePicker', adminDatePicker);
}
