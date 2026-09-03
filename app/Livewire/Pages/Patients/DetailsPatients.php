<?php

namespace App\Livewire\Pages\Patients;

use App\Concerns\WithCustomPagination;
use App\Models\Appointment;
use App\Models\Document;
use App\Models\HealthParameter;
use App\Models\HealthThreshold;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DetailsPatients extends Component
{
    use WithCustomPagination, WithPagination;

    public User $patient;

    public string $source = '';

    public string $type = '';

    public string $searchStatus = '';

    public string $searchType = '';

    public string $severity = '';

    public string $created_at = '';

    public string $searchDoctor = '';

    public string $searchCreatedAt = '';

    public string $searchVisit = '';

    public string $searchControll = '';

    public string $searchTypeDoc = '';

    public string $searchNameDoc = '';

    public function mount(User $patient)
    {
        $this->patient = $patient->load('guestDetail');
    }

    public function updatingsearchTypeDoc(): void
    {
        $this->resetPage();
    }

    public function updatingSearchNameDoc(): void
    {
        $this->resetPage();
    }

    public function updatingSearchVisit(): void
    {
        $this->resetPage();
    }

    public function updatingSearchControll(): void
    {
        $this->resetPage();
    }

    public function updatingSource(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingSeverity(): void
    {
        $this->resetPage();
    }

    public function updatingCreatedAt(): void
    {
        $this->resetPage();
    }

    public function updatingSearchType(): void
    {
        $this->resetPage();
    }

    public function updatingSearchStatus(): void
    {
        $this->resetPage();
    }

    public function updatingSearchCreatedAt(): void
    {
        $this->resetPage();
    }

    public function updatingSearchDoctor(): void
    {
        $this->resetPage();
    }

    #[On('appointment-created')]
    public function refreshAppointments(): void
    {
        $this->resetPage();
    }

    public function deleteParameter(int $parameter_id)
    {
        $parameter = HealthParameter::findOrFail($parameter_id);

        if ($parameter) {
            $parameter->delete();
        }
    }

    public function deleteThreshold(int $threshold_id)
    {
        $threshold = HealthThreshold::findOrFail($threshold_id);

        if ($threshold) {
            $threshold->delete();
        }
    }

    public function deleteAppointment(int $appointment_id)
    {
        $appointment = Appointment::findOrFail($appointment_id);

        if ($appointment) {
            $appointment->delete();
        }
    }

    public function deleteDoc(int $document_id)
    {
        $document = Document::findOrFail($document_id);

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->forceDelete();
    }

    public function render()
    {
        // Thresholds
        $healthThresholdsQuery = $this->patient->healthThresholds()->with(['patient', 'createdBy']);

        if ($this->severity) {
            $healthThresholdsQuery->where('severity', 'like', '%'.$this->severity.'%');
        }

        if ($this->created_at) {
            $healthThresholdsQuery->where('created_at', 'like', '%'.$this->created_at.'%');
        }

        $healthThresholds = $healthThresholdsQuery->paginate(20)->withPath(route('patients.details', $this->patient));

        // Parameters
        $healthParametersQuery = $this->patient->healthParameters()->with('patient');

        if ($this->source) {
            $healthParametersQuery->where('source', 'like', '%'.$this->source.'%');
        }

        if ($this->type) {
            $healthParametersQuery->where('type', 'like', '%'.$this->type.'%');
        }

        $healthParameters = $healthParametersQuery->paginate(10)->withPath(route('patients.details', $this->patient));

        // Appointments
        $appointmentsQuery = $this->patient->appointmentsPatient()->with(['doctor', 'patient']);

        if ($this->searchStatus) {
            $appointmentsQuery->where('status', 'like', '%'.$this->searchStatus.'%');
        }

        if ($this->searchType) {
            $appointmentsQuery->where('type', 'like', '%'.$this->searchType.'%');
        }

        if ($this->searchCreatedAt) {
            $appointmentsQuery->where('scheduled_at', 'like', '%'.$this->searchCreatedAt.'%');
        }

        if ($this->searchDoctor) {
            $appointmentsQuery->whereHas('doctor', function ($query) {
                $query->where('name', 'like', '%'.$this->searchDoctor.'%');
            });
        }

        $appointments = $appointmentsQuery->paginate(10)->withPath(route('patients.details', $this->patient));

        // MedicalRecordsAsPatient
        $medicalRecordsAsPatientQuery = $this->patient->medicalRecordsAsPatient()->with(['doctor', 'patient']);

        if ($this->searchControll) {
            $medicalRecordsAsPatientQuery->where('follow_up_date', 'like', '%'.$this->searchControll.'%');
        }

        if ($this->searchVisit) {
            $medicalRecordsAsPatientQuery->where('visit_date', 'like', '%'.$this->searchVisit.'%');
        }

        $medicalRecordsAsPatient = $medicalRecordsAsPatientQuery->paginate(10)->withPath(route('patients.details', $this->patient));

        $appointments = $appointmentsQuery->paginate(10)->withPath(route('patients.details', $this->patient));

        // Documents
        $documentsQuery = $this->patient->documents();

        if ($this->searchTypeDoc) {
            $documentsQuery->where('title', 'like', '%'.$this->searchTypeDoc.'%');
        }

        if ($this->searchNameDoc) {
            $documentsQuery->where('original_filename', 'like', '%'.$this->searchNameDoc.'%');
        }

        $documents = $documentsQuery->paginate(10)->withPath(route('patients.details', $this->patient));

        // configs
        $severities = config('medicall.defaultThresholds.severities');
        $statuses = config('medicall.appointments.statuses');
        $types = config('medicall.appointments.types');

        return view('livewire.pages.patients.details-patients', compact('healthParameters', 'healthThresholds', 'appointments', 'medicalRecordsAsPatient', 'documents', 'severities', 'statuses', 'types'));
    }
}
