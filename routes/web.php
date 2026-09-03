<?php

use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\Doctors\CreateDoctors;
use App\Livewire\Pages\Doctors\DetailsDoctors;
use App\Livewire\Pages\Doctors\EditDoctors;
use App\Livewire\Pages\Doctors\IndexDoctors;
use App\Livewire\Pages\Documents\IndexDocuments;
use App\Livewire\Pages\Guest\GuestDashboard;
use App\Livewire\Pages\Guest\Parameters\CreateParameters;
use App\Livewire\Pages\Guest\Parameters\EditParameter;
use App\Livewire\Pages\Guest\Parameters\IndexParameters;
use App\Livewire\Pages\Patients\CreatePatients;
use App\Livewire\Pages\Patients\DetailsPatients;
use App\Livewire\Pages\Patients\EditPatients;
use App\Livewire\Pages\Patients\IndexPatients;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified', 'role:Admin|Doctor'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::get('/doctor/profile', DetailsDoctors::class)->name('doctor.profile');
    Route::get('/doctor/edit/{doctor}', EditDoctors::class)->name('doctor.edit');

    Route::middleware('role:Admin')->group(function () {
        Route::get('/doctors', IndexDoctors::class)->name('doctors');
        Route::get('/doctors/details/{doctor}', DetailsDoctors::class)->name('doctors.details');
        Route::get('/doctors/create', CreateDoctors::class)->name('doctors.create');
        Route::get('/doctors/edit/{doctor}', EditDoctors::class)->name('doctors.edit');
    });

    Route::get('/documents', IndexDocuments::class)->name('documents');

    Route::get('/patients', IndexPatients::class)->name('patients');
    Route::get('/patients/details/{patient}', DetailsPatients::class)->name('patients.details');
    Route::get('/patients/create', CreatePatients::class)->name('patients.create');
    Route::get('/patients/edit/{patient}', EditPatients::class)->name('patients.edit');
});

Route::middleware(['auth', 'verified', 'role:Guest'])->group(function () {
    Route::get('/guest/dashboard', GuestDashboard::class)->name('guest.dashboard');

    Route::get('/guest/parameters', IndexParameters::class)->name('guest.parameters');
    Route::get('/guest/parameters/create', CreateParameters::class)->name('guest.parameters.create');
    Route::get('/guest/parameters/{parameter}/edit', EditParameter::class)->name('guest.parameters.edit');
});

require __DIR__.'/settings.php';
