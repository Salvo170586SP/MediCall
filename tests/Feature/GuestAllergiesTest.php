<?php

use App\Livewire\Pages\Guest\Allergies\EditAllergies;
use App\Models\GuestDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('rifiuta allergie oltre 255 caratteri', function () {
    $guest = User::factory()->create();
    $guestDetail = GuestDetail::create([
        'guest_id' => $guest->id,
        'blood_type' => 'A+',
    ]);

    Livewire::test(EditAllergies::class, ['user' => $guest])
        ->set('allergies', str_repeat('a', 256))
        ->call('editAllergies', $guest->id)
        ->assertHasErrors(['allergies' => ['max']]);

    expect($guestDetail->refresh()->allergies)->toBeNull();
});

test('imposta a null le allergie quando il campo contiene solo spazi', function () {
    $guest = User::factory()->create();
    $guestDetail = GuestDetail::create([
        'guest_id' => $guest->id,
        'blood_type' => 'A+',
        'allergies' => 'Polline',
    ]);

    Livewire::test(EditAllergies::class, ['user' => $guest])
        ->set('allergies', " \n\t ")
        ->call('editAllergies', $guest->id)
        ->assertHasNoErrors();

    expect($guestDetail->refresh()->allergies)->toBeNull();
});

test('modifica le allergie quando il campo contiene caratteri', function () {
    $guest = User::factory()->create();
    $guestDetail = GuestDetail::create([
        'guest_id' => $guest->id,
        'blood_type' => 'A+',
        'allergies' => 'Polline',
    ]);

    Livewire::test(EditAllergies::class, ['user' => $guest])
        ->set('allergies', 'Latte')
        ->call('editAllergies', $guest->id)
        ->assertHasNoErrors();

    expect($guestDetail->refresh()->allergies)->toBe('Latte');
});
