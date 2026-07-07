<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ParticipantAuthController;
use App\Http\Controllers\SanghRegistrationController;
use Illuminate\Support\Facades\Route;

// Language switcher
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/sangh/register', [SanghRegistrationController::class, 'create'])->name('sangh.register');
Route::post('/sangh/register', [SanghRegistrationController::class, 'store'])->name('sangh.register.store');
Route::get('/sangh/status', [SanghRegistrationController::class, 'status'])->name('sangh.status');

// Participant (Sangh member) login
Route::prefix('my')->name('participant.')->group(function () {
    Route::get('/login', [ParticipantAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [ParticipantAuthController::class, 'sendOtp'])->name('login.post');
    Route::get('/verify', [ParticipantAuthController::class, 'showVerify'])->name('verify');
    Route::post('/verify', [ParticipantAuthController::class, 'verifyOtp'])->name('verify.post');
    Route::post('/logout', [ParticipantAuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ParticipantAuthController::class, 'profile'])->middleware('participant')->name('profile');
});

// Admin auth (no middleware)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [Admin\AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [Admin\AuthController::class, 'logout'])->name('logout');
});

// Admin protected routes
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Sangh CRUD
    Route::resource('sangh', Admin\SanghController::class);

    // Sangh participants
    Route::get('sangh/{sangh}/participants', [Admin\ParticipantController::class, 'index'])->name('sangh.participants');
    Route::post('sangh/{sangh}/participants', [Admin\ParticipantController::class, 'store'])->name('sangh.participants.store');
    Route::get('sangh/{sangh}/participants/lookup', [Admin\ParticipantController::class, 'lookup'])->name('sangh.participants.lookup');
    Route::post('sangh/{sangh}/participants/confirm', [Admin\ParticipantController::class, 'confirm'])->name('sangh.participants.confirm');
    Route::patch('sangh/{sangh}/participants/{participant}', [Admin\ParticipantController::class, 'updateStatus'])->name('sangh.participants.status');

    // Hawan participants
    Route::get('sangh/{sangh}/hawan', [Admin\HawanController::class, 'index'])->name('sangh.hawan');
    Route::post('sangh/{sangh}/hawan/assign', [Admin\HawanController::class, 'assign'])->name('sangh.hawan.assign');
    Route::post('sangh/{sangh}/hawan/remove', [Admin\HawanController::class, 'remove'])->name('sangh.hawan.remove');

    // Stoppages
    Route::get('sangh/{sangh}/stoppages', [Admin\StoppageController::class, 'index'])->name('sangh.stoppages');
    Route::post('sangh/{sangh}/stoppages', [Admin\StoppageController::class, 'store'])->name('sangh.stoppages.store');
    Route::put('sangh/{sangh}/stoppages/{stoppage}', [Admin\StoppageController::class, 'update'])->name('sangh.stoppages.update');
    Route::delete('sangh/{sangh}/stoppages/{stoppage}', [Admin\StoppageController::class, 'destroy'])->name('sangh.stoppages.destroy');
    Route::post('sangh/{sangh}/stoppages/{stoppage}/log', [Admin\StoppageController::class, 'logService'])->name('sangh.stoppages.log');

    // Volunteers
    Route::get('sangh/{sangh}/volunteers', [Admin\VolunteerController::class, 'index'])->name('sangh.volunteers');
    Route::post('sangh/{sangh}/volunteers', [Admin\VolunteerController::class, 'store'])->name('sangh.volunteers.store');
    Route::delete('sangh/{sangh}/volunteers/{volunteer}', [Admin\VolunteerController::class, 'destroy'])->name('sangh.volunteers.destroy');

    // Report
    Route::get('sangh/{sangh}/report', [Admin\ReportController::class, 'show'])->name('sangh.report');

    // Events
    Route::resource('events', Admin\EventController::class);

    // Sponsors
    Route::get('sponsors', [Admin\SponsorController::class, 'index'])->name('sponsors.index');
    Route::post('sponsors', [Admin\SponsorController::class, 'store'])->name('sponsors.store');
    Route::delete('sponsors/{sponsor}', [Admin\SponsorController::class, 'destroy'])->name('sponsors.destroy');

    // Family (Parivar)
    Route::resource('family', Admin\FamilyController::class);
    Route::get('family-suggest/middle-name', [Admin\FamilyController::class, 'middleNameSuggest'])->name('family.suggest.middle');
});
