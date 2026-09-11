<?php

namespace App\Livewire\Pages\Guest\MedicalHistory;

use App\Models\User;
use Livewire\Component;

class EditMedicalHistory extends Component
{
    public User $user;

    public string $medical_history = '';

    public function mount(User $user): void
    {
        $this->medical_history = $user->guestDetail->medical_history ?? '';
    }

    public function editMedicalHistory(int $user_id): void
    {
        $validated = $this->validate($this->rules());
        $medical_history = $validated['medical_history'] ?? null;

        if (is_string($medical_history) && trim($medical_history) === '') {
            $medical_history = null;
        }

        $guest = User::findOrFail($user_id);
        $guest->guestDetail->update([
            'medical_history' => $medical_history,
        ]);

        $this->dispatch('medical-history-updated');
    }

    public function resetHistoryForm(): void
    {
        $this->medical_history = $this->user->guestDetail?->refresh()->medical_history ?? '';
        $this->resetValidation();
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'medical_history' => ['nullable', 'string'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.guest.medical-history.edit-medical-history');
    }
}
