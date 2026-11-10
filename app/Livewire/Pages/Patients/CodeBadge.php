<?php

namespace App\Livewire\Pages\Patients;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CodeBadge extends Component
{
    public User $patient;

    public function mount(User $patient): void
    {
        $this->patient = $patient;
    }

    public function refreshCode(): void
    {
        $this->patient->refresh();
        $this->patient->load('guestDetail');
    }

    public function getCodeItemPendingProperty(): bool
    {
        return blank($this->patient->guestDetail?->code_item);
    }

    public function render(): View
    {
        return view('livewire.pages.patients.code-badge');
    }
}
