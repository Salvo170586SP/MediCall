<?php

use App\Models\User;
use Laravel\Fortify\Features;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::registration());
});

test('registration screen can be rendered', function () {
    $response = $this->get(route('register'));

    $response->assertOk();
});

test('new users can register', function () {
    $response = $this->post(route('register.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'phone' => '+39 333 1234567',
        'tax_id_code' => 'JHNDOE90A01H501Z',
        'birth_city' => 'Roma',
        'birth_date' => '1990-01-01',
        'gender' => 'M',
    ]);

    $response->assertSessionHasNoErrors()
        ->assertRedirect(route('guest.dashboard', absolute: false));

    $user = User::query()->where('email', 'test@example.com')->firstOrFail();

    $this->assertAuthenticated()
        ->assertDatabaseHas('guest_details', [
            'guest_id' => $user->id,
            'phone' => '+39 333 1234567',
            'tax_id_code' => 'JHNDOE90A01H501Z',
            'birth_city' => 'Roma',
            'birth_date' => '1990-01-01',
            'gender' => 'M',
        ]);
});
