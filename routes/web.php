<?php

use App\Livewire\Pages\Admin\Doctors\CreateDoctors;
use App\Livewire\Pages\Admin\Doctors\DetailsDoctors;
use App\Livewire\Pages\Admin\Doctors\EditDoctors;
use App\Livewire\Pages\Admin\Doctors\IndexDoctors;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified', 'role:Admin'])->group(function () {
    Route::get('/doctors', IndexDoctors::class)->name('doctors');
    Route::get('/doctors/details/{doctor}', DetailsDoctors::class)->name('doctors.details');
    Route::get('/doctors/create', CreateDoctors::class)->name('doctors.create');
    Route::get('/doctors/edit/{doctor}', EditDoctors::class)->name('doctors.edit');

    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
