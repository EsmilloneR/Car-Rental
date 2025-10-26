<?php

use App\Http\Controllers\AnalyticController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Livewire\Browse\ListCars;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('homepage');
})->name('home');

Volt::route('/about', 'pages.about')->name('about');
Volt::route('/contact', 'pages.contact')->name('contact');

Route::get('/list-cars', ListCars::class)->name('list-cars');
Volt::route('/vehicles/{id}/details', 'vehicle.details')->name('vehicle.details');



Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
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



    Volt::route('profile/my-car', 'profile.mycar')->name('profile.mycar');

    Volt::route('/vehicles/{id}/payment', 'booking.payment')->name('vehicle.payment');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payments/{id}/receipt', [InvoiceController::class, 'receipt'])
    ->name('payments.receipt');

    // PDF
    Route::get('/report/download', [ReportController::class, 'downloadReport'])
    ->name('reports.download');

    Route::get('/analytics/download/{from}/{to}', [AnalyticController::class, 'downloadAnalytics'])->name('analytics.download');




    Volt::route('thankyou', 'thankyou')->name('thankyou');
});
