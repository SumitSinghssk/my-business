<?php

namespace App\Console\Commands;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Page;
use App\Models\Project;
use App\Models\Seo;
use App\Models\Service;
use App\Models\User;
use App\Services\ImageProcessor;
use App\Support\ImagePreset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Brings already-uploaded images in line with config/images.php by
 * centre-cropping and resizing any image that is not the exact preset size.
 */
class NormalizeImages extends Command
{
    protected $signature = 'images:normalize {--dry-run : List what would change without writing anything}';

    protected $description = 'Crop/resize (and convert to the configured format) stored images (blogs, pages, categories, services, projects, SEO, avatars) to their preset size';

    /** model class => [column, preset] */
    private const TARGETS = [
        Blog::class => ['featured_image', 'blog'],
        Page::class => ['featured_image', 'page'],
        BlogCategory::class => ['image', 'category'],
        Seo::class => ['og_image', 'og'],
        User::class => ['avatar', 'avatar'],
        Service::class => ['featured_image', 'service'],
        Project::class => ['featured_image', 'project'],
    ];

    public function handle(ImageProcessor $processor): int
    {
        $disk = Storage::disk('public');
        $dryRun = (bool) $this->option('dry-run');
        $totals = ['ok' => 0, 'fixed' => 0, 'missing' => 0, 'failed' => 0, 'variants' => 0];

        foreach (self::TARGETS as $model => [$column, $presetKey]) {
            $preset = ImagePreset::get($presetKey);
            $query = $model::query()->whereNotNull($column)->where($column, '!=', '');

            if (method_exists($model, 'bootSoftDeletes')) {
                $query->withTrashed();
            }

            foreach ($query->cursor() as $record) {
                $path = $record->{$column};

                if (! $disk->exists($path)) {
                    $this->warn("  missing  {$preset->label} #{$record->getKey()}: {$path}");
                    $totals['missing']++;

                    continue;
                }

                $size = @getimagesize($disk->path($path));
                // Images in another format than config('images.format') (e.g. seeded JPGs) are re-encoded too.
                $format = config('images.format') === 'jpg' ? ['jpg', 'jpeg'] : ['webp'];
                $rightFormat = in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $format, true);

                if ($size && $rightFormat && $size[0] === $preset->width && $size[1] === $preset->height) {
                    $totals['ok']++;

                    // Backfill the smaller srcset copies (e.g. -640) for images uploaded before they existed.
                    if (! $dryRun && $preset->variants) {
                        $totals['variants'] += $processor->ensureVariants($path, $presetKey);
                    }

                    continue;
                }

                $current = $size ? "{$size[0]}×{$size[1]}" : 'unreadable';
                $action = $size && $size[0] === $preset->width && $size[1] === $preset->height ? 'convert' : 'resize ';
                $this->line("  {$action}  {$preset->label} #{$record->getKey()}: {$current} → {$preset->width}×{$preset->height}");

                if ($dryRun) {
                    $totals['fixed']++;

                    continue;
                }

                try {
                    $newPath = $processor->store($disk->path($path), $presetKey, null, $path);
                    $record->forceFill([$column => $newPath])->saveQuietly();
                    $totals['fixed']++;
                } catch (\Throwable $e) {
                    $this->error("  failed   {$path}: {$e->getMessage()}");
                    $totals['failed']++;
                }
            }
        }

        $verb = $dryRun ? 'would be resized' : 'resized';
        $this->info("Done: {$totals['ok']} already correct, {$totals['fixed']} {$verb}, {$totals['missing']} missing files, {$totals['failed']} failed, {$totals['variants']} srcset variants created.");

        return $totals['failed'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}
