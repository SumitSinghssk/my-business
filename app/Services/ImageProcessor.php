<?php

namespace App\Services;

use App\Support\ImagePreset;
use GdImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Crops and resizes uploaded images to the exact size of a preset
 * (config/images.php) and stores them on the public disk.
 *
 * Every stored image therefore has identical dimensions per preset, so the
 * website can rely on one aspect ratio everywhere it is shown.
 */
class ImageProcessor
{
    /**
     * Process an upload (or an existing file path) and return the stored path.
     *
     * @param  UploadedFile|string  $source  Upload, or an absolute file path.
     * @param  array|null  $crop  Crop box in source pixels: x, y, width, height (from the admin cropper).
     * @param  string|null  $replace  Existing stored path to delete after a successful save.
     */
    public function store(UploadedFile|string $source, string $preset, ?array $crop = null, ?string $replace = null): string
    {
        $preset = ImagePreset::get($preset);
        $realPath = $source instanceof UploadedFile ? $source->getRealPath() : $source;

        $image = $this->load($realPath);
        $output = $this->fit($image, $preset, $crop);

        $extension = config('images.format') === 'jpg' ? 'jpg' : 'webp';
        $path = trim($preset->directory, '/').'/'.Str::lower(Str::random(32)).'.'.$extension;

        $saved = Storage::disk('public')->put($path, $this->encode($output, $extension));

        if ($saved) {
            $this->storeVariants($output, $path, $preset->variants);
        }

        imagedestroy($image);
        imagedestroy($output);

        // Never delete the previous image (or return a path) unless the new file was written.
        if (! $saved) {
            throw new RuntimeException("Could not save the processed image to [{$path}].");
        }

        if ($replace && $replace !== $path) {
            $this->delete($replace);
        }

        return $path;
    }

    /**
     * Save smaller widths of an already fitted image next to it (e.g. blogs/abc-640.webp).
     *
     * @param  array<int, int>  $widths
     */
    public function storeVariants(GdImage $fitted, string $path, array $widths): void
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        foreach ($widths as $width) {
            $height = (int) round(imagesy($fitted) * $width / imagesx($fitted));
            $small = imagecreatetruecolor($width, $height);
            imagealphablending($small, false);
            imagesavealpha($small, true);
            imagecopyresampled($small, $fitted, 0, 0, 0, 0, $width, $height, imagesx($fitted), imagesy($fitted));

            Storage::disk('public')->put(self::variantPath($path, $width), $this->encode($small, $extension));
            imagedestroy($small);
        }
    }

    /** Create any missing variants for an image that is already stored (used by images:normalize). */
    public function ensureVariants(string $path, string $preset): int
    {
        $preset = ImagePreset::get($preset);
        $disk = Storage::disk('public');
        $missing = array_filter($preset->variants, fn ($w) => ! $disk->exists(self::variantPath($path, $w)));

        if (! $missing || ! $disk->exists($path)) {
            return 0;
        }

        $image = $this->load($disk->path($path));
        $this->storeVariants($image, $path, array_values($missing));
        imagedestroy($image);

        return count($missing);
    }

    /** Delete a stored image together with its smaller variants. */
    public function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        $widths = collect(config('images.presets'))->pluck('variants')->flatten()->filter()->unique();

        Storage::disk('public')->delete([$path, ...$widths->map(fn ($w) => self::variantPath($path, (int) $w))->all()]);
    }

    /** blogs/abc.webp → blogs/abc-640.webp */
    public static function variantPath(string $path, int $width): string
    {
        $info = pathinfo($path);

        return ($info['dirname'] !== '.' ? $info['dirname'].'/' : '').$info['filename'].'-'.$width.'.'.($info['extension'] ?? 'webp');
    }

    /**
     * srcset for a stored image: its existing smaller variants plus the full size, or null when there are none.
     */
    public static function srcset(?string $path, string $preset): ?string
    {
        if (! $path) {
            return null;
        }

        $preset = ImagePreset::get($preset);
        $disk = Storage::disk('public');
        $sources = [];

        foreach ($preset->variants as $width) {
            if ($disk->exists($variant = self::variantPath($path, $width))) {
                $sources[] = asset('storage/'.$variant).' '.$width.'w';
            }
        }

        return $sources ? implode(', ', [...$sources, asset('storage/'.$path).' '.$preset->width.'w']) : null;
    }

    /**
     * Crop to the preset's ratio (using the admin's crop box when given,
     * otherwise a centred crop) and resize to the exact preset size.
     */
    public function fit(GdImage $image, ImagePreset $preset, ?array $crop = null): GdImage
    {
        $srcW = imagesx($image);
        $srcH = imagesy($image);
        $targetRatio = $preset->aspectRatio();

        [$x, $y, $w, $h] = $this->cropBox($srcW, $srcH, $targetRatio, $crop);

        $output = imagecreatetruecolor($preset->width, $preset->height);
        imagealphablending($output, false);
        imagesavealpha($output, true);
        imagefill($output, 0, 0, imagecolorallocatealpha($output, 255, 255, 255, 127));

        imagecopyresampled($output, $image, 0, 0, $x, $y, $preset->width, $preset->height, $w, $h);

        return $output;
    }

    /**
     * Resolve a crop box that is inside the image and exactly the target ratio.
     *
     * @return array{0: int, 1: int, 2: int, 3: int}
     */
    private function cropBox(int $srcW, int $srcH, float $ratio, ?array $crop): array
    {
        if ($crop && ($crop['width'] ?? 0) > 0 && ($crop['height'] ?? 0) > 0) {
            $w = min((float) $crop['width'], $srcW);
            $h = min((float) $crop['height'], $srcH);

            // Snap the admin's box to the exact ratio (guards against rounding / tampering).
            if ($w / $h > $ratio) {
                $w = $h * $ratio;
            } else {
                $h = $w / $ratio;
            }

            $x = max(0, min((float) ($crop['x'] ?? 0), $srcW - $w));
            $y = max(0, min((float) ($crop['y'] ?? 0), $srcH - $h));

            return [(int) round($x), (int) round($y), (int) round($w), (int) round($h)];
        }

        // Centred crop.
        if ($srcW / $srcH > $ratio) {
            $h = $srcH;
            $w = $srcH * $ratio;
        } else {
            $w = $srcW;
            $h = $srcW / $ratio;
        }

        return [(int) round(($srcW - $w) / 2), (int) round(($srcH - $h) / 2), (int) round($w), (int) round($h)];
    }

    private function load(string $path): GdImage
    {
        $contents = @file_get_contents($path);
        $image = $contents !== false ? @imagecreatefromstring($contents) : false;

        if (! $image instanceof GdImage) {
            throw new RuntimeException('The uploaded file could not be read as an image.');
        }

        // Palette images (GIF/8-bit PNG) → true colour so resampling looks right.
        if (! imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }

        return $this->applyExifOrientation($image, $path);
    }

    /** Phone photos store rotation in EXIF; bake it in so crops match what the admin saw. */
    private function applyExifOrientation(GdImage $image, string $path): GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $orientation = @exif_read_data($path)['Orientation'] ?? 1;

        return match ((int) $orientation) {
            3 => imagerotate($image, 180, 0),
            6 => imagerotate($image, -90, 0),
            8 => imagerotate($image, 90, 0),
            default => $image,
        };
    }

    private function encode(GdImage $image, string $extension): string
    {
        ob_start();

        $extension === 'jpg'
            ? imagejpeg($image, null, (int) config('images.quality', 82))
            : imagewebp($image, null, (int) config('images.quality', 82));

        return (string) ob_get_clean();
    }
}
