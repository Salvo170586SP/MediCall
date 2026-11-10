<?php

namespace App\Livewire\Pages\Documents;

use App\Concerns\WithCustomPagination;
use App\Enums\DocumentType;
use App\Models\Appointment;
use App\Models\Document;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class IndexDocuments extends Component
{
    use WithCustomPagination, WithPagination;

    public string $search = '';

    public string $searchUser = '';

    public string $searchType = '';

    public ?int $selectedUserId = null;

    public function updatingSearchUser(): void
    {
        $this->resetPage();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSearchType(): void
    {
        $this->resetPage();
    }

    public function toggleUserSelection(int $userId): void
    {
        $this->selectedUserId = $this->selectedUserId === $userId ? null : $userId;
    }

    public function deleteDocument(int $document_id): void
    {
        $document = Document::findOrFail($document_id);

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();
    }

    public function deleteDoc(int $document_id): void
    {
        $documentQuery = Document::query()->whereKey($document_id);

        /** @var User|null $authenticatedUser */
        $authenticatedUser = Auth::user();

        if ($authenticatedUser?->hasRole('Doctor')) {
            $patientIds = Appointment::query()
                ->where('doctor_id', $authenticatedUser->getKey())
                ->distinct()
                ->pluck('guest_id');

            $documentQuery
                ->where('documentable_type', User::class)
                ->whereIn('documentable_id', $patientIds);
        }

        $document = $documentQuery->firstOrFail();

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->forceDelete();
    }

    public function render(): View
    {
        $usersQuery = User::query()
            ->select(['id', 'name']);

        $patientIds = collect();

        /** @var User|null $authenticatedUser */
        $authenticatedUser = Auth::user();

        if ($authenticatedUser?->hasRole('Doctor')) {
            $patientIds = Appointment::query()
                ->where('doctor_id', $authenticatedUser->getKey())
                ->distinct()
                ->pluck('guest_id');

            $usersQuery->whereKey($patientIds);
        }

        if ($this->searchUser) {
            $usersQuery->where('name', 'like', '%'.$this->searchUser.'%');
        }

        $users = $usersQuery->where(function ($query) {
            $query->whereHas('documents')
                ->orWhereHas('medicalRecordsAsPatient', function ($medicalRecordsQuery) {
                    $medicalRecordsQuery->whereHas('medicalRecordDocument');
                });
        })
            ->orderBy('name')
            ->paginate(10);

        $selectedDocuments = $this->selectedUserId
            ? Document::query()
                ->select([
                    'id',
                    'documentable_id',
                    'documentable_type',
                    'uploaded_by',
                    'type',
                    'title',
                    'original_filename',
                    'file_path',
                    'created_at',
                ])
                ->where(function ($query) {
                    $query->where(function ($userDocumentsQuery) {
                        $userDocumentsQuery->where('documentable_id', $this->selectedUserId)
                            ->where('documentable_type', User::class);
                    })->orWhere(function (Builder $medicalRecordDocumentsQuery) {
                        $medicalRecordDocumentsQuery->where('documentable_type', MedicalRecord::class)
                            ->whereIn('type', [
                                DocumentType::MedicalRecord,
                                DocumentType::Prescription,
                            ])
                            ->whereHasMorph('documentable', [MedicalRecord::class], function ($medicalRecordsQuery) {
                                $medicalRecordsQuery->where('guest_id', $this->selectedUserId);
                            });
                    });
                })
                ->when($authenticatedUser?->hasRole('Doctor'), function ($query) use ($patientIds) {
                    $query->where(function ($authorizedDocumentsQuery) use ($patientIds) {
                        $authorizedDocumentsQuery->where(function ($userDocumentsQuery) use ($patientIds) {
                            $userDocumentsQuery->where('documentable_type', User::class)
                                ->whereIn('documentable_id', $patientIds);
                        })->orWhereHasMorph('documentable', [MedicalRecord::class], function (Builder $medicalRecordsQuery) use ($patientIds) {
                            $medicalRecordsQuery->whereIn('guest_id', $patientIds);
                        });
                    });
                })
                ->with(['uploadedBy' => fn ($query) => $query->select(['id', 'name'])])
                ->when($this->search, function ($query) {
                    $query->where(function ($documentQuery) {
                        $documentQuery->where('title', 'like', '%'.$this->search.'%')
                            ->orWhere('original_filename', 'like', '%'.$this->search.'%');
                    });
                })
                ->when($this->searchType, function ($query) {
                    $query->where('type', 'like', '%'.$this->searchType.'%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate(8)
            : collect();

        return view('livewire.pages.documents.index-documents', compact('users', 'selectedDocuments'));
    }
}
