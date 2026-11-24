<?php

namespace App\Livewire\Pages\Guest\UploadDocuments;

use App\Concerns\WithCustomPagination;
use App\Enums\DocumentType;
use App\Models\Appointment;
use App\Models\Document;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class IndexUploadDocuments extends Component
{
    use WithCustomPagination, WithFileUploads, WithPagination;

    public ?int $doctor_id = null;
    public string $title = '';
    public string $document_type = 'other';
    public ?TemporaryUploadedFile $document = null;
    public string $successMessage = '';

    public function mount(): void
    {
        session()->forget('guest.has_new_report');
    }

    public function updatedDocument(): void
    {
        $this->validateOnly('document', [
            'document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048'],
        ]);
    }

    /** @return array<string, array<int, mixed>|string> */
    protected function rules(): array
    {
        return [
            'doctor_id' => ['required', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'document_type' => ['required', Rule::enum(DocumentType::class)],
            'document' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:2048'],
        ];
    }

    /** @return array<string, string> */
    protected function messages(): array
    {
        return [
            'doctor_id.required' => 'Seleziona il dottore a cui inviare il documento.',
            'doctor_id.exists' => 'Il dottore selezionato non è valido.',
            'title.required' => 'Inserisci un titolo per il documento.',
            'title.max' => 'Il titolo non può superare i 255 caratteri.',
            'document_type.required' => 'Seleziona il tipo di documento.',
            'document_type.enum' => 'Il tipo di documento selezionato non è valido.',
            'document.required' => 'Seleziona un documento da inviare.',
            'document.mimes' => 'Il documento deve essere un file JPG, PNG, WEBP o PDF.',
            'document.max' => 'Il documento non può superare i 2 MB.',
        ];
    }

    public function deleteDoc(int $doc_id): void
    {
        $doc = Document::findOrFail($doc_id);

        if ($doc) {
            if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                Storage::disk('public')->delete($doc->file_path);
            }

            $doc->delete();
        }

        session()->flash('success', 'Elemento eliminato con successo!');
    }

    public function sendDocument(): void
    {
        $this->validate();

        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $isAssociated = Appointment::query()
            ->where('guest_id', $authenticatedUser->id)
            ->where('doctor_id', $this->doctor_id)
            ->exists();

        if (! $isAssociated) {
            throw ValidationException::withMessages([
                'doctor_id' => 'Puoi inviare documenti solo a dottori associati al tuo profilo.',
            ]);
        }

        $filePath = $this->document->store('patients/documents', 'public');

        $document = $authenticatedUser->documents()->create([
            'uploaded_by' => $authenticatedUser->id,
            'recipient_id' => $this->doctor_id,
            'type' => DocumentType::from($this->document_type),
            'title' => $this->title,
            'file_path' => $filePath,
            'original_filename' => $this->document->getClientOriginalName(),
            'mime_type' => $this->document->getMimeType(),
            'file_size' => $this->document->getSize(),
            'is_verified' => false,
        ]);

        $doctor = User::findOrFail($this->doctor_id);
        $doctor->notify(new UserNotification(
            'document_added',
            'Il paziente ' . $authenticatedUser->name . ' ti ha inviato un nuovo documento.',
            documentId: $document->id,
        ));

        $this->reset(['doctor_id', 'title', 'document_type', 'document']);
        $this->document_type = 'other';
        $this->successMessage = 'Documento inviato correttamente.';
    }

    public function render(): View
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();

        $doctorIds = $authenticatedUser->appointmentsPatient()
            ->whereNotNull('doctor_id')
            ->distinct()
            ->pluck('doctor_id');

        $associatedDoctors = User::role('Doctor')
            ->whereKey($doctorIds)
            ->orderBy('name')
            ->get();

        $medicalRecordsAsPatient = $authenticatedUser->medicalRecordsAsPatient()
            ->whereHas('medicalRecordDocument', function ($query) use ($authenticatedUser): void {
                $query->where('uploaded_by', $authenticatedUser->id);
            })
            ->with([
                'medicalRecordDocument' => function ($query) use ($authenticatedUser): void {
                    $query->where('uploaded_by', $authenticatedUser->id);
                },
            ])
            ->latest()
            ->paginate(10);

        $sentDocuments = Document::query()
            ->where('documentable_type', User::class)
            ->where('documentable_id', $authenticatedUser->id)
            ->where('uploaded_by', $authenticatedUser->id)
            ->whereNotNull('recipient_id')
            ->with(['recipient' => fn($query) => $query->select(['id', 'name'])])
            ->latest()
            ->paginate(10);

        return view('livewire.pages.guest.upload-documents.index-upload-documents', compact(
            'associatedDoctors',
            'medicalRecordsAsPatient',
            'sentDocuments',
        ));
    }
}
