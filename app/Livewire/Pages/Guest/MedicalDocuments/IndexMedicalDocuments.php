<?php

namespace App\Livewire\Pages\Guest\MedicalDocuments;

use App\Concerns\WithCustomPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class IndexMedicalDocuments extends Component
{
    use WithCustomPagination, WithPagination;

    public function mount(): void
    {
        session()->forget('guest.has_new_report');
    }

    public function render()
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $medicalRecordsAsPatient = $authenticatedUser->medicalRecordsAsPatient()
            ->with('medicalRecordDocument')
            ->latest()
            ->paginate(10);

        return view('livewire.pages.guest.medical-documents.index-medical-documents', compact('medicalRecordsAsPatient'));
    }
}
