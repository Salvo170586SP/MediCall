<?php

namespace App\Livewire\Pages\Doctors;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CodeBadge extends Component
{
    public User $doctor;

    public function mount(User $doctor): void
    {
        $this->doctor = $doctor;
    }

    public function refreshCode(): void
    {
        $this->doctor->refresh();
        $this->doctor->load('doctorDetail');
    }

    public function getCodeItemPendingProperty(): bool
    {
        return blank($this->doctor->doctorDetail?->code_item);
    }

    public function render(): View
    {
        return view('livewire.pages.doctors.code-badge');
    }
}
