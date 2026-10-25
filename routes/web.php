<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/', '/'.config('d51.default_locale'));

Route::get('sitemap.xml', SitemapController::class)->name('sitemap');

Route::prefix('{locale}')
    ->where(['locale' => implode('|', config('d51.locales'))])
    ->middleware('locale')
    ->group(function () {
        Route::get('/', [PageController::class, 'home'])->name('home');
        Route::get('about', [PageController::class, 'about'])->name('about');
        Route::get('privacy', [PageController::class, 'privacy'])->name('privacy');

        Route::get('services', [ServiceController::class, 'index'])->name('services.index');
        Route::get('services/{service}', [ServiceController::class, 'show'])->name('services.show');

        Route::get('projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

        Route::get('contact', [InquiryController::class, 'create'])->name('contact');
        Route::post('contact', [InquiryController::class, 'store'])
            ->middleware('throttle:inquiries')
            ->name('contact.store');
        Route::get('contact/thanks', [InquiryController::class, 'thanks'])->name('contact.thanks');
    });

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'destroy'])->name('logout');

        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::resource('services', Admin\ServiceController::class)->except('show');
        Route::resource('projects', Admin\ProjectController::class)->except('show');
        Route::post('projects/{project}/images', [Admin\ProjectImageController::class, 'store'])->name('projects.images.store');
        Route::put('projects/{project}/images/{image}', [Admin\ProjectImageController::class, 'update'])->scopeBindings()->name('projects.images.update');
        Route::delete('projects/{project}/images/{image}', [Admin\ProjectImageController::class, 'destroy'])->scopeBindings()->name('projects.images.destroy');

        Route::get('inquiries', [Admin\InquiryController::class, 'index'])->name('inquiries.index');
        Route::get('inquiries/{inquiry}', [Admin\InquiryController::class, 'show'])->name('inquiries.show');
        Route::patch('inquiries/{inquiry}', [Admin\InquiryController::class, 'update'])->name('inquiries.update');
        Route::post('inquiries/{inquiry}/resend', [Admin\InquiryController::class, 'resend'])->name('inquiries.resend');

        Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
    });
});
