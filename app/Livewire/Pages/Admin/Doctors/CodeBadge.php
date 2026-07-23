<?php

namespace App\Livewire\Pages\Admin\Doctors;

use App\Models\User;
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

    public function render()
    {
        return view('livewire.pages.admin.doctors.code-badge');
    }
}
