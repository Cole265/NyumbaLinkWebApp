<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Password reset
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Email verification (verify works without auth via signed URL)
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->middleware('signed')->name('verification.verify');
Route::middleware('web.auth')->group(function () {
    Route::get('/email/verify', [VerifyEmailController::class, 'notice'])->name('verification.notice');
    Route::post('/email/verification-notification', [VerifyEmailController::class, 'send'])->middleware('throttle:6,1')->name('verification.send');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/properties', function () {
    return view('Properties.index');
});

Route::get('/properties/{id}', function ($id) {
    return view('Properties.show');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/pricing', function () {
    return view('pricing');
});

Route::get('/contact', function () {
    return view('contact');
})->name('contact.show');

Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Protected dashboard routes (require auth cookie and role)
Route::middleware(['web.auth:landlord'])->group(function () {
    Route::get('/landlord/dashboard', function () {
        return view('landlord.dashboard');
    })->name('landlord.dashboard');
    Route::get('/landlord/properties/create', function () {
        return view('landlord.properties.create');
    })->name('landlord.properties.create');
    Route::get('/landlord/properties/{id}/edit', function ($id) {
        return view('landlord.properties.edit', ['id' => $id]);
    })->name('landlord.properties.edit');
});

Route::middleware(['web.auth:tenant'])->group(function () {
    Route::get('/tenant/dashboard', function () {
        return view('tenant.dashboard');
    })->name('tenant.dashboard');
});

Route::middleware(['web.auth:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});