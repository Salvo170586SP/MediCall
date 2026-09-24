<div>
    <x-modal title="{{ __('Modifica Referto o Ricetta') }}" :close-on-click="false" reset-method="resetForm"
        close-on-event="medical-record-edited">
        <x-slot:trigger>
            <button type="button" title="{{ __('Modifica Referto o Ricetta') }}"
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
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('Data di visita') }}</label>
                        <input type="date" wire:model="visit_date"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        @error('visit_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start">
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('Diagnosi') }} *</label>
                        <input type="text" wire:model="diagnosis"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        @error('diagnosis') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start">
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('Prescrizioni') }}</label>
                        <textarea wire:model="prescription"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('prescription') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="text-start">
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('Consigli') }}</label>
                        <textarea wire:model="instructions"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('instructions') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- sezione caricamento file --}}
                    <div class="text-start">
                        <label class="mb-1 block text-sm font-medium text-slate-700">{{ __('File') }}</label>

                        {{-- file medical_record --}}
                        <div class="flex-col space-y-3">
                            <div class="flex flex-col">
                                <div class="w-full flex flex-col items-center mb-2">
                                    <label for="file" class="block text-sm font-semibold text-slate-900 mb-1">
                                        {{ __('Sostituisci file') }}
                                    </label>
                                    <span class="font-semibold text-xs">
                                        *{{ __('Se carichi il file, seleziona prima il tipo e poi il file') }}
                                    </span>
                                </div>

                                <div class="flex flex-col">

                                    <select name="file_type" id="file_type" wire:model="file_type"
                                        class="rounded-lg border py-1">
                                        <option value="">- {{ __('Seleziona il tipo di file') }} -</option>
                                        @foreach($file_types as $key => $value)
                                        <option value="{{$value->value}}"
                                            wire:key="{{$value->value}}-{{$value->value}}">
                                            {{ __($key) }}</option>
                                        @endforeach
                                    </select>
                                    @error('file_type')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror

                                    <label for="filemd-{{ $medicalRecord->id }}"
                                        class="inline-flex w-full cursor-pointer my-3 items-center justify-center rounded-xl bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-800 hover:bg-indigo-100">
                                        {{ __('Seleziona') }}
                                        <input type="file" id="filemd-{{ $medicalRecord->id }}" wire:model="file"
                                            accept="image/png, image/jpeg, image/webp, application/pdf"
                                            class="hidden" />
                                    </label>

                                    <div wire:loading wire:target="file" class="text-sm text-gray-500">
                                        {{ __('Caricamento in corso...') }}
                                    </div>

                                    @if($medicalRecord->medicalRecordDocument && !$remove_existing_file)
                                    <p class="mt-2 text-sm text-slate-600">
                                        {{ __('File attuale') }}: {{
                                        $medicalRecord->medicalRecordDocument->original_filename }}
                                    </p>
                                    <label class="mt-2 inline-flex items-center gap-2 text-sm text-red-700">
                                        <input type="checkbox" wire:model="remove_existing_file"
                                            class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                                        {{ __('Rimuovi file attuale') }}
                                    </label>
                                    @elseif($medicalRecord->medicalRecordDocument)
                                    <p class="mt-2 text-sm text-red-700">{{ __('Il file attuale verrà rimosso') }}</p>
                                    @endif

                                    @error('file')
                                    <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                        </div>
                    </div>
                </div>


            </div>
        </x-slot:body>

        <x-slot:action>
            <button type="button" wire:loading.attr="disabled" wire:click="edit"
                class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-medium text-white hover:bg-sky-500 disabled:opacity-60">
                <span wire:loading.remove wire:target="edit">{{ __('Modifica') }}</span>
                <span wire:loading wire:target="edit">{{ __('Salvataggio...') }}</span>
            </button>
        </x-slot:action>
    </x-modal>
</div>