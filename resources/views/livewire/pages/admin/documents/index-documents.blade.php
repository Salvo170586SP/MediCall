<div class="relative">

    <div class="w-full flex items-center justify-between mb-10">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archivio') }}
        </h2>
    </div>

    <div class="grid gap-4 lg:grid-cols-3 mb-6">
        <x-search-input inputClass="bg-gray-50" id="search" model="search"
            placeholder="Cerca per tipo o nome del file..." />
        <div>
            <label for="severity" class="block text-xs font-semibold text-slate-900 mb-2">Cerca per
                Tipo Documento</label>
            <select id="searchType" wire:model.live="searchType"
                class="w-full rounded-xl border border-slate-200 bg-gray-50 px-4 py-2 text-sm text-slate-800 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                <option value="">Tutti</option>
                <option value="identity_card">Carta d'Identità</option>
                <option value="tax_code">Codice fiscale</option>
                <option value="photo">Fotografia</option>
                <option value="medical_record">Referto medico</option>
                <option value="prescription">Ricetta medica</option>
                <option value="other">Altro</option>
            </select>
        </div>
    </div>

    <div class="m-6">
        {{$documents->links()}}
    </div>

    <div class="w-full rounded-xl overflow-hidden border border-slate-200">
        <table class="w-full h-full">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-100 text-[11px]">
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Documento di</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Tipo</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Nome del file</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Caricato da</th>
                    <th class="px-3 py-3 text-center font-semibold text-slate-900 uppercase tracking-wider">
                        Verificato</th>
                    <th class="px-3 py-3 text-center font-semibold text-slate-900 uppercase tracking-wider">
                        Note</th>
                    <th class="px-3 py-3 text-center font-semibold text-slate-900 uppercase tracking-wider">
                        Scarica</th>
                    <th class="px-3 py-3 text-right font-semibold text-slate-900 uppercase tracking-wider">
                        Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                <tr wire:key="doc-[{{$doc->id}}]" class="border-b border-slate-200 text-[14px]">
                    <td class="px-3 py-4 font-medium text-slate-900">{{ $doc->documentable->name ?? '-' }}</td>
                    <td class="px-3 py-4 font-medium text-slate-900">{{ $doc->title }}</td>
                    <td class="px-3 py-4 font-medium text-slate-900">{{ $doc->original_filename ?? '-' }}</td>
                    <td class="px-3 py-4 font-medium text-slate-900">{{ $doc->uploadedBy->name ?? '-' }}</td>
                    <td class="px-3 py-4">
                        <div class="w-full flex justify-center">
                            @if($doc->is_verified)
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6 text-red-700">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            @endif
                        </div>
                    </td>
                    <td class="px-3 py-4">
                        <div class="w-full flex justify-center">
                            @if(!$doc->notes)
                            <x-modal title="Note" confirm-label="notes"
                                confirm-class="border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-800">
                                <x-slot:trigger>
                                    <button title="Note"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-100 hover:bg-sky-200 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                        </svg>
                                    </button>
                                </x-slot:trigger>

                                <x-slot:body>

                                </x-slot:body>

                                <x-slot:action>
                                </x-slot:action>
                            </x-modal>
                            @else
                            -
                            @endif
                        </div>
                    </td>
                    <td class="px-3 py-4 font-medium">
                        <div class="text-center">
                            @if($doc->file_path)
                            <a title="scarica" download href="{{ asset('storage/'.$doc->file_path) }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-100 hover:bg-sky-200 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                            </a>
                            @else
                            -
                            @endif
                        </div>
                    </td>
                    <td class="px-3 py-4 text-right">
                        <div class="inline-flex items-center justify-end gap-2">
                            <button wire:navigate href="patients/details/{{$doc->documentable->id}}" title="dettagli"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-zinc-100 hover:bg-zinc-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                </svg>
                            </button>

                            <x-modal title="Elimina definitivamente?" confirm-label="Elimina"
                                confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                <x-slot:trigger>
                                    <button title="elimina"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-800 text-sm font-medium rounded-xl transition-colors">
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
                                    <button wire:click="deleteDoc({{$doc->id}})"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-900 text-sm font-medium rounded-xl transition-colors">
                                        Elimina
                                    </button>
                                </x-slot:action>

                            </x-modal>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-slate-500">
                        <p>Nessun documento in archivio</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="fixed bottom-0 right-5">
        <x-flash-message type="success" />
        <x-flash-message type="error" />
    </div>
</div>