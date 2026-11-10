<?php

namespace App\Livewire\Pages\Guest\Doctors;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class IndexDoctors extends Component
{
    use WithPagination;

    public string $search = '';

    public bool $is_active = false;

    public string $search_city = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingIsActive(): void
    {
        $this->resetPage();
    }

    public function updatingSearchCity(): void
    {
        $this->resetPage();
    }

    public function render(): View
    {
        $users_doctor = User::role('Doctor')
            ->with('doctorDetail')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->is_active, function ($query) {
                $query->whereHas('doctorDetail', function ($q) {
                    $q->where('is_active', true);
                });
            })
            ->when($this->search_city, function ($query) {
                $query->whereHas('doctorDetail', function ($q) {
                    $q->where('city', 'like', '%'.$this->search_city.'%');
                });
            })
            ->paginate(10);

        return view('livewire.pages.guest.doctors.index-doctors', compact('users_doctor'));
    }
}
