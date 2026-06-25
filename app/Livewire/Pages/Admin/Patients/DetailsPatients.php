<?php

namespace App\Livewire\Pages\Admin\Patients;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DetailsPatients extends Component
{
    use WithPagination;

    public User $patient;
    public string $source = '';
    public string $type = '';

    public function mount(User $patient)
    {
        $this->patient = $patient;
    }

    public function render()
    {
        $healthParametersQuery  = $this->patient->healthParameters();

        if ($this->source) {
            $healthParametersQuery->where('source', 'like', '%' . $this->source . '%');
        }

        if ($this->type) {
            $healthParametersQuery->where('type', 'like', '%' . $this->type . '%');
        }

      $healthParameters =  $healthParametersQuery->paginate(10);

        return view('livewire.pages.admin.patients.details-patients', compact('healthParameters'));
    }
}
