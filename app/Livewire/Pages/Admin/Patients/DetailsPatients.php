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

    public function deleteParameter(int $parameter_id)
    {
        $parameter = HealthParameter::findOrFail($parameter_id);

        if ($parameter) {
            $parameter->delete();
        }
    }

    public function render()
    {
        $healthParametersQuery = $this->patient->healthParameters();

        if ($this->source) {
            $healthParametersQuery->where('source', 'like', '%' . $this->source . '%');
        }

        if ($this->type) {
            $healthParametersQuery->where('type', 'like', '%' . $this->type . '%');
        }

        $healthParameters = $healthParametersQuery->paginate(10)->withPath(route('patients.details', $this->patient));

        return view('livewire.pages.admin.patients.details-patients', compact('healthParameters'));
    }
}
