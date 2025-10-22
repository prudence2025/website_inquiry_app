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

// ✅ Default home redirects to dashboard (requires login)
Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

// ✅ Dashboard routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Main dashboard view
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // AJAX endpoint to get live dashboard stats & chart data
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
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
    // Companies
    Route::resource('companies', CompanyController::class);

    // Customers
    Route::resource('customers', CustomerController::class);

    // Industries
    Route::resource('industries', IndustryController::class);

    // Inquiries
    Route::resource('inquiries', InquiryController::class);

    // Requirement Types
    Route::resource('requirement-types', RequirementTypeController::class);
});

require __DIR__.'/auth.php';
