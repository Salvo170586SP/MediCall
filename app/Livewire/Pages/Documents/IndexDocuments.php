<?php

namespace App\Livewire\Pages\Documents;

use App\Concerns\WithCustomPagination;
use App\Models\Appointment;
use App\Models\Document;
use App\Models\User;
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

    public function render()
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

        $users = $usersQuery->whereHas('documents')
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
                ->where('documentable_id', $this->selectedUserId)
                ->where('documentable_type', User::class)
                ->when($authenticatedUser?->hasRole('Doctor'), function ($query) use ($patientIds) {
                    $query->whereIn('documentable_id', $patientIds);
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
