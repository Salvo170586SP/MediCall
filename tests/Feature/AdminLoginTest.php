<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('permette a un admin di fare login', function () {
    // 1. PREPARO: creo un admin nel database di test
    Role::create(['name' => 'Admin']);

    $admin = User::factory()->create([
        'email' => 'admin@medicall.test',
        'password' => bcrypt('password123'),
    ]);
    $admin->assignRole('Admin');

    // 2. ESEGUO: testo il componente Livewire come farebbe l'utente
    $response = $this->post('/login', [
        'email' => 'admin@medicall.test',
        'password' => 'password123',
    ]);

    // 3. VERIFICO
    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticatedAs($admin);
});
