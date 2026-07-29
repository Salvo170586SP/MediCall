<?php

namespace App\Livewire\Pages\Admin\Documents;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class IndexDocuments extends Component
{
    use WithPagination;
    public $search = '';
    public $searchType = '';

    public function deleteDoc(int $document_id)
    {
        $document = Document::findOrFail($document_id);

        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->forceDelete();
    }

    public function render()
    {
        $documentsQuery = Document::query();

        if ($this->search) {
            $documentsQuery
                ->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('original_filename', 'like', '%' . $this->search . '%');
        }

        if ($this->searchType) {
            $documentsQuery
                ->where('type', 'like', '%' . $this->searchType . '%');
        }


        $documents =  $documentsQuery->paginate(10);

        return view('livewire.pages.admin.documents.index-documents', compact('documents'));
    }
}
