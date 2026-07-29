<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('permette a un paziente guest di fare login', function () {
    // 1. PREPARO: creo un admin nel database di test
    Role::create(['name' => 'Guest']);

    $guest = User::factory()->create([
        'email' => 'guest@medicall.test',
        'password' => bcrypt('password123'),
    ]);
    $guest->assignRole('Guest');

    // 2. ESEGUO: testo il componente Livewire come farebbe l'utente
    $response = $this->post('/login', [
        'email' => 'guest@medicall.test',
        'password' => 'password123',
    ]);

    // 3. VERIFICO
    $this->assertAuthenticatedAs($guest);
});