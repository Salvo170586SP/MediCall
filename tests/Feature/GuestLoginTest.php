<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('reindirizza un paziente guest alla dashboard guest dopo il login', function () {
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

    $response->assertRedirect(route('guest.dashboard'));
    $this->assertAuthenticatedAs($guest);
    $this->assertTrue($guest->fresh()->hasRole('Guest'));
});
