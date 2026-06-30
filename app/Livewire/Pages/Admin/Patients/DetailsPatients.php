<?php

namespace App\Livewire\Pages\Admin\Patients;

use App\Concerns\WithCustomPagination;
use App\Models\HealthParameter;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DetailsPatients extends Component
{
    use WithPagination, WithCustomPagination;

    public User $patient;
    public string $source = '';
    public string $type = '';
    public string $severity = '';
    public string $created_at = '';

    public function mount(User $patient)
    {
        $this->patient = $patient;
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

    public function deleteParameter(int $parameter_id)
    {
        $parameter = HealthParameter::findOrFail($parameter_id);

        if ($parameter) {
            $parameter->delete();
        }
    }

    public function render()
    {
        $healthThresholdsQuery = $this->patient->healthThresholds();

        if ($this->severity) {
            $healthThresholdsQuery->where('severity', 'like', '%' . $this->severity . '%');
        }

        if ($this->created_at) {
            $healthThresholdsQuery->where('created_at', 'like', '%' . $this->created_at . '%');
        }

        $healthThresholds = $healthThresholdsQuery->paginate(20)->withPath(route('patients.details', $this->patient));


        $healthParametersQuery = $this->patient->healthParameters();

        if ($this->source) {
            $healthParametersQuery->where('source', 'like', '%' . $this->source . '%');
        }

        if ($this->type) {
            $healthParametersQuery->where('type', 'like', '%' . $this->type . '%');
        }

        $healthParameters = $healthParametersQuery->paginate(10)->withPath(route('patients.details', $this->patient));

        $severities = config('medicall.defaultThresholds.severities');
         
        return view('livewire.pages.admin.patients.details-patients', compact('healthParameters', 'healthThresholds', 'severities'));
    }
}
