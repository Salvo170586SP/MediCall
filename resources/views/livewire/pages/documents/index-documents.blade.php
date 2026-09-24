<div class="relative">

    <div class="w-full flex items-center justify-between mb-10">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archivio') }}
        </h2>
    </div>

    <div class="w-full h-full flex gap-5">
        <div class="w-[420px] min-w-[420px]">
            <x-search-input inputClass="bg-gray-50 mt-4" id="searchUser" model="searchUser" label=""
                placeholder="{{ __('Cerca per nome') }}" />

            <div class="rounded-xl overflow-hidden border border-slate-200 mt-3">
                <table class="w-full h-full">
                    <thead>
                        <tr class="border-b border-zinc-200 bg-zinc-100 text-[11px]">
                            <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                {{ __('Archivio di') }}
                            </th>
                            <th class="px-3 py-3 text-right font-semibold text-slate-900 uppercase tracking-wider">
                                {{ __('Azioni') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr wire:key="user-[{{ $user->id }}]" class="text-[14px]">
                            <td class="px-3 py-2 font-medium text-slate-900">
                                <button type="button" wire:click="toggleUserSelection({{ $user->id }})"
                                    class="w-full text-left px-3 py-2 rounded-xl capitalize cursor-pointer transition-colors {{ $selectedUserId === $user->id ? 'bg-sky-100 text-sky-800' : 'bg-zinc-100 hover:bg-zinc-200' }}">
                                    {{ $user->name ?? '-' }}
                                </button>
                            </td>
                            <td class="px-3 py-2 text-right">
                                <div class="inline-flex items-center justify-end gap-2">
                                    <button wire:navigate href="patients/details/{{ $user->id }}"
                                        title="{{ __('Dettagli') }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-zinc-100 hover:bg-zinc-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-6 py-8 italic text-center text-slate-400">
                                <p>{{ __('Nessun utente disponibile') }}</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                {{ $users->links() }}
            </div>
        </div>

        <div class="flex-1">
            @if($selectedUserId)
            <div class="grid gap-4 lg:grid-cols-3 mb-3">
                <x-search-input inputClass="bg-gray-50" id="search" model="search" label="{{ __('Cerca') }}"
                    placeholder="{{ __('Cerca per tipo o nome del file...') }}" />
                <div>
                    <label for="severity" class="block text-xs font-semibold text-slate-900 mb-2">{{ __('Cerca per
                        tipo') }}</label>
                    <select id="searchType" wire:model.live="searchType"
                        class="w-full rounded-xl border border-slate-200 bg-gray-50 px-4 py-2 text-sm text-slate-800 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        <option value="">{{ __('Tutti') }}</option>
                        <option value="identity_card">{{ __('Carta d\'identità') }}</option>
                        <option value="tax_code">{{ __('Codice fiscale') }}</option>
                        <option value="photo">{{ __('Fotografia') }}</option>
                        <option value="medical_record">{{ __('Referto medico') }}</option>
                        <option value="prescription">{{ __('Ricetta medica') }}</option>
                        <option value="other">{{ __('Altro') }}</option>
                    </select>
                </div>
            </div>
            <div class="rounded-xl overflow-hidden border border-slate-200 bg-white">
                @if($selectedDocuments->count())
                <table class="w-full h-full">
                    <thead>
                        <tr class="border-b border-zinc-200 bg-zinc-100 text-[11px]">
                            <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                {{ __('Nome file') }}
                            </th>
                            <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                {{ __('di') }}
                            </th>
                            <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                {{ __('Caricato da') }}
                            </th>
                            <th class="px-3 py-3 text-end font-semibold text-slate-900 uppercase tracking-wider">
                                {{ __('Azioni') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($selectedDocuments as $document)
                        <tr wire:key="selected-doc-[{{ $document->id }}]" class="text-[14px]">
                            <td class="px-3 py-4 font-medium text-slate-900">
                                {{ $document->original_filename ?? $document->title }}
                            </td>
                            <td class="px-3 py-4 text-slate-600">
                                {{ $document->title ?? '-' }}
                            </td>
                            <td class="px-3 py-4 text-slate-900">
                                {{ $document->uploadedBy?->name ?? '-' }}
                            </td>
                            <td class="px-3 py-4 text-slate-900">
                                <div class="w-full flex justify-end space-x-3">
                                    <a href="{{asset('storage/'.$document->file_path)}}" target="_blank"
                                        title="{{ __('Guarda') }}"
                                        class="w-8 h-8 rounded-xl inline-flex justify-center items-center bg-sky-100 hover:bg-sky-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>

                                    <a href="{{asset('storage/'.$document->file_path)}}" download
                                        title="{{ __('Scarica') }}"
                                        class="w-8 h-8 rounded-xl inline-flex justify-center items-center bg-sky-100 hover:bg-sky-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </a>

                                    <x-modal title="{{ __('Elimina definitivamente?') }}" confirm-label="elimina"
                                        wire:key="deleteDocument-[{{$document->id}}]"
                                        confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                        <x-slot:trigger>
                                            <button title="{{ __('Elimina') }}"
                                                class="w-8 h-8 cursor-pointer rounded-xl inline-flex justify-center items-center bg-red-100 hover:bg-red-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </x-slot:trigger>

                                        <x-slot:body>
                                            {{ __('Questa operazione è irreversibile') }}
                                        </x-slot:body>

                                        <x-slot:action>
                                            <button wire:click="deleteDocument({{$document->id}})"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-300 bg-red-100 hover:bg-red-200 text-red-900 text-sm font-medium rounded-xl transition-colors">
                                                {{ __('Elimina') }}
                                            </button>
                                        </x-slot:action>
                                    </x-modal>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-3">
                    {{ $selectedDocuments->links() }}
                </div>
                @else
                <div class="px-6 py-5 text-center text-slate-500">
                    <p>{{ __('Nessun documento disponibile') }}</p>
                </div>
                @endif
            </div>
            @else
            <div
                class="rounded-xl border border-dashed border-slate-300 bg-zinc-50 px-6 py-12 mt-6 font-medium text-center text-zinc-400 flex flex-col items-center justify-center space-y-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <p>{{ __('Clicca su un utente per vedere i documenti') }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="fixed bottom-0 right-5">
        <x-flash-message type="success" />
        <x-flash-message type="error" />
    </div>
</div>