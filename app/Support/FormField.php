<?php

namespace App\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use Illuminate\Support\ViewErrorBag;

/**
 * Naming, old input and error lookup shared by the admin form components
 * (resources/views/components/admin/form/*).
 */
class FormField
{
    /** Border, background, text and focus classes for a text-like control, by state. */
    public static function controlClasses(bool $invalid = false, bool $disabled = false): string
    {
        $base = 'w-full rounded-lg border text-sm shadow-xs transition focus:ring-3 focus:outline-none';

        if ($disabled) {
            return "$base cursor-not-allowed border-slate-200 bg-slate-50 text-slate-400 shadow-none dark:border-slate-800 dark:bg-slate-800/50";
        }

        $border = $invalid
            ? 'border-red-300 focus:border-red-400 focus:ring-red-500/15 dark:border-red-500/60'
            : 'border-slate-300/80 hover:border-slate-300 focus:border-blue-500 focus:ring-blue-500/15 dark:border-slate-700 dark:hover:border-slate-600';

        return "$base $border bg-white text-slate-900 placeholder:text-slate-400 dark:bg-slate-900 dark:text-white";
    }

    /** "faqs[0][question]" → "faqs.0.question", "roles[]" → "roles" (the key used by old() and $errors). */
    public static function key(?string $name): string
    {
        return trim(str_replace(['[]', '[', ']'], ['', '.', ''], (string) $name), '.');
    }

    /** A usable HTML id for a field name: "faqs[0][question]" → "faqs_0_question". */
    public static function id(?string $name): string
    {
        return str_replace('.', '_', self::key($name));
    }

    /**
     * The field's error: an explicit message, none when false, otherwise the first validation error
     * for the field (or any of its items, e.g. "category_ids.3" for "category_ids").
     */
    public static function error(?string $name, string|false|null $error, ?ViewErrorBag $errors): ?string
    {
        if ($error === false) {
            return null;
        }

        if (filled($error)) {
            return $error;
        }

        $key = self::key($name);

        if ($key === '' || ! $errors) {
            return null;
        }

        return $errors->first($key) ?: ($errors->first($key.'.*') ?: null);
    }

    /** Previous input for the field after a failed submit, else the given value. */
    public static function old(?string $name, mixed $value): mixed
    {
        $key = self::key($name);

        if ($key === '') {
            return $value;
        }

        $old = old($key, $value);

        // A list where one value is expected (tampered input, or a field the server rebuilt from an
        // array such as "tags") would break a text control: keep the value given by the view.
        return is_array($old) && ! is_array($value) ? $value : $old;
    }

    /**
     * Options as [{ value, label, description?, group?, depth? }]. Accepts [value => label],
     * a list of such arrays, or a list of objects/arrays with value/label keys.
     *
     * @return array<int, array{value: string, label: string, description?: string, group?: string, depth?: int}>
     */
    public static function options(iterable $options): array
    {
        $list = [];

        foreach ($options as $key => $option) {
            if (is_array($option) || is_object($option)) {
                $option = (array) $option;
                $list[] = array_filter([
                    'value' => (string) ($option['value'] ?? $key),
                    'label' => (string) ($option['label'] ?? $option['value'] ?? $key),
                    'description' => $option['description'] ?? null,
                    'group' => $option['group'] ?? null,
                    'depth' => $option['depth'] ?? null,
                    'dot' => $option['dot'] ?? null,
                ], fn ($v) => $v !== null);
            } else {
                $list[] = ['value' => (string) $key, 'label' => (string) $option];
            }
        }

        return $list;
    }

    /**
     * Parent/child records (e.g. blog categories with ->children) as multi-select options:
     * parents at depth 0, children indented under them.
     */
    public static function tree(iterable $parents, string $label = 'name', string $children = 'children'): array
    {
        $options = [];

        foreach ($parents as $parent) {
            $options[] = ['value' => (string) $parent->getKey(), 'label' => (string) $parent->{$label}, 'depth' => 0];

            foreach ($parent->{$children} ?? [] as $child) {
                $options[] = ['value' => (string) $child->getKey(), 'label' => (string) $child->{$label}, 'depth' => 1];
            }
        }

        return $options;
    }

    /** Selected values as strings (multi-select). */
    public static function values(mixed $value): array
    {
        $value = $value instanceof Arrayable ? $value->toArray() : Arr::wrap($value ?? []);

        return array_values(array_map('strval', $value));
    }
}
