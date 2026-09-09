<div>
    <x-modal title="Aggiungi Referto o Ricetta" confirm-label="Referto o Ricetta" :close-on-click="false"
        reset-method="resetForm" close-on-event="medical-record-created"
        confirm-class="max-w-200 border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
        <x-slot:trigger>
            <button type="button" title="Aggiungi Referto o Ricetta"
                class="inline-flex items-center gap-1.5 rounded-lg bg-sky-100 px-4 py-3 text-sm font-bold text-sky-900 transition-colors hover:bg-sky-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Aggiungi Referto o Ricetta
            </button>
        </x-slot:trigger>

        <x-slot:body>
            <div class="mt-10 gap-5">
                <div class="w-[500px] space-y-3">
                    <div class="text-start">
                        <label class="mb-1 block text-sm font-medium text-slate-700">Data di Visita *</label>
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
                                        Carica file
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

                                    @error('file')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <select name="file_type" id="file_type" wire:model="file_type">
                                <option value="">Seleziona il tipo di file</option>
                                @foreach($file_types as $key => $value)
                                <option value="{{$value->value}}" wire:key="{{$value->value}}-{{$value->value}}">{{$key}}</option>
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
            <button type="button" wire:loading.attr="disabled" wire:click="save"
                class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-medium text-white hover:bg-sky-500 disabled:opacity-60">
                <span wire:loading.remove wire:target="save">Salva</span>
                <span wire:loading wire:target="save">Salvataggio...</span>
            </button>
        </x-slot:action>
    </x-modal>
</div>