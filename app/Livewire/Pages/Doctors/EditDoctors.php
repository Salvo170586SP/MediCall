<?php

namespace App\Livewire\Pages\Doctors;

use App\Enums\DocumentType;
use App\Models\Document;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class EditDoctors extends Component
{
    use WithFileUploads;

    public User $doctor;

    public string $name = '';

    public string $phone = '';

    public string $bio = '';

    public string $specialty = '';

    public string $sub_specialty = '';

    public string $city = '';

    public int $slot_duration = 30;

    public bool $is_active = true;

    /** @var array<string, array{0: string, 1: string}> */
    public array $available_hours = [
        'mon' => ['', ''],
        'tue' => ['', ''],
        'wed' => ['', ''],
        'thu' => ['', ''],
        'fri' => ['', ''],
        'sat' => ['', ''],
        'sun' => ['', ''],
    ];

    public ?TemporaryUploadedFile $photo = null;

    /**
     * Validazione "live" della foto appena selezionata.
     */
    public function updatedPhoto(): void
    {
        $this->validateOnly('photo', [
            'photo' => 'image|mimes:jpg,jpeg,png,webp|max:2048', // max 2MB
        ]);
    }

    public function mount(User $doctor): void
    {
        $this->doctor = $doctor;
        $this->name = $doctor->name;
        $this->phone = $doctor->doctorDetail->phone ?? '';
        $this->bio = $doctor->doctorDetail->bio ?? '';
        $this->specialty = $doctor->doctorDetail->specialty ?? '';
        $this->sub_specialty = $doctor->doctorDetail->sub_specialty ?? '';
        $this->city = $doctor->doctorDetail->city ?? '';
        $this->slot_duration = $doctor->doctorDetail->slot_duration ?? 30;
        $this->is_active = $doctor->doctorDetail->is_active ?? true;

        $hours = $this->doctorAvailableHours($doctor);

        foreach ($hours as $day => $slots) {
            $slots = array_slice($slots, 0, 2);

            if (count($slots) < 2) {
                $this->available_hours[$day] = ['', ''];
            } else {
                $this->available_hours[$day] = [$slots[0], $slots[1]];
            }
        }
    }

    /** @return array<string, array<int, string>> */
    private function doctorAvailableHours(User $doctor): array
    {
        $hours = $doctor->doctorDetail->available_hours ?? [];

        return is_array($hours) ? $hours : [];
    }

    /**
     * Valida e crea un nuovo Doctor (User + DoctorDetail)
     */
    public function editDoctor(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'specialty' => 'required|string|max:255',
            'sub_specialty' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'slot_duration' => 'required|integer|in:15,20,30,45,60',
            'is_active' => 'boolean',
            'available_hours' => 'required|array|size:7',
            'available_hours.mon' => 'array|size:2',
            'available_hours.tue' => 'array|size:2',
            'available_hours.wed' => 'array|size:2',
            'available_hours.thu' => 'array|size:2',
            'available_hours.fri' => 'array|size:2',
            'available_hours.sat' => 'array|size:2',
            'available_hours.sun' => 'array|size:2',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Valida che gli orari siano nel formato corretto e logici
        foreach ($validated['available_hours'] as $day => $hours) {
            if (! empty($hours[0]) || ! empty($hours[1])) {
                if (empty($hours[0]) || empty($hours[1])) {
                    $this->addError("available_hours.{$day}", 'Orario di apertura e chiusura obbligatori se inseriti.');

                    return;
                }

                if ($hours[0] >= $hours[1]) {
                    $this->addError("available_hours.{$day}", "L'orario di apertura deve essere prima di quello di chiusura.");

                    return;
                }
            }
        }

        // Salva il file fisico PRIMA della transazione DB.
        // Se la transazione fallisce, va ripulito nel catch.
        $photoPath = null;
        if ($this->photo) {
            $photoPath = $this->photo->store('doctors/photos', 'public');
        }

        // modifica il Doctor usando una transazione per garantire consistenza
        try {
            DB::transaction(function () use ($validated, $photoPath) {
                // Crea l'utente con ruolo Doctor
                $this->doctor->update([
                    'name' => $validated['name'],
                ]);

                // Crea i dettagli del doctor
                $this->doctor->doctorDetail->update([
                    'phone' => $validated['phone'] ?? '',
                    'specialty' => $validated['specialty'],
                    'sub_specialty' => $validated['sub_specialty'] ?? '',
                    'bio' => $validated['bio'] ?? '',
                    'city' => $validated['city'] ?? '',
                    'slot_duration' => $validated['slot_duration'],
                    'is_active' => $validated['is_active'],
                    'available_hours' => $validated['available_hours'],
                ]);

                // Salva il riferimento della foto nella tabella documents,
                // collegata polimorficamente allo User (il dottore)
                if ($photoPath) {
                    $existing = Document::where('documentable_id', $this->doctor->id)
                        ->where('documentable_type', User::class)
                        ->where('type', DocumentType::Photo)
                        ->first();

                    if ($existing && $existing->file_path && Storage::disk('public')->exists($existing->file_path)) {
                        Storage::disk('public')->delete($existing->file_path);
                    }

                    Document::updateOrCreate(
                        [
                            'documentable_id' => $this->doctor->id,
                            'documentable_type' => User::class,
                            'type' => DocumentType::Photo,
                        ],
                        [
                            'uploaded_by' => auth()->id() ?? $this->doctor->id,
                            'title' => 'Foto profilo',
                            'file_path' => $photoPath,
                            'original_filename' => $this->photo->getClientOriginalName(),
                            'mime_type' => $this->photo->getMimeType(),
                            'file_size' => $this->photo->getSize(),
                            'is_verified' => true,
                        ]
                    );
                }
            });

            session()->flash('success', 'Elemento modificato con successo!');
            $authenticatedUser = Auth::user();
            $redirectRoute = $authenticatedUser instanceof User && $authenticatedUser->hasRole('Doctor')
                ? 'doctor.profile'
                : 'doctors';

            $this->redirect(route($redirectRoute), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la modifica: '.$e->getMessage());
        }
    }

    public function render(): View
    {
        return view('livewire.pages.doctors.edit-doctors');
    }
}
