<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

// Default home
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Settings (Volt pages)
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

// ✅ Application routes (protected)
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\RequirementTypeController;

Route::middleware(['auth'])->group(function () {

    // Redirect dashboard to company list if needed
    //Route::get('/dashboard', [CompanyController::class, 'index'])->name('dashboard');

    // Companies CRUD
    Route::resource('companies', CompanyController::class);

    // Customers CRUD
    Route::resource('customers', CustomerController::class);

    // Industries CRUD
    Route::resource('industries', IndustryController::class);

    // Inquiries CRUD
    Route::resource('inquiries', InquiryController::class);

    // Requirement Types CRUD
    Route::resource('requirement-types', RequirementTypeController::class);

});

require __DIR__.'/auth.php';
