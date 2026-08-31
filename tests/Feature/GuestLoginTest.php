<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('permette a un paziente guest di fare login e resta nella welcome', function () {
    Role::create(['name' => 'Guest']);

    $guest = User::factory()->create([
        'email' => 'guest@medicall.test',
        'password' => bcrypt('password123'),
    ]);
    $guest->assignRole('Guest');

    $response = $this->post('/login', [
        'email' => 'guest@medicall.test',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('home'));
    $this->assertAuthenticatedAs($guest);
    $this->assertTrue($guest->fresh()->hasRole('Guest'));
});