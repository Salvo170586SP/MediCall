<?php

use App\Livewire\Pages\Guest\MedicalHistory\EditMedicalHistory;
use App\Models\GuestDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('la modale di storia clinica chiude sul corretto evento livewire', function () {
    $guest = User::factory()->create();
    GuestDetail::create([
        'guest_id' => $guest->id,
        'blood_type' => 'A+',
    ]);

    Livewire::test(EditMedicalHistory::class, ['user' => $guest])
        ->assertSeeHtml('x-on:medical-history-updated.window');
});

test('modifica la storia clinica quando il campo contiene testo', function () {
    $guest = User::factory()->create();
    $guestDetail = GuestDetail::create([
        'guest_id' => $guest->id,
        'blood_type' => 'A+',
        'medical_history' => 'Controlli annuali',
    ]);

    Livewire::test(EditMedicalHistory::class, ['user' => $guest])
        ->set('medical_history', 'Visita cardiologica')
        ->call('editMedicalHistory', $guest->id)
        ->assertHasNoErrors();

    expect($guestDetail->refresh()->medical_history)->toBe('Visita cardiologica');
});
