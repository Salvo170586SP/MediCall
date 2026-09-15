<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Livewire\Component;
use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use Illuminate\Support\Facades\Auth;
use Flux\Flux;
use Illuminate\Validation\ValidationException;

class EditProfileAdmin extends Component
{
    use PasswordValidationRules;
    use ProfileValidationRules;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $birth_city = '';
    public string $birth_date = '';
    public string $tax_id_code = '';
    public string $gender = '';
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(User $user): void
    {
        abort_unless($user->is(Auth::user()), 403);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->doctorDetail?->phone ?? '';
        $this->birth_city = $user->doctorDetail?->birth_city ?? '';
        $this->birth_date = $user->doctorDetail?->birth_date?->format('Y-m-d') ?? '';
        $this->tax_id_code = $user->doctorDetail?->tax_id_code ?? '';
        $this->gender = $user->doctorDetail?->gender ?? '';
    }

    public function updateProfileInformation(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $this->validate([
            ...$this->profileRules($user->id),
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'birth_city' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'tax_id_code' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'string', 'max:30'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        $detailData = [
            'phone' => $validated['phone'],
            'birth_city' => $validated['birth_city'],
            'birth_date' => blank($validated['birth_date']) ? null : $validated['birth_date'],
            'tax_id_code' => $validated['tax_id_code'],
            'gender' => $validated['gender'],
        ];

        $guestDetail = $user->guestDetail;
        if ($guestDetail !== null || collect($detailData)->contains(filled(...))) {
            $user->guestDetail()->updateOrCreate([], [
                ...$detailData,
                'blood_type' => $guestDetail?->blood_type ?? '',
            ]);
        }

        Flux::toast(variant: 'success', text: __('Profilo aggiornato.'));
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
