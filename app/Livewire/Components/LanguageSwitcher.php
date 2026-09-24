<?php

namespace App\Livewire\Components;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class LanguageSwitcher extends Component
{
    public function switchLocale(string $locale): void
    {
        abort_unless(in_array($locale, ['it', 'en'], true), 404);

        session(['locale' => $locale]);
        app()->setLocale($locale);

        $this->redirect(url()->previous(), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.components.language-switcher');
    }
}
