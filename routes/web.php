<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\LeadImportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicLeadController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Public lead form - no login required
Route::get('/enquiry', [PublicLeadController::class, 'show'])->name('public.lead-form');
Route::post('/enquiry', [PublicLeadController::class, 'store'])->name('public.lead-form.store');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Leads
    Route::get('/leads/pipeline', [LeadController::class, 'pipeline'])->name('leads.pipeline');
    Route::get('/leads/quick-create', [LeadController::class, 'quickCreate'])->name('leads.quick-create');
    Route::post('/leads/quick-store', [LeadController::class, 'quickStore'])->name('leads.quick-store');
    Route::patch('/leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.update-status');
    Route::post('/leads/{lead}/notes', [LeadController::class, 'addNote'])->name('leads.add-note');
    Route::post('/leads/{lead}/communications', [LeadController::class, 'addCommunication'])->name('leads.add-communication');
    Route::get('/leads/import', [LeadImportController::class, 'show'])->name('leads.import');
    Route::post('/leads/import/preview', [LeadImportController::class, 'preview'])->name('leads.import.preview');
    Route::post('/leads/import/process', [LeadImportController::class, 'import'])->name('leads.import.process');
    Route::get('/leads/import/template', [LeadImportController::class, 'downloadTemplate'])->name('leads.import.template');
    Route::resource('leads', LeadController::class);

    // Properties (CRUD restricted to admin in FormRequest)
    Route::resource('properties', PropertyController::class);

    // Cities
    Route::get('/cities', [CityController::class, 'index'])->name('cities.index');
    Route::post('/cities', [CityController::class, 'store'])->name('cities.store');
    Route::patch('/cities/{city}/toggle', [CityController::class, 'toggle'])->name('cities.toggle');
    Route::delete('/cities/{city}', [CityController::class, 'destroy'])->name('cities.destroy');
    Route::get('/cities/{city}/leads', [CityController::class, 'leads'])->name('cities.leads');

    // Follow-ups
    Route::post('/leads/{lead}/follow-ups', [FollowUpController::class, 'store'])->name('follow-ups.store');
    Route::patch('/follow-ups/{followUp}/complete', [FollowUpController::class, 'complete'])->name('follow-ups.complete');
    Route::patch('/follow-ups/{followUp}/cancel', [FollowUpController::class, 'cancel'])->name('follow-ups.cancel');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');

    // Owner/Super Admin Dashboard
    Route::middleware('role:super_admin')->prefix('owner')->group(function () {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
        Route::get('/daily-report', [OwnerDashboardController::class, 'dailyReport'])->name('owner.daily-report');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
