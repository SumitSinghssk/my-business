<?php

use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Admin\Auth\ProfileController;
use App\Http\Controllers\Admin\Blog\BlogCategoryController;
use App\Http\Controllers\Admin\Blog\BlogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\Setting\ActivityLogController;
use App\Http\Controllers\Admin\Setting\BasicSettingController;
use App\Http\Controllers\Admin\Setting\ClearCacheController;
use App\Http\Controllers\Admin\Setting\DbDownloadController;
use App\Http\Controllers\Admin\Setting\LogController;
use App\Http\Controllers\Admin\Setting\RobotsController;
use App\Http\Controllers\Admin\Setting\ScriptSettingController;
use App\Http\Controllers\Admin\Setting\SettingController;
use App\Http\Controllers\Admin\Setting\SitemapController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['scalar.query', 'log.admin.activity'])->prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:web')->group(function () {
        Route::get('login', [AdminAuthController::class, 'index'])->name('login');
        Route::post('login', [AdminAuthController::class, 'store'])->name('login.store');
    });

    Route::middleware(['auth:web', 'active'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('update-password', [ProfileController::class, 'updatePassword'])->name('update-password');

        Route::post('images', [ImageController::class, 'store'])->name('images.store');

        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::resource('seo', SeoController::class)->except('show');

        Route::prefix('settings')->name('settings.')->group(function () {

            Route::get('/', SettingController::class)->name('index');
            Route::post('/basic', BasicSettingController::class)->name('basic.update');
            Route::post('/scripts', ScriptSettingController::class)->name('scripts.update');
            Route::post('/clear-cache', ClearCacheController::class)->name('clear-cache');
            Route::post('/download-db', DbDownloadController::class)->name('download-db');

            Route::prefix('sitemap')->name('sitemap.')->group(function () {
                Route::post('generate', [SitemapController::class, 'generate'])->name('generate');
                Route::get('download', [SitemapController::class, 'download'])->name('download');
                Route::post('upload', [SitemapController::class, 'upload'])->name('upload');
            });

            Route::prefix('logs')->name('logs.')->group(function () {
                Route::get('/', [LogController::class, 'index'])->name('index');
                Route::get('/show', [LogController::class, 'show'])->name('show');
                Route::delete('/destroy', [LogController::class, 'destroy'])->name('destroy');
                Route::delete('/destroy-all', [LogController::class, 'destroyAll'])->name('destroy-all');
            });

            Route::post('robots', RobotsController::class)->name('robots.update');
        });

        Route::prefix('activity-logs')->name('activity-logs.')->group(function () {
            Route::get('/', [ActivityLogController::class, 'index'])->name('index');
            Route::delete('/clear', [ActivityLogController::class, 'clear'])->name('clear');
            Route::get('/{activityLog}', [ActivityLogController::class, 'show'])->name('show');
        });

        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RolePermissionController::class, 'index'])->name('index');

            Route::post('/', [RolePermissionController::class, 'storeRole'])->name('store');
            Route::delete('/{role}', [RolePermissionController::class, 'destroyRole'])->name('destroy');

            Route::post('/{role}/toggle-permission', [RolePermissionController::class, 'togglePermission'])->name('toggle-permission');
        });

        Route::prefix('permissions')->name('permissions.')->group(function () {
            Route::post('/', [RolePermissionController::class, 'storePermission'])->name('store');
            Route::delete('/{permission}', [RolePermissionController::class, 'destroyPermission'])->name('destroy');
        });

        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::resource('users', UserController::class)->except(['show']);

        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/list', [NotificationController::class, 'list'])->name('list');
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('markAllRead');
            Route::post('/{id}/mark-read', [NotificationController::class, 'markRead'])->name('markRead');
            Route::get('/{id}/view', [NotificationController::class, 'view'])->name('view');
        });

        Route::patch('/blog-categories/{blogCategory}/toggle-status', [BlogCategoryController::class, 'toggleStatus'])->name('blog-categories.toggle-status');
        Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);

        Route::patch('blogs/{blog}/toggle-status', [BlogController::class, 'toggleStatus'])->name('blogs.toggle-status');
        Route::resource('blogs', BlogController::class)->except(['show']);

        Route::patch('pages/{page}/toggle-status', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');
        Route::resource('pages', PageController::class)->except(['show']);

        Route::patch('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
        Route::resource('services', ServiceController::class)->except(['show']);

        Route::patch('projects/{project}/toggle-status', [ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
        Route::resource('projects', ProjectController::class)->except(['show']);

        Route::get('enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
        Route::patch('enquiries/{enquiry}/seen', [EnquiryController::class, 'markSeen'])->name('enquiries.seen');
        Route::delete('enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');
    });
});
