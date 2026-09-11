<?php

use App\Livewire\Pages\Guest\EditProfile;
use App\Models\GuestDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function createGuestWithProfile(): User
{
    $guest = User::factory()->create();
    GuestDetail::create([
        'guest_id' => $guest->id,
        'blood_type' => 'A+',
    ]);

    return $guest;
}

test('guest profile page is displayed for the authenticated guest', function () {
    Role::create(['name' => 'Guest']);
    $guest = createGuestWithProfile();
    $guest->assignRole('Guest');

    $this->actingAs($guest)
        ->get(route('guest.edit-profile', $guest))
        ->assertSuccessful();
});

test('guest can update personal information', function () {
    $guest = createGuestWithProfile();

    Livewire::actingAs($guest)->test(EditProfile::class, ['patient' => $guest])
        ->set('name', 'Mario Rossi')
        ->set('email', 'mario.rossi@example.com')
        ->set('phone', '3331234567')
        ->set('birth_city', 'Roma')
        ->set('birth_date', '1990-05-10')
        ->set('tax_id_code', 'RSSMRA90E10H501Z')
        ->set('gender', 'M')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    expect($guest->fresh()->only('name', 'email'))->toMatchArray([
        'name' => 'Mario Rossi',
        'email' => 'mario.rossi@example.com',
    ]);
    $detail = $guest->fresh()->guestDetail;

    expect($detail->phone)->toBe('3331234567')
        ->and($detail->birth_city)->toBe('Roma')
        ->and($detail->birth_date->format('Y-m-d'))->toBe('1990-05-10')
        ->and($detail->tax_id_code)->toBe('RSSMRA90E10H501Z')
        ->and($detail->gender)->toBe('M');
});

test('guest can create their detail while updating personal information', function () {
    $guest = User::factory()->create();

    Livewire::actingAs($guest)->test(EditProfile::class, ['patient' => $guest])
        ->set('phone', '3331234567')
        ->set('birth_city', 'Roma')
        ->call('updateProfileInformation')
        ->assertHasNoErrors();

    $detail = $guest->fresh()->guestDetail;

    expect($detail)->not->toBeNull()
        ->and($detail->phone)->toBe('3331234567')
        ->and($detail->birth_city)->toBe('Roma');
});

test('guest can change password after confirming the current password', function () {
    $guest = createGuestWithProfile();

    Livewire::actingAs($guest)->test(EditProfile::class, ['patient' => $guest])
        ->set('current_password', 'password')
        ->set('password', 'new-password-123')
        ->set('password_confirmation', 'new-password-123')
        ->call('updatePassword')
        ->assertHasNoErrors();

    expect(Hash::check('new-password-123', $guest->fresh()->password))->toBeTrue();
});

test('guest cannot edit another guest profile', function () {
    $guest = createGuestWithProfile();
    $otherGuest = createGuestWithProfile();

    $this->actingAs($guest)
        ->get(route('guest.edit-profile', $otherGuest))
        ->assertForbidden();
});
