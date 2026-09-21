<?php

use App\Livewire\Pages\Guest\GuestDashboard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('shows a new appointment message when the appointment notification is received', function () {
    $guest = User::factory()->create();

    $component = Livewire::actingAs($guest)->test(GuestDashboard::class);

    $component
        ->assertDontSee('Hai un nuovo appuntamento')
        ->dispatch('appointment-created-notification')
        ->assertSee('Hai un nuovo appuntamento');
});
