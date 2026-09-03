<?php

namespace App\Livewire\Pages\Guest\Parameters;

use App\Concerns\WithCustomPagination;
use App\Models\HealthParameter;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class IndexParameters extends Component
{
    use WithCustomPagination, WithPagination;

    public string $source = '';

    public string $type = '';

    public function deleteParameter(int $parameter_id)
    {
        try {
            $parameter = HealthParameter::findOrFail($parameter_id);

            if ($parameter) {
                $parameter->delete();
            }

            session()->flash('success', 'Elemento eliminato con successo!');
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la eliminazione: '.$e->getMessage());
        }
    }

    public function render()
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $parameters = $authenticatedUser->healthParameters();

        if ($this->source) {
            $parameters->where('source', 'like', '%'.$this->source.'%');
        }

        if ($this->type) {
            $parameters->where('type', 'like', '%'.$this->type.'%');
        }

        $parameters = $parameters
            ->latestFirst()
            ->paginate(10);

        return view('livewire.pages.guest.parameters.index-parameters', compact('parameters'));
    }
}
