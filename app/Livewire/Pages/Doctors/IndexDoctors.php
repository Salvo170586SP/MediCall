<?php

namespace App\Livewire\Pages\Doctors;

use App\Enums\DocumentType;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class IndexDoctors extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteDoctor(int $doctor_id)
    {
        try {
            $doctor = User::findOrFail($doctor_id);

            if ($doctor) {
                $photo = Document::where('documentable_id', $doctor->id)
                    ->where('documentable_type', User::class)
                    ->where('type', DocumentType::Photo) 
                    ->first();

                if ($photo) {
                    if ($photo->file_path && Storage::disk('public')->exists($photo->file_path)) {
                        Storage::disk('public')->delete($photo->file_path);
                    }

                    $photo->delete();
                }

                $doctor->delete();
            }

            session()->flash('success', 'Elemento eliminato con successo!');
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la eliminazione: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $users_doctor = User::role('Doctor')
            ->with('doctorDetail')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(10);


        return view('livewire.pages.doctors.index-doctors', compact('users_doctor'));
    }
}
