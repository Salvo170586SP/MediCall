<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('permette a un dottore di fare login', function () {
    // 1. PREPARO: creo un admin nel database di test
    Role::create(['name' => 'Doctor']);

    $doctor = User::factory()->create([
        'email' => 'doctor@medicall.test',
        'password' => bcrypt('password123'),
    ]);
    $doctor->assignRole('Doctor');

    // 2. ESEGUO: testo il componente Livewire come farebbe l'utente
    $response = $this->post('/login', [
        'email' => 'doctor@medicall.test',
        'password' => 'password123',
    ]);

    // 3. VERIFICO
    $this->assertAuthenticatedAs($doctor);
});
