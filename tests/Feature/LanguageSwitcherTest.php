<?php

use App\Livewire\Components\LanguageSwitcher;
use Livewire\Livewire;

test('the locale switcher stores supported locales in the session', function () {
    Livewire::test(LanguageSwitcher::class)
        ->call('switchLocale', 'en')
        ->assertRedirect();

    expect(session('locale'))->toBe('en')
        ->and(app()->getLocale())->toBe('en');
});

test('the locale switcher rejects unsupported locales', function () {
    Livewire::test(LanguageSwitcher::class)
        ->call('switchLocale', 'fr')
        ->assertStatus(404);
});

test('static labels are translated without changing dynamic values', function () {
    app()->setLocale('en');

    expect(__('Dottori'))->toBe('Doctors')
        ->and(__('Pazienti'))->toBe('Patients');

    app()->setLocale('it');

    expect(__('Dottori'))->toBe('Dottori')
        ->and(__('Pazienti'))->toBe('Pazienti');
});
