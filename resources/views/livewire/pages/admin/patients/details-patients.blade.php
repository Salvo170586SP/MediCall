<div>
    <div class="h-full mx-auto">

        <button wire:navigate href="{{route('patients') }}"
            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            Indietro
        </button>

        <div class="mt-5" x-data="{ activeTab: 'patient' }">
            {{-- Tab navigation --}}
            <div class="mt-10 flex justify-between">
                <nav class="flex gap-2">
                    <button @click="activeTab = 'patient'"
                        :class="activeTab === 'patient' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-gray-50 hover:bg-gray-100'"
                        class="px-4 py-2 text-sm font-medium border border-b-0 rounded-t-xl transition-colors">
                        Dati Paziente
                    </button>
                    <button @click="activeTab = 'params'"
                        :class="activeTab === 'params' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-gray-50 hover:bg-gray-100'"
                        class="px-4 py-2 text-sm font-medium border border-b-0 rounded-t-xl transition-colors">
                        Storico Parametri <span
                            class="inline-flex ms-3 bg-blue-400 font-bold justify-center items-center text-white w-6 h-6 rounded-lg">{{$healthParameters->total()}}</span>
                    </button>
                    <button @click="activeTab = 'thresholds'"
                        :class="activeTab === 'thresholds' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-gray-50 hover:bg-gray-100'"
                        class="px-4 py-2 text-sm font-medium border  border-b-0 rounded-t-xl transition-colors">
                        Soglie <span
                            class="inline-flex ms-3 bg-blue-400 font-bold justify-center items-center text-white w-6 h-6 rounded-lg">{{$healthThresholds->total()}}</span>
                    </button>
                    <button @click="activeTab = 'appointments'"
                        :class="activeTab === 'appointments' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-gray-50 hover:bg-gray-100'"
                        class="px-4 py-2 text-sm font-medium border border-b-0 rounded-t-xl transition-colors">
                        Appuntamenti <span
                            class="inline-flex ms-3 bg-blue-400 font-bold justify-center items-center text-white w-6 h-6 rounded-lg">{{$appointments->total()}}</span>
                    </button>
                    <button @click="activeTab = 'records'"
                        :class="activeTab === 'records' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-gray-50 hover:bg-gray-100'"
                        class="px-4 py-2 text-sm font-medium border border-b-0 rounded-t-xl transition-colors">
                        Cartella clinica <span
                            class="inline-flex ms-3 bg-blue-400 font-bold justify-center items-center text-white w-6 h-6 rounded-lg">{{$medicalRecordsAsPatient->total()}}</span>
                    </button>
                    <button @click="activeTab = 'files'"
                        :class="activeTab === 'files' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-gray-50 hover:bg-gray-100'"
                        class="px-4 py-2 text-sm font-medium border border-b-0 rounded-t-xl transition-colors">
                        File caricati <span
                            class="inline-flex ms-3 bg-blue-400 font-bold justify-center items-center text-white w-6 h-6 rounded-lg">{{$documents->total()}}</span>
                    </button>
                </nav>
                <div class="px-4 py-2 border border border-b-0 rounded-t-xl bg-gray-100 border-gray-200">
                    <p class="font-medium">
                        Paziente:
                        <span class="font-bold text-blue-700">{{ $patient->name }}</span>
                    </p>
                </div>
            </div>

            {{-- Tab content --}}
            <div class="bg-gray-50 rounded-2xl rounded-b-2xl rounded-l-none rounded-r-none border border-gray-200 p-6">
                {{-- dati paziente tab --}}
                <div x-show="activeTab === 'patient'">
                    <div
                        class="flex flex-col sm:flex-row sm:items-start mb-2 bg-white rounded-2xl border border-gray-200 p-4">
                        {{-- Nome --}}
                        <div class="flex-1 min-w-0">
                            <livewire:pages.admin.patients.code-badge :patient="$patient"
                                :key="'patient-code-'.$patient->id" />

                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <h1 class="text-xl font-medium text-gray-900">
                                    {{ $patient->name }}
                                </h1>
                            </div>

                            <p class="text-sm text-gray-400">Città e data di nascita: {{
                                $patient->guestDetail?->birth_city ?? '-' }}, {{
                                $patient->guestDetail?->formatDate($patient->guestDetail?->birth_date) }}
                            </p>

                            <p class="text-sm text-gray-400">Cod Fiscale: {{ $patient->guestDetail?->tax_id_code ??
                                '-'
                                }}
                            </p>
                            <p class="text-sm text-gray-400">Telefono: {{ $patient->guestDetail?->phone }}
                            </p>
                        </div>

                        {{-- Azioni --}}
                        <div class="flex gap-2 flex-wrap">
                            <button wire:navigate href="{{ route('patients.edit', $patient->id) }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 border border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-800 text-sm font-medium rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                </svg>
                                Modifica
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-2 mb-2">
                        <div class="w-1/8 text-center bg-white rounded-xl border border-gray-200 p-4">
                            <p class="text-xs text-gray-400 mb-1">Gruppo Sanguigno</p>
                            <p class="text-2xl font-medium text-gray-900">
                                {{ $patient->guestDetail?->blood_type }}
                            </p>
                        </div>

                        <div class="w-full bg-white rounded-xl border border-gray-200 p-4">
                            <p class="text-xs text-gray-400 mb-1">Allergie</p>
                            <p class="font-medium text-gray-900">
                                {{ $patient->guestDetail?->allergies }}
                            </p>
                        </div>
                        <div class="w-1/6 text-center bg-white rounded-xl border border-gray-200 p-4">
                            <p class="text-xs text-gray-400 mb-1">Dottori che ti seguono</p>
                            <p class="text-2xl font-medium text-gray-900">
                                {{ $patient->doctorsCount}}
                            </p>
                        </div>

                    </div>

                    <div class="rounded-2xl bg-white border border-gray-200 p-6">
                        <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">Storia Clinica</h2>

                        @if($patient->guestDetail)
                        <p class="text-sm text-gray-600 leading-relaxed h-[150px] overflow-y-auto">
                            {{$patient->guestDetail?->medical_history}}
                        </p>
                        @else
                        <p class="text-sm text-gray-400 italic">Nessuna biografia inserita.</p>
                        @endif
                    </div>
                </div>

                {{-- Parametri Tab --}}
                <div x-show="activeTab ===  'params'">
                    <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">Storico
                        parametri
                    </h2>

                    <div class="grid gap-4 lg:grid-cols-3 mb-6">
                        <x-search-input inputClass="bg-white" id="type" model="type" label="Tipo"
                            placeholder="Cerca per tipo" />
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

                    <!-- Paginazione -->
                    <div class="mb-6">
                        {{ $healthParameters->links() }}
                    </div>

                    <div class="w-full bg-white rounded-xl overflow-hidden border border-slate-200">
                        <table class="w-full h-full" id="health-parameters-table">
                            <thead>
                                <tr class="border-b border-slate-200 bg-white text-[11px]">
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Tipo</th>
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Valore</th>
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Unità</th>
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Misurato il</th>
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Sorgente</th>
                                    <th class="px-6 py-3 font-semibold text-slate-900 uppercase tracking-wider">
                                        Note</th>
                                    <th
                                        class="px-6 py-3 text-right font-semibold text-slate-900 uppercase tracking-wider">
                                        Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($healthParameters as $parameter)
                                <tr wire:key="parameters-[{{$parameter->id}}]"
                                    class="border-b border-slate-200 hover:bg-slate-50 transition text-[14px]">
                                    <td class="px-6 py-4 font-medium text-slate-900 capitalize">{{
                                        $parameter->type }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $parameter->value }}</td>
                                    <td class="px-6 py-4 text-slate-900">{{ $parameter->unit }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $parameter->formatDate($parameter->measured_at) ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-900">
                                        {{ $parameter->source ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($parameter->notes)
                                        <x-modal title="Note" confirm-label="Note"
                                            wire:key="noteParameter-[{{$parameter->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="note"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
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
                                    <td class="px-6 py-4 text-right">
                                        <x-modal title="Elimina definitivamente?" confirm-label="Elimina"
                                            wire:key="deleteParameter-[{{$parameter->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="note"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                Questa operazione è irreversibile.
                                            </x-slot:body>

                                            <x-slot:action>
                                                <button wire:click="deleteParameter({{$parameter->id}})"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-300 bg-red-100 hover:bg-red-200 text-red-900 text-sm font-medium rounded-xl transition-colors">
                                                    Elimina
                                                </button>
                                            </x-slot:action>
                                        </x-modal>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                        <p>Nessun parametro disponibile.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Soglie Tab --}}
                <div x-show="activeTab === 'thresholds'">
                    <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">
                        Soglie
                    </h2>

                    <div class="grid gap-4 lg:grid-cols-3 mb-6">
                        <x-search-input inputClass="bg-white" id="created_at" type="date" model="created_at"
                            label="Cerca per data" />
                        <div>
                            <label for="severity" class="block text-xs font-semibold text-slate-900 mb-2">Cerca
                                per
                                severità</label>
                            <select id="severity" wire:model.live="severity"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                <option value="">Tutti</option>
                                @foreach($severities as $key => $value)
                                <option wire:key="{{$key}}" value="{{$value['severity']}}">
                                    {{$value['label']}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Paginazione -->
                    <div class="mb-6">
                        {{ $healthThresholds->links() }}
                    </div>
                    <div class="w-full  overflow-hidden rounded-xl border border-slate-200">
                        <table class="w-full bg-white text-left text-sm">
                            <thead class="bg-white text-[11px] uppercase tracking-wider text-slate-900">
                                <tr>
                                    <th class="px-6 py-3">Parametro</th>
                                    <th class="px-6 py-3">Min</th>
                                    <th class="px-6 py-3">Max</th>
                                    <th class="px-6 py-3">Severità</th>
                                    <th class="px-6 py-3 text-center">Notifica paziente</th>
                                    <th class="px-6 py-3 text-center">Notifica medico</th>
                                    <th class="px-6 py-3">Creata il</th>
                                    <th class="px-6 py-3">Creato da</th>
                                    <th class="px-6 py-3 text-right">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($healthThresholds ?? [] as $threshold)
                                <tr class="border-b border-slate-200 hover:bg-slate-50 transition text-[14px]">
                                    <td class="px-6 py-4 font-medium text-slate-900 capitalize">{{
                                        $threshold->parameter_type }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $threshold->min_value ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $threshold->max_value ?? '-' }}
                                    </td>
                                    <td class="px-2 py-4 text-slate-900 capitalize">
                                        @foreach($severities as $value)
                                        @if($threshold->severity == $value['severity'] )
                                        <span
                                            class="inline-flex justify-center items-center w-full px-3 py-1 rounded-xl font-medium {{$threshold->severity == $value['severity'] ? $value['color'] : '' }}">
                                            {{ $value['label'] }}
                                        </span>
                                        @endif
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-slate-900 text-center">
                                        <div class="w-full flex justify-center">
                                            @if($threshold->notify_patient)
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                                            </svg>
                                            @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9.143 17.082a24.248 24.248 0 0 0 3.844.148m-3.844-.148a23.856 23.856 0 0 1-5.455-1.31 8.964 8.964 0 0 0 2.3-5.542m3.155 6.852a3 3 0 0 0 5.667 1.97m1.965-2.277L21 21m-4.225-4.225a23.81 23.81 0 0 0 3.536-1.003A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6.53 6.53m10.245 10.245L6.53 6.53M3 3l3.53 3.53" />
                                            </svg>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-900 text-center">
                                        <div class="w-full flex justify-center">
                                            @if($threshold->notify_doctor)
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                                            </svg>
                                            @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9.143 17.082a24.248 24.248 0 0 0 3.844.148m-3.844-.148a23.856 23.856 0 0 1-5.455-1.31 8.964 8.964 0 0 0 2.3-5.542m3.155 6.852a3 3 0 0 0 5.667 1.97m1.965-2.277L21 21m-4.225-4.225a23.81 23.81 0 0 0 3.536-1.003A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6.53 6.53m10.245 10.245L6.53 6.53M3 3l3.53 3.53" />
                                            </svg>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{
                                        $threshold->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-slate-900">{{ $threshold->createdBy?->name ??
                                        '-' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <x-modal title="Elimina definitivamente?" confirm-label="elimina"
                                            wire:key="deleteThreshold-[{{$threshold->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="elimina"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                Questa operazione è irreversibile.
                                            </x-slot:body>

                                            <x-slot:action>
                                                <button wire:click="deleteThreshold({{$threshold->id}})"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-300 bg-red-100 hover:bg-red-200 text-red-900 text-sm font-medium rounded-xl transition-colors">
                                                    Elimina
                                                </button>
                                            </x-slot:action>
                                        </x-modal>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                        <p>Non ci sono soglie impostate per questo paziente.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Appuntamenti Tab --}}
                <div x-show="activeTab ===  'appointments'">
                    <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">
                        Appuntamenti
                    </h2>

                    <div class="grid gap-4 lg:grid-cols-4 mb-6">
                        <x-search-input inputClass="bg-white" id="searchDoctor" model="searchDoctor"
                            placeholder="Cerca per Dottore" />
                        <x-search-input inputClass="bg-white" type="date" id="searchCreatedAt" model="searchCreatedAt"
                            label="Cerca per data" />
                        <div>
                            <label for="type" class="block text-xs font-semibold text-slate-900 mb-2">Cerca
                                per
                                Modalità</label>
                            <select id="type" wire:model.live="searchType"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                <option value="">Tutti</option>
                                @foreach($types as $key => $value)
                                <option wire:key="{{$key}}" value="{{$value['type']}}">
                                    {{$value['label']}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="severity" class="block text-xs font-semibold text-slate-900 mb-2">Cerca
                                per
                                Stato</label>
                            <select id="status" wire:model.live="searchStatus"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                <option value="">Tutti</option>
                                @foreach($statuses as $key => $value)
                                <option wire:key="{{$key}}" value="{{$value['status']}}">
                                    {{$value['label']}}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Paginazione -->
                    <div class="mb-6" id="pagination-links-appointments" data-table="#appointments-table">
                        {{ $appointments->links() }}
                    </div>

                    <div class="w-full bg-white overflow-hidden rounded-xl border border-slate-200">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-white text-[11px] uppercase tracking-wider text-slate-900">
                                <tr>
                                    <th class="px-6 py-2">Appuntamento con</th>
                                    <th class="px-2 py-2">Data</th>
                                    <th class="px-6 py-2">Durata</th>
                                    <th class="px-6 py-2 text-center">Modalità</th>
                                    <th class="px-6 py-2 text-center">Stato</th>
                                    <th class="px-6 py-2 text-center">Motivazione</th>
                                    <th class="px-6 py-2 text-center">Note del medico</th>
                                    <th class="px-6 py-2 text-center">Motivazione cancellazione</th>
                                    <th class="px-6 py-2">Cancellato da</th>
                                    <th class="px-6 py-2">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments ?? [] as $appointment)
                                <tr class="border-b border-slate-200 hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 font-medium text-slate-900 capitalize">
                                        {{ $appointment->doctor->name }}</td>
                                    <td class="px-2 py-4 font-medium text-slate-900 capitalize w-full">
                                        {{ $appointment->formatDate($appointment->scheduled_at) }}</td>
                                    <td class="px-6 py-4 font-medium text-slate-900 capitalize">
                                        {{ $appointment->duration .'min' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @foreach($types as $value)
                                        <div wire:key="types-[{{$appointment->id}}-{{$value['type']}}]">
                                            @if($appointment->type == $value['type'] )
                                            <span
                                                class="inline-flex justify-center items-center w-full px-3 py-1 rounded-xl font-medium {{$appointment->type == $value['type'] ? $value['color'] : '' }}">
                                                {{ $value['label'] }}
                                            </span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @foreach($statuses as $value)
                                        <div wire:key="statuses-[{{$appointment->id}}-{{$value['status']}}]">
                                            @if($appointment->status == $value['status'] )
                                            <span
                                                class="inline-flex justify-center items-center w-full px-3 py-1 rounded-xl font-medium {{$appointment->status == $value['status'] ? $value['color'] : '' }}">
                                                {{ $value['label'] }}
                                            </span>
                                            @endif
                                        </div>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($appointment->reason)
                                        <x-modal title="reason" confirm-label="reason"
                                            wire:key="reason-[{{$appointment->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="reason"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                <p>{{ $appointment->reason ?? '-' }}</p>
                                            </x-slot:body>

                                            <x-slot:action>
                                            </x-slot:action>
                                        </x-modal>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($appointment->notes)
                                        <x-modal title="notes" confirm-label="notes"
                                            wire:key="notes-[{{$appointment->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="notes"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                <p>{{ $appointment->notes ?? '-' }}</p>
                                            </x-slot:body>

                                            <x-slot:action>
                                            </x-slot:action>
                                        </x-modal>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($appointment->cancellation_reason)
                                        <x-modal title="Motivazione" confirm-label="motivazione"
                                            wire:key="motivazione-[{{$appointment->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="Motivazione"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                <p>{{ $appointment->cancellation_reason ?? '-' }}</p>
                                            </x-slot:body>

                                            <x-slot:action>
                                            </x-slot:action>
                                        </x-modal>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $appointment->cancelledBy->name
                                        ?? '-'
                                        }}</td>
                                    <td class="px-6 py-4 text-slate-600">
                                        <x-modal title="Elimina definitivamente?" confirm-label="elimina"
                                            wire:key="deleteAppointment-[{{$appointment->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="note"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                Questa operazione è irreversibile.
                                            </x-slot:body>

                                            <x-slot:action>
                                                <button wire:click="deleteAppointment({{$appointment->id}})"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-300 bg-red-100 hover:bg-red-200 text-red-900 text-sm font-medium rounded-xl transition-colors">
                                                    Elimina
                                                </button>
                                            </x-slot:action>
                                        </x-modal>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                        <p>Non ci sono appuntamenti.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Cartella clinica Tab --}}
                <div x-show="activeTab ===  'records'">
                    <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">
                        Cartella clinica
                    </h2>

                    <div class="grid gap-4 lg:grid-cols-3 mb-6">
                        <x-search-input inputClass="bg-white" id="searchVisit" type="date" model="searchVisit"
                            label="Cerca per data visita" />
                        <x-search-input inputClass="bg-white" id="searchControll" type="date" model="searchControll"
                            label="Cerca per data controllo" />
                    </div>

                    <!-- Paginazione -->
                    <div class="mb-6">
                        {{ $medicalRecordsAsPatient->links() }}
                    </div>

                    <div class="w-full bg-white rounded-xl overflow-hidden border border-slate-200">
                        <table class="w-full h-full" id="health-records-table">
                            <thead>
                                <tr class="border-b border-slate-200 bg-white text-[11px]">
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Medico</th>
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Data di Visita</th>
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Diagnosi</th>
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Prescrizioni</th>
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Consigli</th>
                                    <th
                                        class="px-6 py-3  text-center font-semibold text-slate-900 uppercase tracking-wider">
                                        Prossimo controllo il</th>
                                    <th
                                        class="px-6 py-3  text-center font-semibold text-slate-900 uppercase tracking-wider">
                                        Allegato</th>
                                    <th
                                        class="px-6 py-3 text-right font-semibold text-slate-900 uppercase tracking-wider">
                                        Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($medicalRecordsAsPatient as $record)
                                <tr class="border-b border-slate-200 hover:bg-slate-50 transition text-[14px]">
                                    <td class="px-6 py-4 font-medium text-slate-900 capitalize">{{
                                        $record->doctor->name }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{
                                        $record->formatDate($record->visit_date) }}</td>
                                    <td class="px-6 py-4 text-slate-900">{{ $record->diagnosis }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($record->prescription)
                                        <x-modal title="Prescrizioni" confirm-label="prescrizioni"
                                            wire:key="prescription-[{{$record->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="note"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                {{ $record->prescription ?? '-'}}
                                            </x-slot:body>

                                            <x-slot:action>
                                            </x-slot:action>
                                        </x-modal>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-900">
                                        @if($record->instructions)
                                        <x-modal title="Consigli" confirm-label="consigli"
                                            wire:key="instructions-[{{$record->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="note"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                {{ $record->instructions ?? '-'}}
                                            </x-slot:body>

                                            <x-slot:action>
                                            </x-slot:action>
                                        </x-modal>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-900 text-center">
                                        {{ $record->formatDate($record->follow_up_date) }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-900 text-center">
                                        {{ $record->file ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <x-modal title="Elimina definitivamente?" confirm-label="elimina"
                                            wire:key="deleteRecord-[{{$record->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="note"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                Questa operazione è irreversibile.
                                            </x-slot:body>

                                            <x-slot:action>
                                                <button wire:click="deleteRecord({{$record->id}})"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-300 bg-red-100 hover:bg-red-200 text-red-900 text-sm font-medium rounded-xl transition-colors">
                                                    Elimina
                                                </button>
                                            </x-slot:action>
                                        </x-modal>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                        <p>Nessun parametro disponibile.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Files Tab --}}
                <div x-show="activeTab ===  'files'">
                    <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">
                        File caricati del paziente
                    </h2>

                    <div class="grid gap-4 lg:grid-cols-3 mb-6">
                        <x-search-input inputClass="bg-white" id="searchNameDoc" type="text" model="searchNameDoc"
                            label="Cerca per nome" />
                        <x-search-input inputClass="bg-white" id="searchTypeDoc" type="text" model="searchTypeDoc"
                            label="Cerca per tipo" />
                    </div>

                    <!-- Paginazione -->
                    <div class="mb-6">
                        {{ $documents->links() }}
                    </div>

                    <div class="w-full bg-white rounded-xl overflow-hidden border border-slate-200">
                        <table class="w-full h-full" id="health-records-table">
                            <thead>
                                <tr class="border-b border-slate-200 bg-white text-[11px]">
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Nome File</th>
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Tipo file</th>
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Caricato da</th>
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        note</th>
                                    <th
                                        class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                                        Verificato</th>
                                    <th
                                        class="px-6 py-3  text-center font-semibold text-slate-900 uppercase tracking-wider">
                                        Scarica file</th>
                                    <th
                                        class="px-6 py-3 text-right font-semibold text-slate-900 uppercase tracking-wider">
                                        Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($documents as $document)
                                <tr wire:key="doc-[{{$document->id}}]" class="border-b border-slate-200 hover:bg-slate-50 transition text-[14px]">
                                    <td class="px-6 py-4 font-medium text-slate-900 capitalize">{{
                                        $document->original_filename }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{
                                        $document->title }}</td>
                                    <td class="px-6 py-4 text-slate-900">
                                        {{ $document->uploadedBy->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($document->notes)
                                        <x-modal title="note" confirm-label="notes" wire:key="notes-[{{$document->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="notes"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                {{ $document->notes ?? '-'}}
                                            </x-slot:body>

                                            <x-slot:action>
                                            </x-slot:action>
                                        </x-modal>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-900">
                                        {{ $document->is_verified ? 'verificato' : 'non verificato' }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-900 text-center">
                                        @if($document->file_path)
                                        <a title="scarica" download href="{{ asset('storage/'.$document->file_path) }}"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <x-modal title="Elimina definitivamente?" confirm-label="elimina"
                                            wire:key="deleteDoc-[{{$document->id}}]"
                                            confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                            <x-slot:trigger>
                                                <button title="note"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </x-slot:trigger>

                                            <x-slot:body>
                                                Questa operazione è irreversibile.
                                            </x-slot:body>

                                            <x-slot:action>
                                                <button wire:click="deleteDoc({{$document->id}})"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-300 bg-red-100 hover:bg-red-200 text-red-900 text-sm font-medium rounded-xl transition-colors">
                                                    Elimina
                                                </button>
                                            </x-slot:action>
                                        </x-modal>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                        <p>Nessun documento disponibile.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>