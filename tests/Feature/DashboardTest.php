<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class); // <-- verifica che ci sia

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated Admin can visit the dashboard', function () {
    Role::create(['name' => 'Admin']); // o il ruolo che la dashboard richiede

    $user = User::factory()->create();
    $user->assignRole('Admin');

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('authenticated Doctor can visit the dashboard', function () {
    Role::create(['name' => 'Doctor']); 

    $user = User::factory()->create();
    $user->assignRole('Doctor');

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});