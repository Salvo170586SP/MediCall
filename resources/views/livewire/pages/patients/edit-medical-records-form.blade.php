<div>
    <x-modal title="Modifica cartella clinica" confirm-label="Modifica cartella clinica" :close-on-click="false"
        reset-method="resetForm" close-on-event="medical-record-edited">
        <x-slot:trigger>
            <button type="button" title="Modifica cartella clinica"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-100 hover:bg-sky-200 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                </svg>
            </button>
        </x-slot:trigger>

        <x-slot:body>
            <div class="mt-10 gap-5">
                <div class="w-[500px] space-y-3">
                    <div class="text-start">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Data di visita</label>
                        <input type="date" wire:model="visit_date"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        @error('visit_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Diagnosi *</label>
                        <input type="text" wire:model="diagnosis"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        @error('diagnosis') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Prescrizioni</label>
                        <textarea wire:model="prescription"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('prescription') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Consigli</label>
                        <textarea wire:model="instructions"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('instructions') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- sezione caricamento file --}}
                    <div class="text-start">
                        <label class="mb-1 block text-sm font-medium text-slate-700">File</label>

                        {{-- file medical_record --}}
                        <div class="flex-col space-y-3">
                            <div class="flex flex-col">
                                <div class="w-full flex flex-col">
                                    <label for="file" class="block text-sm font-semibold text-slate-900 mb-1">
                                        Sostituisci file
                                    </label>
                                </div>

                                <div class="flex flex-col">
                                    <input type="file" id="file" wire:model="file"
                                        accept="image/png, image/jpeg, image/webp, application/pdf" class="block w-full text-sm text-gray-600 
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-xl file:border-0
                            file:text-sm file:font-semibold
                            file:transition-colors
                            file:bg-indigo-100 file:text-indigo-700
                            hover:file:bg-indigo-200">

                                    <div wire:loading wire:target="file" class="text-sm text-gray-500">
                                        Caricamento in corso...
                                    </div>

                                    @if($medicalRecord->medicalRecordDocument && !$remove_existing_file)
                                    <p class="mt-2 text-sm text-slate-600">
                                        File attuale: {{ $medicalRecord->medicalRecordDocument->original_filename }}
                                    </p>
                                    <label class="mt-2 inline-flex items-center gap-2 text-sm text-red-700">
                                        <input type="checkbox" wire:model="remove_existing_file"
                                            class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                                        Rimuovi il file attuale
                                    </label>
                                    @elseif($medicalRecord->medicalRecordDocument)
                                    <p class="mt-2 text-sm text-red-700">Il file attuale verrà rimosso.</p>
                                    @endif

                                    @error('file')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <select name="file_type" id="file_type" wire:model="file_type">
                                <option value="">Seleziona il tipo di file</option>
                                @foreach($file_types as $key => $value)
                                <option value="{{$value->value}}" wire:key="{{$value->value}}-{{$value->value}}">
                                    {{$key}}</option>
                                @endforeach
                            </select>
                            @error('file_type')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>


            </div>
        </x-slot:body>

        <x-slot:action>
            <button type="button" wire:loading.attr="disabled" wire:click="edit"
                class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-medium text-white hover:bg-sky-500 disabled:opacity-60">
                <span wire:loading.remove wire:target="edit">Salva</span>
                <span wire:loading wire:target="edit">Modifica in corso...</span>
            </button>
        </x-slot:action>
    </x-modal>
</div>