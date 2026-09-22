<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * Read-only access to an image preset from config/images.php.
 */
final class ImagePreset
{
    private function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly int $width,
        public readonly int $height,
        public readonly string $ratio,
        public readonly string $aspect,
        public readonly string $directory,
        /** @var array<int, int> Smaller widths saved next to the image for srcset. */
        public readonly array $variants = [],
    ) {}

    public static function get(string $key): self
    {
        $preset = config("images.presets.{$key}");

        if (! $preset) {
            throw new InvalidArgumentException("Unknown image preset [{$key}].");
        }

        return new self(
            $key,
            $preset['label'],
            (int) $preset['width'],
            (int) $preset['height'],
            $preset['ratio'],
            $preset['aspect'],
            $preset['directory'],
            array_values(array_filter(array_map('intval', $preset['variants'] ?? []), fn ($w) => $w > 0 && $w < (int) $preset['width'])),
        );
    }

    /** Width ÷ height, used by the admin cropper. */
    public function aspectRatio(): float
    {
        return $this->width / $this->height;
    }

    /** Uploads must be at least half the target size so they are not blown up blurry. */
    public function minWidth(): int
    {
        return (int) ceil($this->width / 2);
    }

    public function minHeight(): int
    {
        return (int) ceil($this->height / 2);
    }

    /** Human readable hint, e.g. "16:10 · 1600 × 1000 px". */
    public function hint(): string
    {
        return "{$this->ratio} · {$this->width} × {$this->height} px";
    }

    /**
     * Validation rules for the upload field plus its crop coordinates.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(string $field): array
    {
        return [
            $field => [
                'nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120',
                "dimensions:min_width={$this->minWidth()},min_height={$this->minHeight()},max_width=8000,max_height=8000",
            ],
            "{$field}_crop" => ['nullable', 'array'],
            "{$field}_crop.*" => ['nullable', 'numeric', 'min:0'],
        ];
    }

    /** @return array<string, string> */
    public function messages(string $field): array
    {
        return [
            "{$field}.dimensions" => "The {$this->label} must be between {$this->minWidth()} × {$this->minHeight()} px and 8000 × 8000 px (recommended {$this->width} × {$this->height} px).",
            "{$field}.max" => "The {$this->label} may not be larger than 5 MB.",
        ];
    }
}
