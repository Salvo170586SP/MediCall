<?php

namespace App\Livewire\Pages;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class EditProfileAdmin extends Component
{
    use PasswordValidationRules;
    use ProfileValidationRules;

    public string $name = '';

    public string $email = '';

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $notifications = false;

    public function mount(User $user): void
    {
        abort_unless($user->is(Auth::user()), 403);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->notifications = $user->doctorDetail?->notifications ?? false;
    }

    public function updateProfileInformation(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $this->validate([
            ...$this->profileRules($user->id),
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        Flux::toast(variant: 'success', text: __('Profilo aggiornato.'));
    }

    public function submitNotificated(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $this->validate([
            'notifications' => ['required', 'boolean'],
        ]);

        $detailData = [
            'notifications' => $validated['notifications'],
        ];

        $user->doctorDetail()->updateOrCreate([], $detailData);

        Flux::toast(variant: 'success', text: __('Stato notifiche aggiornate.'));
    }

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]);
        } catch (ValidationException $exception) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $exception;
        }

        /** @var User $user */
        $user = Auth::user();
        $user->update(['password' => $validated['password']]);
        $this->reset('current_password', 'password', 'password_confirmation');

        Flux::toast(variant: 'success', text: __('Password aggiornata.'));
    }

    public function render()
    {
        return view('livewire.pages.edit-profile-admin');
    }
}
