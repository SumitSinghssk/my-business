<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebsiteController::class, 'index'])->name('home');
Route::get('/services', [WebsiteController::class, 'services'])->name('services');
Route::get('/about', [WebsiteController::class, 'about'])->name('about');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

Route::get('/insights', [BlogController::class, 'index'])->name('blog.index');
Route::get('/insights/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')
    ->name('page.show');
