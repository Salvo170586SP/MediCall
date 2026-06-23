<div>

    <div class="w-full flex items-center justify-between mb-10">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dottori') }}
        </h2>
        <button wire:navigate href="{{route('doctors.create') }}"
            class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-50 border border-gray-200 hover:bg-slate-100 text-gray-700 font-medium rounded-xl transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            Aggiungi
        </button>
    </div>

    {{-- <form method="get" action="{{ route('doctors.searchItem') }}" class="grid gap-4 lg:grid-cols-3 mb-6"
        @submit.prevent="handleFilterSubmit($event)">
        <div>
            <label for="name" class="block text-xs font-semibold text-slate-900 mb-2">Cerca</label>
            <input id="name" name="name" type="text" value="{{ request('name') }}" placeholder="Es: Mario Rossi"
                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
        </div>
        <div class="flex items-end gap-3">
            <button type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">Filtra</button>
            <a href="{{ route('doctors.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Azzera</a>
        </div>
    </form> --}}

    <div class="m-6">
        {{$users_doctor->links()}}
    </div>
    <div class="w-full bg-white rounded-xl overflow-hidden border border-slate-200">
        <table class="w-full h-full">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50 text-[11px]">
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        ID</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Nome e Cognome</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Email</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        Telefono</th>
                    <th class="px-3 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                        licenza</th>
                    <th class="px-3 py-3 text-center font-semibold text-slate-900 uppercase tracking-wider">
                        Specializzazione</th>
                    <th class="px-3 py-3 text-center font-semibold text-slate-900 uppercase tracking-wider">
                        Disponibilità</th>
                    <th class="px-3 py-3 text-center font-semibold text-slate-900 uppercase tracking-wider">
                        Pazienti</th>
                    <th class="px-3 py-3 text-right font-semibold text-slate-900 uppercase tracking-wider">
                        Azioni</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users_doctor as $user_doctor)
                <tr wire:key="doctors-[{{$user_doctor->id}}]"
                    class="border-b border-slate-200 hover:bg-slate-50 transition text-[14px]">
                    <td class="px-3 py-4 font-medium  text-slate-900">#D-{{$user_doctor->doctorDetail->code_item }}</td>
                    <td class="px-3 py-4 font-medium text-slate-900">{{ $user_doctor->name }}</td>
                    <td class="px-3 py-4 font-medium text-slate-600">{{ $user_doctor->email }}</td>
                    <td class="px-3 py-4 text-slate-900">{{ $user_doctor->doctorDetail->phone ?? '-' }}
                    </td>
                    <td class="px-3 py-4 text-slate-900">{{ $user_doctor->doctorDetail->license_number
                        ?? '-' }}</td>
                    <td class="px-3 py-4">
                        <span
                            class="w-full inline-flex items-center justify-center w-[200px] rounded-full px-3 py-1 bg-gray-200 text-gray-800">
                            {{ $user_doctor->doctorDetail->specialty ?? '-' }}
                        </span>
                    </td>
                    <td class="px-3 py-4">
                        <span
                            class="w-full inline-block text-center rounded-full max-w-[150px] px-5 py-1 {{ $user_doctor->doctorDetail && $user_doctor->doctorDetail->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $user_doctor->doctorDetail && $user_doctor->doctorDetail->is_active
                            ? 'Disponibile'
                            : 'Non disponibile' }}
                        </span>
                    </td>
                    <td class="px-3 py-4">
                        <span
                            class="w-full inline-flex items-center justify-center text-center rounded-full w-[25px] h-[25px] bg-blue-100  }}">
                            {{ $user_doctor->patientsDoctor ? $user_doctor->patientsDoctor()->count() : '0' }}  
                        </span>
                    </td>
                    <td class="px-3 py-4 text-right">
                        <div class="inline-flex items-center justify-end gap-2">
                            <button wire:navigate href="doctors/details/{{$user_doctor->id}}" title="dettagli"
                                class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 1 6.338 0Z" />
                                </svg>
                            </button>
                            <button wire:navigate href="doctors/edit/{{$user_doctor->id}}" title="modifica"
                                class="inline-flex items-center gap-1.5 px-4 py-2 border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-800 text-sm font-medium rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                </svg>
                            </button>
                            <div x-data="{ open: null }" x-cloak>
                                <button @click="open = {{ $user_doctor->id }}" title="elimina"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                                <!-- Overlay -->
                                <div x-show="open === {{ $user_doctor->id }}" @click="open = false"
                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                </div>

                                <!-- Modal -->
                                <div x-show="open === {{ $user_doctor->id }}"
                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">

                                    <h2 class="w-full text-center font-bold">Elimina definitivamente
                                        {{$user_doctor->name}}?</h2>
                                    <div class="flex items-center justify-end gap-2 mt-5">
                                        <button @click="open = !open" title="elimina"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                            Annulla
                                        </button>
                                        <form {{-- action="{{ route('doctors.destroy', $user_doctor->id) }}" --}}
                                            method="post">
                                            @method('delete')
                                            @csrf

                                            <button title="elimina"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                Elimina
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                        <p>Nessun dottore disponibile.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>




</div>