<div>
    <button wire:navigate href="{{ route('patients') }}"
        class="inline-flex items-center gap-1.5 px-4 py-3 mb-5 bg-zinc-100 hover:bg-zinc-200 text-gray-700 text-sm font-bold rounded-xl transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
        </svg>
        Indietro
    </button>

    <div class="bg-gray-50 overflow-hidden border border-gray-200 sm:rounded-xl">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-10">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Modifica Paziente') }}
                </h2>
            </div>
            <form wire:submit="editPatient({{$patient->id}})" class="space-y-6">

                <div class="grid grid-cols-2 gap-5">
                    <!-- Name e Surname -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-900 mb-2">
                            Nome e Cognome <span class="text-rose-600">*</span>
                        </label>
                        <input type="text" wire:model.blur="name" id="name"
                            class="w-full px-4 py-2 border border-gray-300 bg-white rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('name') border-rose-600 @enderror"
                            required>
                        @error('name')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="grid grid-cols-2 gap-5">
                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-900 mb-2">
                            Telefono
                        </label>
                        <input type="tel" wire:model.blur="phone" id="phone"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('phone') border-rose-600 @enderror">
                        @error('phone')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- tax_id_code -->
                    <div>
                        <label for="tax_id_code" class="block text-sm font-semibold text-slate-900 mb-2">
                            Cod Fiscale *
                        </label>
                        <input type="text" wire:model.blur="tax_id_code" id="tax_id_code"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('tax_id_code') border-rose-600 @enderror">
                        @error('tax_id_code')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- Description -->
                <div>
                    <label for="medical_history" class="block text-sm font-semibold text-slate-900 mb-2">
                        Storia Clinica
                    </label>
                    <textarea wire:model.blur="medical_history" id="medical_history" rows="4"
                        class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('medical_history') border-rose-600 @enderror">{{ old('bio') }}</textarea>
                    @error('medical_history')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City and Country -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="birth_city" class="block text-sm font-semibold text-slate-900 mb-2">
                            Città di nascita
                        </label>
                        <input type="text" wire:model.blur="birth_city" id="birth_city"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('birth_city') border-rose-600 @enderror">
                        @error('birth_city')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-semibold text-slate-900 mb-2">
                            Data di nascita
                        </label>
                        <input type="date" wire:model.blur="birth_date" id="birth_date"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('birth_date') border-rose-600 @enderror">
                        @error('birth_date')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="blood_type" class="block text-sm font-semibold text-slate-900 mb-2">
                            Gruppo sanguigno
                        </label>
                        <input type="text" wire:model.blur="blood_type" id="blood_type" required
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('blood_type') border-rose-600 @enderror">
                        @error('blood_type')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-semibold text-slate-900 mb-2">
                            Allergie
                        </label>
                        <input type="text" wire:model.blur="allergies" id="allergies"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('allergies') border-rose-600 @enderror">
                        @error('allergies')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                {{-- sezione caricamento file --}}
                <div class="flex space-y-5 pt-6 border-t border-gray-200">
                    <div class="w-80 flex flex-col">
                        <label for="identity_card" class="block text-sm font-semibold text-slate-900 mb-1">
                            Carica carta d'identità attuale
                        </label>
                        @if($patient->identityCard)
                        <a href="{{asset('storage/'.$patient->identityCard->file_path)}}" target="_blank" class="w-12 h-12 inline-flex justify-center items-center bg-white border border-gray-300 rounded-xl p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5 group-hover:size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM10.5 7.5v6m3-3h-6" />
                            </svg>
                        </a>
                        @else
                        -
                        @endif
                    </div>

                    <div class="w-80 flex flex-col">
                        <label for="identity_card" class="block text-sm font-semibold text-slate-900 mb-1">
                            Codice fiscale attuale
                        </label>
                        @if($patient->fiscalCode)
                        <a href="{{asset('storage/'.$patient->fiscalCode->file_path)}}" target="_blank" class="w-12 h-12 inline-flex justify-center items-center bg-white border border-gray-300 rounded-xl p-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607ZM10.5 7.5v6m3-3h-6" />
                            </svg>
                        </a>
                        @else
                        -
                        @endif
                    </div>

                </div>

                <div class="flex">
                    <div class="flex flex-col ">
                        <div class="w-full flex flex-col">
                            <label for="identity_card" class="block text-sm font-semibold text-slate-900 mb-1">
                                Carica carta d'identità
                            </label>
                        </div>

                        <div class="flex flex-col mt-3">
                            <input type="file" id="identity_card" wire:model="identity_card"
                                accept="image/png, image/jpeg, image/webp, image/pdf" class="block w-full text-sm text-gray-600 
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-xl file:border-0
                            file:text-sm file:font-semibold
                            file:transition-colors
                            file:bg-indigo-100 file:text-indigo-700
                            hover:file:bg-indigo-200">

                            <div wire:loading wire:target="identity_card" class="text-sm text-gray-500">
                                Caricamento in corso...
                            </div>

                            @error('identity_card')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col ">
                        <div class="w-full flex flex-col">
                            <label for="fiscal_code" class="block text-sm font-semibold text-slate-900 mb-1">
                                Carica codice fiscale
                            </label>
                        </div>

                        <div class="flex flex-col mt-3">
                            <input type="file" id="fiscal_code" wire:model="fiscal_code"
                                accept="image/png, image/jpeg, image/webp, image/pdf" class="block w-full text-sm text-gray-600 
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-xl file:border-0
                            file:transition-colors
                            file:text-sm file:font-semibold
                            file:bg-indigo-100 file:text-indigo-700
                            hover:file:bg-indigo-200">

                            <div wire:loading wire:target="fiscal_code" class="text-sm text-gray-500">
                                Caricamento in corso...
                            </div>

                            @error('fiscal_code')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>


                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button wire:navigate href="{{ route('patients') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Annulla
                    </button>
                    <button
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">
                        Modifica
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>