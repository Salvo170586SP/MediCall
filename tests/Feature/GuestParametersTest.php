<?php

use App\Livewire\Pages\Guest\Parameters\CreateParameters;
use App\Livewire\Pages\Guest\Parameters\EditParameter;
use App\Models\HealthParameter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('guest vede solo i propri parametri sanitari', function () {
    Role::create(['name' => 'Guest']);

    $guest = User::factory()->create();
    $guest->assignRole('Guest');
    $otherGuest = User::factory()->create();
    $otherGuest->assignRole('Guest');

    HealthParameter::create([
        'guest_id' => $guest->id,
        'type' => 'heart_rate',
        'value' => 72,
        'unit' => 'bpm',
        'measured_at' => now()->subHour(),
        'source' => 'manual',
        'notes' => 'Misurazione mattutina',
    ]);
    HealthParameter::create([
        'guest_id' => $guest->id,
        'type' => 'body_temperature',
        'value' => 36.5,
        'unit' => '°C',
        'measured_at' => now(),
        'source' => 'device',
    ]);
    HealthParameter::create([
        'guest_id' => $otherGuest->id,
        'type' => 'blood_glucose',
        'value' => 98,
        'unit' => 'mg/dL',
        'measured_at' => now(),
        'source' => 'wearable',
        'notes' => 'Parametro di un altro Guest',
    ]);

    $this->actingAs($guest)
        ->get(route('guest.parameters'))
        ->assertOk()
        ->assertSee('2 misurazioni')
        ->assertSee('heart rate')
        ->assertSee('body temperature')
        ->assertSee('Misurazione mattutina')
        ->assertDontSee('Parametro di un altro Guest')
        ->assertDontSee('blood glucose');
});

test('guest vede il totale dei parametri anche oltre la prima pagina', function () {
    Role::create(['name' => 'Guest']);

    $guest = User::factory()->create();
    $guest->assignRole('Guest');

    foreach (range(1, 11) as $measurement) {
        HealthParameter::create([
            'guest_id' => $guest->id,
            'type' => 'heart_rate',
            'value' => 60 + $measurement,
            'unit' => 'bpm',
            'measured_at' => now()->subMinutes($measurement),
            'source' => 'manual',
        ]);
    }

    $this->actingAs($guest)
        ->get(route('guest.parameters'))
        ->assertOk()
        ->assertSee('11 misurazioni');
});

test('guest crea i parametri selezionati con le unita predefinite', function () {
    Role::create(['name' => 'Guest']);

    $guest = User::factory()->create();
    $guest->assignRole('Guest');

    Livewire::actingAs($guest)
        ->test(CreateParameters::class)
        ->set('selectedParameters', [
            'peso' => true,
            'frequenza cardiaca' => true,
        ])
        ->set('values', [
            'peso' => '70',
            'frequenza cardiaca' => '65.56',
        ])
        ->set('measuredAt', [
            'peso' => now()->format('Y-m-d\\TH:i'),
            'frequenza cardiaca' => now()->subMinute()->format('Y-m-d\\TH:i'),
        ])
        ->set('source', [
            'peso' => 'wearable',
            'frequenza cardiaca' => 'device',
        ])
        ->set('notes', [
            'peso' => 'Peso del mattino',
            'frequenza cardiaca' => 'Misurazione del mattino',
        ])
        ->call('save')
        ->assertRedirect(route('guest.parameters'));

    expect(HealthParameter::whereBelongsTo($guest, 'patient')->get())
        ->toHaveCount(2)
        ->and(HealthParameter::where('type', 'peso')->first()->unit)->toBe('kg')
        ->and(HealthParameter::where('type', 'frequenza cardiaca')->first()->unit)->toBe('bpm')
        ->and(HealthParameter::where('type', 'peso')->first()->source)->toBe('wearable')
        ->and(HealthParameter::where('type', 'frequenza cardiaca')->first()->source)->toBe('device')
        ->and(HealthParameter::where('type', 'peso')->first()->notes)->toBe('Peso del mattino')
        ->and(HealthParameter::where('guest_id', '!=', $guest->id)->exists())->toBeFalse();
});

test('guest deve selezionare un parametro e rispettare il suo intervallo', function () {
    Role::create(['name' => 'Guest']);

    $guest = User::factory()->create();
    $guest->assignRole('Guest');

    Livewire::actingAs($guest)
        ->test(CreateParameters::class)
        ->set('selectedParameters', ['saturazione' => true])
        ->set('values', ['saturazione' => '101'])
        ->call('save')
        ->assertHasErrors(['values.saturazione' => ['between']]);

    expect(HealthParameter::count())->toBe(0);
});

test('guest modifica solo i propri parametri sanitari', function () {
    Role::create(['name' => 'Guest']);

    $guest = User::factory()->create();
    $guest->assignRole('Guest');
    $otherGuest = User::factory()->create();
    $otherGuest->assignRole('Guest');

    $parameter = HealthParameter::create([
        'guest_id' => $guest->id,
        'type' => 'heart_rate',
        'value' => 72,
        'unit' => 'bpm',
        'measured_at' => now()->subHour(),
        'source' => 'manual',
        'notes' => 'Prima nota',
    ]);
    $otherParameter = HealthParameter::create([
        'guest_id' => $otherGuest->id,
        'type' => 'heart_rate',
        'value' => 80,
        'unit' => 'bpm',
        'measured_at' => now()->subHour(),
        'source' => 'manual',
    ]);

    Livewire::actingAs($guest)
        ->test(EditParameter::class, ['parameter' => $parameter])
        ->set('value', '65')
        ->set('measuredAt', now()->subMinutes(10)->format('Y-m-d\\TH:i'))
        ->set('source', 'device')
        ->set('notes', 'Nota aggiornata')
        ->call('save')
        ->assertRedirect(route('guest.parameters'));

    expect($parameter->refresh()->value)->toBe('65.00')
        ->and($parameter->notes)->toBe('Nota aggiornata')
        ->and($parameter->source)->toBe('device');

    $this->actingAs($guest)
        ->get(route('guest.parameters.edit', $otherParameter))
        ->assertNotFound();
});
