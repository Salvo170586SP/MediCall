<?php

use App\Livewire\Pages\Admin\Doctors\CreateDoctors;
use App\Livewire\Pages\Admin\Doctors\DetailsDoctors;
use App\Livewire\Pages\Admin\Doctors\EditDoctors;
use App\Livewire\Pages\Admin\Doctors\IndexDoctors;
use App\Livewire\Pages\Admin\Documents\IndexDocuments;
use App\Livewire\Pages\Admin\Patients\CreatePatients;
use App\Livewire\Pages\Admin\Patients\DetailsPatients;
use App\Livewire\Pages\Admin\Patients\EditPatients;
use App\Livewire\Pages\Admin\Patients\IndexPatients;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified', 'role:Admin|Doctor'])->group(function () {
    Route::get('/doctors', IndexDoctors::class)->name('doctors');
    Route::get('/doctors/details/{doctor}', DetailsDoctors::class)->name('doctors.details');
    Route::get('/doctors/create', CreateDoctors::class)->name('doctors.create');
    Route::get('/doctors/edit/{doctor}', EditDoctors::class)->name('doctors.edit');

    Route::get('/documents', IndexDocuments::class)->name('documents');

    Route::get('/patients', IndexPatients::class)->name('patients');
    Route::get('/patients/details/{patient}', DetailsPatients::class)->name('patients.details');
    Route::get('/patients/create', CreatePatients::class)->name('patients.create');
    Route::get('/patients/edit/{patient}', EditPatients::class)->name('patients.edit');

    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
