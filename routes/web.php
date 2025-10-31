<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\RequirementTypeController;
use App\Http\Controllers\UserController;

// ✅ Default home and dashboard (both handled by controller)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ✅ Settings (Volt pages)
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// ✅ Application CRUD routes (protected)
Route::middleware(['auth'])->group(function () {
    Route::middleware(['auth', 'admin.only'])->group(function () {
        Route::resource('users', UserController::class);
    });
    Route::resource('companies', CompanyController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('industries', IndustryController::class);
    Route::resource('inquiries', InquiryController::class);
    Route::resource('requirement-types', RequirementTypeController::class);

    // AJAX endpoints
    Route::post('/ajax/companies', [\App\Http\Controllers\CompanyController::class, 'ajaxStore'])
        ->name('companies.ajaxStore');

    Route::post('/ajax/customers', [\App\Http\Controllers\CustomerController::class, 'ajaxStore'])
        ->name('customers.ajaxStore');
});

require __DIR__.'/auth.php';
