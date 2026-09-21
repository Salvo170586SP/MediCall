<div>
    @role('Admin')
    <button wire:navigate href="/doctors"
        class="inline-flex items-center gap-1.5 px-4 py-3 mb-5 bg-zinc-100 hover:bg-zinc-200 text-gray-700 text-sm font-bold rounded-xl transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
        </svg>
        Indietro
    </button>
    @endrole

    @role('Doctor')
    <button wire:navigate href="/doctor/profile"
        class="inline-flex items-center gap-1.5 px-4 py-3 mb-5 bg-zinc-100 hover:bg-zinc-200 text-gray-700 text-sm font-bold rounded-xl transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
        </svg>
        Indietro
    </button>
    @endrole




    <div class="bg-gray-50 overflow-hidden border sm:rounded-xl">
        <div class="p-6 text-gray-900">
            <div class="flex justify-between items-center mb-10">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Modifica Dottore') }}
                </h2>
            </div>
            <form wire:submit="editDoctor" class="space-y-6">

                @if (session('success'))
                <div class="rounded-xl bg-green-50 p-4 border border-green-200">
                    <p class="text-sm text-green-800">{{ session('success') }}</p>
                </div>
                @endif

                @if (session('error'))
                <div class="rounded-xl bg-red-50 p-4 border border-red-200">
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
                @endif

                <div class="flex gap-5">
                    <div class="flex flex-col">
                        <div class="w-full flex flex-col">
                            <label for="photo" class="block text-sm font-semibold text-slate-900 mb-1">
                                Foto del dottore
                            </label>

                            @if ($photo)
                            <figure class="w-78 h-48">
                                <img src="{{ $photo->temporaryUrl() }}" alt="Anteprima foto"
                                    class="w-full h-full rounded-xl object-cover border border-gray-300">
                            </figure>
                            @elseif($doctor->photo?->file_path)
                            <figure class="w-78 h-48">
                                <img src="{{ asset('storage/'.$doctor->photo?->file_path) }}" alt="Anteprima foto"
                                    class="w-full h-full rounded-xl object-cover border border-gray-300">
                            </figure>
                            @else
                            <div
                                class="w-78  h-48 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            @endif
                        </div>

                        <div class="flex flex-col mt-3">
                            <input type="file" id="photo" wire:model="photo" accept="image/png, image/jpeg, image/webp"
                                class="block w-full text-sm text-gray-600 
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-xl file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100">

                            <div wire:loading wire:target="photo" class="text-sm text-gray-500">
                                Caricamento in corso...
                            </div>

                            @error('photo')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="w-150 space-y-5">
                        <!-- Name e Surname -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-900 mb-2">
                                Nome e Cognome <span class="text-rose-600">*</span>
                            </label>
                            <input type="text" name="name" id="name" wire:model.blur="name"
                                class="w-full px-4 py-2 border border-gray-300 bg-white rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('name') border-rose-600 @enderror"
                                required>
                            @error('name')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-900 mb-2">
                                Telefono
                            </label>
                            <input type="tel" name="phone" id="phone" wire:model.blur="phone"
                                class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('phone') border-rose-600 @enderror">
                            @error('phone')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-semibold text-slate-900 mb-2">
                                Città
                            </label>
                            <input type="text" name="city" id="city" wire:model.blur="city"
                                class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('city') border-rose-600 @enderror">
                            @error('city')
                            <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                <hr>

                <!-- Description -->
                <div>
                    <label for="bio" class="block text-sm font-semibold text-slate-900 mb-2">
                        Biografia
                    </label>
                    <textarea name="bio" id="bio" rows="4" wire:model.blur="bio"
                        class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('bio') border-rose-600 @enderror"></textarea>
                    @error('bio')
                    <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="specialty" class="block text-sm font-semibold text-slate-900 mb-2">
                            Specializzazione *
                        </label>
                        <input type="text" name="specialty" id="specialty" wire:model.blur="specialty"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('specialty') border-rose-600 @enderror">
                        @error('specialty')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sub_specialty" class="block text-sm font-semibold text-slate-900 mb-2">
                            Sub Specializzazione
                        </label>
                        <input type="text" name="sub_specialty" id="sub_specialty" wire:model.blur="sub_specialty"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('sub_specialty') border-rose-600 @enderror">
                        @error('sub_specialty')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Oraro di Apertura e Chiusura -->
                    <div>
                        <label for="available_hours" class="block text-sm font-semibold text-slate-900 mb-2">
                            Orari di apertura e chiusura *
                        </label>

                        @php
                        $days = [
                        'mon' => 'Lunedì',
                        'tue' => 'Martedì',
                        'wed' => 'Mercoledì',
                        'thu' => 'Giovedì',
                        'fri' => 'Venerdì',
                        'sat' => 'Sabato',
                        'sun' => 'Domenica',
                        ];
                        @endphp

                        <div class="space-y-2 border rounded-xl p-3 border bg-white w-[500px]">
                            @foreach ($days as $key => $label)
                            <div class="flex items-center justify-between gap-3">

                                <span class="w-24 text-sm text-slate-700">{{ $label }}</span>

                                <div>
                                    <input type="time" wire:model.blur="available_hours.{{ $key }}.0"
                                        class="px-3 py-1.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-slate-500 @error('available_hours.'.$key.'.0') border-rose-600 @enderror">

                                    <span class="text-sm text-slate-400">→</span>

                                    <input type="time" wire:model.blur="available_hours.{{ $key }}.1"
                                        class="px-3 py-1.5 bg-gray-50 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-slate-500 @error('available_hours.'.$key.'.1') border-rose-600 @enderror">

                                </div>
                                @error('available_hours.'.$key.'.0')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                                @error('available_hours.'.$key.'.1')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                            @endforeach
                        </div>

                        @error('available_hours')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slot di Prenizaione -->
                    <div>
                        <label for="slot_duration" class="block text-sm font-semibold text-slate-900 mb-2">
                            Slot visita *
                        </label>
                        <select name="slot_duration" wire:model="slot_duration"
                            class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-slate-500 focus:border-transparent @error('slot_duration') border-rose-600 @enderror">
                            <option value="15">15 minuti</option>
                            <option value="20">20 minuti</option>
                            <option value="30" selected>30 minuti</option>
                            <option value="45">45 minuti</option>
                            <option value="60">1 ora</option>
                        </select>
                        @error('slot_duration')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Is Active -->
                <div class="flex items-center space-x-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" wire:model="is_active"
                        class="rounded-xl border-gray-300">

                    <label for="is_active" class="text-sm font-semibold text-slate-900">
                        Disponibile
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    @role('Doctor')
                    <button wire:navigate href="/doctor/profile"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Annulla
                    </button>
                    @endrole
                    @role('Admin')
                    <button wire:navigate href="/doctors"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                        Annulla
                    </button>
                    @endrole

                    <button wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">
                        <span wire:loading.remove>Modifica</span>
                        <span wire:loading>
                            <svg class="inline h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>