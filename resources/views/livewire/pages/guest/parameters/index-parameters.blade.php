<div>
    <div class="mx-auto max-w-7xl">
        <button wire:navigate href="/guest/dashboard"
            class="inline-flex items-center gap-1.5 mb-5 rounded-xl border border-slate-200 bg-zinc-100 px-4 py-3 text-sm font-bold text-slate-700 transition-colors hover:bg-sky-100 hover:text-sky-800">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
        </button>
    </div>

    <section
        class="mx-auto mb-5 max-w-7xl rounded-xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-8 lg:px-8">
        <div class="flex items-start gap-4">
            <div class="mt-1 flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125 1.125 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">I tuoi parametri</h1>
                <p class="mt-1 text-sm text-slate-600">Lo storico delle misurazioni registrate nel tuo profilo.</p>
            </div>
        </div>
    </section>
    <section class="mx-auto max-w-7xl overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div
            class="flex flex-col gap-2 border-b border-slate-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">Storico misurazioni</h2>
                <p class="text-sm text-slate-500">Le misurazioni più recenti sono mostrate per prime.</p>
            </div>

            <div class="flex flex-col gap-5">
                <button wire:navigate href="/guest/parameters/create"
                    class="inline-flex items-center gap-2 px-3 py-2 cursor-pointer rounded-xl bg-black hover:bg-gray-700 font-medium text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Aggiungi
                </button>
                <span
                    class="inline-flex w-fit items-center rounded-lg bg-sky-100 px-3 py-1 text-sm font-semibold text-sky-800">
                    {{ $parameters->total() }} {{ $parameters->total() === 1 ? 'misurazione' : 'misurazioni' }}
                </span>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3 m-6">
            <x-search-input inputClass="bg-white" id="type" model="type" label="Tipo" placeholder="Cerca per tipo" />
            <div>
                <label for="source" class="block text-xs font-semibold text-slate-900 mb-2">Sorgente</label>
                <select id="source" wire:model.live="source"
                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                    <option value="">Tutte le sorgenti</option>
                    <option value="manual">
                        Dispositivo Medico Manuale
                    </option>
                    <option value="device">
                        Dispositivo Medico Elettronico
                    </option>
                    <option value="wearable">
                        Wearable
                    </option>
                </select>
            </div>
        </div>
        @if ($parameters->isNotEmpty())
        <!-- Paginazione -->
        <div class="m-6" id="pagination-links-appointments" data-table="#appointments-table">
            {{ $parameters->links() }}
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Parametro</th>
                        <th class="px-5 py-3 font-semibold">Valore</th>
                        <th class="px-5 py-3 font-semibold">Data e ora</th>
                        <th class="px-5 py-3 font-semibold">Origine</th>
                        <th class="px-5 py-3 font-semibold">Note</th>
                        <th class="px-5 py-3 text-end font-semibold">Azioni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($parameters as $parameter)
                    <tr wire:key="guest-parameter-{{ $parameter->id }}" class="text-slate-700">
                        <td class="px-5 py-4 font-medium capitalize text-slate-950">
                            {{ str_replace('_', ' ', $parameter->type) }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4 font-semibold text-slate-950">
                            {{ $parameter->value }} {{ $parameter->unit }}
                        </td>
                        <td class="whitespace-nowrap px-5 py-4">
                            {{ $parameter->measured_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-4 capitalize">{{ $parameter->source }}</td>
                        <td class="max-w-xs px-5 py-4 text-slate-500">
                            @if($parameter->notes)
                            <x-modal title="Note" confirm-label="Note" wire:key="noteParameter-[{{$parameter->id}}]"
                                confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                <x-slot:trigger>
                                    <button title="note"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-100 hover:bg-sky-200 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                        </svg>
                                    </button>
                                </x-slot:trigger>

                                <x-slot:body>
                                    {{ $parameter->notes ?? '-'}}
                                </x-slot:body>

                                <x-slot:action>
                                </x-slot:action>
                            </x-modal>
                            @else
                            -
                            @endif
                        </td>
                        <td class="max-w-xs px-5 py-4 text-slate-500">
                            <div class="flex justify-end gap-3">
                                <button wire:navigate href="/guest/parameters/{{$parameter->id}}/edit" title="modifica"
                                    class="inline-flex items-center gap-1.5 p-2 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-medium rounded-xl transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    </svg>
                                </button>

                                <x-modal title="Elimina definitivamente?" confirm-label="Elimina"
                                    confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                    <x-slot:trigger>
                                        <button title="elimina"
                                            class="inline-flex items-center gap-1.5 p-2 bg-red-100 hover:bg-red-200 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </x-slot:trigger>

                                    <x-slot:body>
                                        <div class="w-[400px]">
                                            Questa operazione è irreversibile.
                                        </div>
                                    </x-slot:body>

                                    <x-slot:action>
                                        <button wire:click="deleteParameter({{$parameter->id}})"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-900 text-sm font-medium rounded-xl transition-colors">
                                            Elimina
                                        </button>
                                    </x-slot:action>

                                </x-modal>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-5 py-12 text-center text-sm text-slate-500">
            Non ci sono ancora parametri da mostrare.
        </div>
        @endif
    </section>
</div>