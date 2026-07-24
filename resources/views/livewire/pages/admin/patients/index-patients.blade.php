<div class="relative">

    <div class="w-full flex items-center justify-between mb-10">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pazienti') }}
        </h2>
        <button wire:navigate href="{{route('patients.create') }}"
            class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-50 border border-gray-200 hover:bg-slate-100 text-gray-700 font-medium rounded-xl transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Aggiungi
        </button>
    </div>

    <div class="grid gap-4 lg:grid-cols-3 mb-6">
        <div>
            <x-search-input inputClass="bg-gray-50" id="search" model="search"
                placeholder="Cerca per nome o email..." />
        </div>
    </div>

    <div class="m-6">
        {{$users_patients->links()}}
    </div>

    <div class="w-full bg-gray-50 rounded-xl overflow-hidden border border-slate-200">
        <table class="w-full h-full">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-100 text-[11px]">
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Id</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Nome e Cognome</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Email</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Telefono</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Data di nascita</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Città di nascita</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Cod.fiscale</th>
                    <th class="px-3 py-3 text-center font-semibold text-slate-900 uppercase tracking-wider">
                        Medici</th>
                    <th class="px-3 py-3 text-right font-semibold text-slate-900 uppercase tracking-wider">
                        Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users_patients as $user_patient)
                <tr wire:key="patients-[{{$user_patient->id}}]"
                    class="border-b border-slate-200 font-medium text-[14px]">
                    <td class="px-3 py-4 font-medium text-left text-slate-900">
                        #{{$user_patient->guestDetail->code_item ??
                        null}}</td>
                    <td class="px-3 py-4 text-left text-slate-900">{{ $user_patient->name }}</td>
                    <td class="px-3 py-4 ext-slate-600 text-left">{{ $user_patient->email }}</td>
                    <td class="px-3 py-4 text-slate-900 text-left">{{ $user_patient->guestDetail->phone ?? '-' }}</td>
                    <td class="px-3 py-4 text-slate-900 text-left">{{ 
                        $user_patient->guestDetail?->formatDate($user_patient->guestDetail?->birth_date) ?? '-' }}</td>
                    <td class="px-3 py-4 text-slate-900 text-left">{{ $user_patient->guestDetail->birth_city ?? '-' }}
                    </td>
                    <td class="px-3 py-4 text-slate-900 text-left">{{ $user_patient->guestDetail->tax_id_code ?? '-' }}
                    </td>
                    <td class="px-3 py-4 text-slate-900 text-center"> <span
                            class="inline-flex items-center justify-center text-center rounded-full w-[25px] h-[25px] bg-blue-100  }}">
                            {{-- {{ $user_patient->doctorsPatients()->count() }} --}} 0
                        </span>
                    </td>
                    <td class="px-3 py-4 text-right">
                        <div class="inline-flex items-center justify-end gap-2">
                            <button wire:navigate href="patients/details/{{$user_patient->id}}" title="dettagli"
                                class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                </svg>
                            </button>
                            <button wire:navigate href="patients/edit/{{$user_patient->id}}" title="modifica"
                                class="inline-flex items-center gap-1.5 px-4 py-2 border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-800 text-sm font-medium rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                </svg>
                            </button>

                            <x-modal title="Elimina definitivamente?" confirm-label="Elimina"
                                confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                <x-slot:trigger>
                                    <button title="elimina"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </x-slot:trigger>

                                <x-slot:body>
                                    Questa operazione è irreversibile.
                                </x-slot:body>

                                <x-slot:action>
                                    <button wire:click="deletePatient({{$user_patient->id}})"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-300 bg-red-100 hover:bg-red-200 text-red-900 text-sm font-medium rounded-xl transition-colors">
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
                        <p>Nessun paziente disponibile.</p>
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