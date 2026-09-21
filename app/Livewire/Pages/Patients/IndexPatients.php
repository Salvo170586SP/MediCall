<?php

namespace App\Livewire\Pages\Patients;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPatients extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deletePatient(int $patient_id)
    {
        try {
            $patient = User::findOrFail($patient_id);

            if ($patient) {
                $patient->delete();
            }

            session()->flash('success', 'Elemento eliminato con successo!');
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la eliminazione: '.$e->getMessage());
        }
    }

    public function detachPatientOnDoctor(int $patient_id)
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        abort_unless($authenticatedUser->hasRole('Doctor'), 403);

        try {
            $authenticatedUser->patients()->detach($patient_id);

            session()->flash('success', 'Paziente scollegato con successo!');
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la eliminazione: '.$e->getMessage());
        }
    }

    public function render()
    {
        $patientsQuery = User::role('Guest')
            ->with('guestDetail')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            });

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->hasRole('Doctor')) {
            $patientIds = Appointment::query()
                ->where('doctor_id', $authenticatedUser->getKey())
                ->distinct()
                ->pluck('guest_id');

            $patientsQuery->whereKey($patientIds);
        }

        $users_patients = $patientsQuery->paginate(10);

        return view('livewire.pages.patients.index-patients', compact('users_patients'));
    }
}
