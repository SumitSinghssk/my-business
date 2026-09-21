<?php

use App\Helpers\Settings;
use App\Models\Seo;
use App\Services\ImageProcessor;
use App\Support\Robots;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

// robots.txt lives in storage/app: keep the real file intact while the tests run.
beforeEach(function () {
    $this->robotsBackup = File::exists(Robots::path()) ? File::get(Robots::path()) : null;
});

afterEach(function () {
    $this->robotsBackup === null ? File::delete(Robots::path()) : File::put(Robots::path(), $this->robotsBackup);
});

test('robots.txt is served with a sitemap line for the current domain', function () {
    Robots::save("User-agent: *\nDisallow: /admin/\nSitemap: http://localhost/old/sitemap.xml\n");

    $response = $this->get('/robots.txt')->assertOk()->assertHeader('Content-Type', 'text/plain; charset=UTF-8');

    expect($response->getContent())
        ->toContain('Disallow: /admin/')
        ->toContain('Sitemap: '.route('sitemap'))
        ->not->toContain('/old/sitemap.xml');
});

test('public pages send security headers', function () {
    $this->get('/')
        ->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertHeader('Permissions-Policy')
        ->assertHeaderMissing('X-XSS-Protection');
});

test('listing pages past the last page return 404', function () {
    $this->get('/insights?page=50')->assertNotFound();
    $this->get('/work?page=50')->assertNotFound();
    $this->get('/insights')->assertOk();
});

test('404 pages are noindex and have no canonical', function () {
    $html = $this->get('/definitely-missing-page')->assertNotFound()->getContent();

    expect($html)->toContain('content="noindex, follow"')->not->toContain('rel="canonical"');
});

test('{site_name} in SEO titles is replaced with the site name', function () {
    Seo::create(['page' => 'About', 'slug' => 'about', 'meta_title' => 'About Us | {site_name}', 'index' => true]);

    $html = $this->get('/about')->assertOk()->getContent();

    expect($html)->toContain('<title>About Us | '.e(Settings::appName()).'</title>')->not->toContain('{site_name}');
});

test('the canonical host middleware redirects to APP_URL in production only', function () {
    config(['app.url' => 'https://www.example.com']);

    $this->get('http://example.com/about?x=1')->assertOk(); // local: no redirect

    app()->detectEnvironment(fn () => 'production');
    $this->get('http://example.com/about?x=1')->assertRedirect('https://www.example.com/about?x=1')->assertStatus(301);
    $this->get('http://example.com/up')->assertOk();
});

test('stored images get a 640px variant used in srcset and removed with the image', function () {
    Storage::fake('public');
    $source = tempnam(sys_get_temp_dir(), 'img').'.png';
    imagepng(imagecreatetruecolor(1800, 1100), $source);

    $processor = app(ImageProcessor::class);
    $path = $processor->store($source, 'blog');
    $variant = ImageProcessor::variantPath($path, 640);

    Storage::disk('public')->assertExists([$path, $variant]);
    expect(getimagesizefromstring(Storage::disk('public')->get($variant))[0])->toBe(640)
        ->and(ImageProcessor::srcset($path, 'blog'))->toContain('640w')->toContain('1600w');

    $processor->delete($path);
    Storage::disk('public')->assertMissing([$path, $variant]);
    @unlink($source);
});
