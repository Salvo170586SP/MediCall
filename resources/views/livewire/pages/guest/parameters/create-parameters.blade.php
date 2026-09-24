<div>
    <div class="mx-auto max-w-7xl">
        <button wire:navigate href="{{ route('guest.parameters') }}"
            class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-zinc-100 px-4 py-3 text-sm font-bold text-slate-700 transition-colors hover:bg-sky-100 hover:text-sky-800 mb-5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
        </button>

        <section
            class="mb-6 rounded-xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-8 lg:px-8">
            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-700">{{ __('Nuova misurazione') }}</p>
            <h1 class="mt-2 text-2xl font-bold text-slate-950">{{ __('Registra i tuoi parametri') }}</h1>
            <p class="mt-2 max-w-2xl text-sm text-slate-600">{{ __('Seleziona uno o più parametri e inserisci il valore rilevato') }}</p>
        </section>

        <form wire:submit="save" class="space-y-6">
            <section class="rounded-xl border border-slate-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-slate-950">{{ __('Parametri da registrare') }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ __('L\'unità di misura viene associata automaticamente') }}</p>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @foreach ($availableParameters as $type => $parameter)
                    <div wire:key="parameter-{{ $type }}" class="rounded-xl border border-slate-200 p-4">
                        <label class="flex cursor-pointer items-center gap-3">
                            <input type="checkbox" wire:model.live="selectedParameters.{{ $type }}" value="1"
                                class="size-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="font-medium text-slate-900">{{ __($parameter['label']) }}</span>
                        </label>

                        @if (isset($selectedParameters[$type]) && ($selectedParameters[$type] === true ||
                        $selectedParameters[$type] === '1'))
                        <div class="mt-4 flex items-end gap-3">
                            <div class="min-w-0 flex-1">
                                <label for="value-{{ $type }}"
                                    class="mb-2 block text-xs font-semibold text-slate-700">{{ __('Valore') }}</label>
                                <input id="value-{{ $type }}" type="text" inputmode="decimal"
                                    wire:model="values.{{ $type }}"
                                    placeholder="Es. {{ $type === 'weight' ? '70' : ($type === 'oxygen_saturation' ? '97.55' : ($type === 'heart_rate' ? '65.56' : ($type === 'body_temperature' ? '36.28' : '132.65'))) }}"
                                    class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            </div>
                            <span class="pb-2 text-sm font-semibold text-slate-500">{{ $parameter['unit'] }}</span>
                        </div>
                        @error("values.$type") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        <div class="mt-4">
                            <label for="measured-at-{{ $type }}"
                                class="mb-2 block text-xs font-semibold text-slate-700">{{ __('Data e ora') }}</label>
                            <input id="measured-at-{{ $type }}" type="datetime-local"
                                wire:model="measuredAt.{{ $type }}"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                            @error("measuredAt.$type") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="mt-4">
                            <label for="source-{{ $type }}"
                                class="mb-2 block text-xs font-semibold text-slate-700">{{ __('Origine') }}</label>
                            <select id="source-{{ $type }}" wire:model="source.{{ $type }}"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                                <option value="manual">{{ __('Manuale') }}</option>
                                <option value="wearable">{{ __('Wearable') }}</option>
                                <option value="iot">{{ __('IoT') }}</option>
                                <option value="device">{{ __('Device') }}</option>
                            </select>
                            @error("source.$type") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div class="mt-4">
                            <label for="notes-{{ $type }}" class="mb-2 block text-xs font-semibold text-slate-700">{{ __('Note') }}
                                <span class="font-normal text-slate-500"> ({{ __('facoltativo') }})</span></label>
                            <textarea id="notes-{{ $type }}" wire:model="notes.{{ $type }}" rows="3" maxlength="1000"
                                class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-900 focus:border-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                                placeholder="{{ __('Dettagli sulla misurazione') }}"></textarea>
                            @error("notes.$type") <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @error('selectedParameters')<p class="mt-3 text-sm text-red-600">{{ $message }}</p> @enderror
            </section>

            <div class="flex justify-end">
                <button type="submit" wire:loading.attr="disabled"
                    class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-emerald-700 disabled:cursor-wait disabled:opacity-60">
                    <span wire:loading.remove>{{ __('Salva') }}</span>
                    <span wire:loading>{{ __('Salvataggio...') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>