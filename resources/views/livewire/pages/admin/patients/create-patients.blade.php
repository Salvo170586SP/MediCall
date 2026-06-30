<div>
    <button wire:navigate href="{{ route('patients') }}"
        class="inline-flex items-center gap-1.5 px-4 py-2 mb-5 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-medium rounded-xl transition-colors">
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
                    {{ __('Crea Nuovo Paziente') }}
                </h2>
            </div>
            <form wire:submit="addPatient" class="space-y-6">

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

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-900 mb-2">
                            Email
                        </label>
                        <input type="email" wire:model.blur="email" id="email"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('email') border-rose-600 @enderror">
                        @error('email')
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
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('phone') border-rose-600 @enderror">
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
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('tax_id_code') border-rose-600 @enderror">
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
                        class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('medical_history') border-rose-600 @enderror">{{ old('bio') }}</textarea>
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
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('birth_city') border-rose-600 @enderror">
                        @error('birth_city')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-semibold text-slate-900 mb-2">
                            Data di nascita
                        </label>
                        <input type="date" wire:model.blur="birth_date" id="birth_date"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('birth_date') border-rose-600 @enderror">
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
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('blood_type') border-rose-600 @enderror">
                        @error('blood_type')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-semibold text-slate-900 mb-2">
                            Allergie
                        </label>
                        <input type="text" wire:model.blur="allergies" id="allergies"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('allergies') border-rose-600 @enderror">
                        @error('allergies')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
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
                        Crea
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>