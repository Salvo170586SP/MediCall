<?php

use App\Models\DoctorDetail;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('un dottore può caricare un documento', function () {
    Storage::fake('local'); // finto disco, niente file reali scritti

    $doctor = User::factory()->create();
    $doctorDetail = DoctorDetail::factory()->create();

    $file = UploadedFile::fake()->create('cv.pdf', 500, 'application/pdf'); // file finto da 500kb

    // 1. Il file viene salvato fisicamente (sul disco finto)
    $path = $file->store('documents', 'local');

    // 2. Viene creata la riga nel database che punta a quel file
    $document = Document::factory()
        ->forDocumentable($doctorDetail)
        ->create([
            'uploaded_by'       => $doctor->id,
            'file_path'         => $path,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type'         => $file->getMimeType(),
            'file_size'         => $file->getSize(),
        ]);

    // 3. VERIFICO: il file esiste fisicamente sul disco finto
    Storage::disk('local')->assertExists($path);

    // 4. VERIFICO: la riga nel database è corretta
    expect($document->documentable_id)->toBe($doctorDetail->id);
    expect($document->uploaded_by)->toBe($doctor->id);
    expect($document->original_filename)->toBe('cv.pdf');
});