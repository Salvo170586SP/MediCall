<?php

namespace App\Livewire\Pages\Guest\Allergies;

use App\Models\User;
use Livewire\Component;

class EditAllergies extends Component
{
    public User $user;

    public string $allergies = '';

    public function mount(User $user): void
    {
        $this->allergies = $user->guestDetail?->allergies ?? '';
    }

    public function editAllergies(int $user_id): void
    {
        $validated = $this->validate($this->rules());
        $allergies = $validated['allergies'] ?? null;

        if (is_string($allergies) && trim($allergies) === '') {
            $allergies = null;
        }

        $guest = User::findOrFail($user_id);
        $guest->guestDetail?->update([
            'allergies' => $allergies,
        ]);

        $this->dispatch('allergies-updated');
    }

    public function resetAllergiesForm(): void
    {
        $this->allergies = $this->user->guestDetail?->refresh()->allergies ?? '';
        $this->resetValidation();
    }

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'allergies' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function render()
    {
        return view('livewire.pages.guest.allergies.edit-allergies');
    }
}
