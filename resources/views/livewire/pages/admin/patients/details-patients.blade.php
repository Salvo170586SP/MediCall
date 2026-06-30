<div class="h-full sm:px-6 lg:px-8">
    <div class="py-12 h-full">
        <div class="px-4 space-y-2">
            <button wire:navigate href="{{route('patients') }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                Indietro
            </button>
            {{-- Header card --}}
            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6">
                <div class="flex flex-col sm:flex-row sm:items-start gap-5">

                    {{-- Avatar iniziali --}}
                    @if($patient->guestDetail && $patient->guestDetail->img_url)
                    <figure
                        class="w-18 h-18 overflow-hidden shrink-0 rounded-full bg-gray-200 flex items-center justify-center text-blue-700 font-medium text-xs select-none">
                        <img class="w-full h-full object-center" src="{{$patient->guestDetail->img_url}}"
                            alt="{{$patient->name}}">
                    </figure>
                    @else
                    <div class="w-18 h-18 shrink-0 rounded-full bg-gray-200 flex items-center justify-center text-blue-700 font-medium text-xl select-none"
                        style="width:72px;height:72px;">
                        {{ collect(explode(' ', $patient->name))->map(fn($w) =>
                        strtoupper($w[0]))->take(2)->implode('') }}
                    </div>
                    @endif

                    {{-- Nome --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h1 class="text-xl font-medium text-gray-900">
                                {{ $patient->name }}
                            </h1>
                        </div>

                        <p class="text-sm text-gray-400">Città e data di nascita: {{
                            $patient->guestDetail->birth_city }}, {{
                            $patient->guestDetail->formatDate($patient->guestDetail->birth_date) }}
                        </p>

                        <p class="text-sm text-gray-400">Cod Fiscale: {{ $patient->guestDetail->tax_id_code
                            }}
                        </p>
                        <p class="text-sm text-gray-400">Telefono: {{ $patient->guestDetail->phone }}
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
            </div>

            <div class="flex gap-2">
                <div class="w-1/8 text-center bg-gray-50 rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-400 mb-1">Gruppo Sanguigno</p>
                    <p class="text-2xl font-medium text-gray-900">
                        {{ $patient->guestDetail->blood_type }}
                    </p>
                </div>

                <div class="w-full bg-gray-50 rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-400 mb-1">Allergie</p>
                    <p class="font-medium text-gray-900">
                        {{ $patient->guestDetail->allergies }}
                    </p>
                </div>
                <div class="w-1/6 text-center bg-gray-50 rounded-xl border border-gray-200 p-4">
                    <p class="text-xs text-gray-400 mb-1">Dottori che ti seguono</p>
                    <p class="text-2xl font-medium text-gray-900">
                        {{-- {{ $patient->doctorsPatients()->count() }} --}} 0
                    </p>
                </div>

            </div>

            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6">
                <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">Storia Clinica</h2>

                @if($patient->guestDetail)
                <p class="text-sm text-gray-600 leading-relaxed h-[150px] overflow-y-auto">
                    {{$patient->guestDetail->medical_history}}
                </p>
                @else
                <p class="text-sm text-gray-400 italic">Nessuna biografia inserita.</p>
                @endif
            </div>


            <div x-data="{ activeTab: 'params' }">
                {{-- Tab navigation --}}
                <div class="mt-10">
                    <nav class="flex gap-2">

                        <button @click="activeTab = 'params'"
                            :class="activeTab === 'params' ? 'bg-blue-50 text-blue-800 border border-blue-200' : 'bg-gray-50 hover:bg-gray-100'"
                            class="px-4 py-2 text-sm font-medium border rounded-t-xl transition-colors">
                            Storico Parametri
                        </button>
                        <button @click="activeTab = 'thresholds'"
                            :class="activeTab === 'thresholds' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-gray-50 hover:bg-gray-100'"
                            class="px-4 py-2 text-sm font-medium border rounded-t-xl transition-colors">
                            Soglie
                        </button>
                        <button @click="activeTab = 'appointments'"
                            :class="activeTab === 'appointments' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-gray-50 hover:bg-gray-100'"
                            class="px-4 py-2 text-sm font-medium border rounded-t-xl transition-colors">
                            Appuntamenti
                        </button>
                        <button @click="activeTab = 'records'"
                            :class="activeTab === 'records' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-gray-50 hover:bg-gray-100'"
                            class="px-4 py-2 text-sm font-medium border rounded-t-xl transition-colors">
                            Cartella clinica
                        </button>

                    </nav>
                </div>

                {{-- Tab content --}}
                <div class="bg-gray-50 rounded-2xl rounded-b-2xl rounded-l-none border border-gray-200 p-6">
                    {{-- Parametri Tab --}}
                    <div x-show="activeTab ===  'params'" class="gap-6">
                        {{-- <div
                            class="bg-gray-50 rounded-2xl rounded-b-2xl rounded-l-none border border-gray-200 p-6"> --}}
                            <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">Storico
                                parametri
                            </h2>

                            <div class="grid gap-4 lg:grid-cols-3 mb-6">
                                <div>
                                    <label for="type"
                                        class="block text-xs font-semibold text-slate-900 mb-2">Tipo</label>
                                    <input id="type" wire:model.live="type" type="text" placeholder="Cerca per tipo"
                                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
                                </div>
                                <div>
                                    <label for="source"
                                        class="block text-xs font-semibold text-slate-900 mb-2">Sorgente</label>
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
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="size-5">
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
                                                <x-modal title="Elimina definitivamente?" confirm-label="Note"
                                                    wire:key="deleteParameter-[{{$parameter->id}}]"
                                                    confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                                    <x-slot:trigger>
                                                        <button title="note"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="size-5">
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
                            {{--
                        </div> --}}
                    </div>

                    {{-- Soglie Tab --}}
                    <div x-show="activeTab === 'thresholds'" class="gap-6">
                        <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">
                            Soglie
                        </h2>
                        <!-- Paginazione -->
                        <div class="mb-6" id="pagination-links-thresholds" data-table="#health-soglie-table" {{--
                            @click="handleThresholdsPaginationClick($event)" --}}>
                            {{-- {{ $healthThresholds->links() }} --}}
                        </div>
                        <div class="w-full overflow-hidden rounded-xl border border-slate-200">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-white text-[11px] uppercase tracking-wider text-slate-900">
                                    <tr>
                                        <th class="px-6 py-3">Parametro</th>
                                        <th class="px-6 py-3">Min</th>
                                        <th class="px-6 py-3">Max</th>
                                        <th class="px-6 py-3">Severità</th>
                                        <th class="px-6 py-3">Notifica paziente</th>
                                        <th class="px-6 py-3">Notifica medico</th>
                                        <th class="px-6 py-3">Creata il</th>
                                        <th class="px-6 py-3">Creato da</th>
                                        <th class="px-6 py-3 text-right">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @forelse($healthThresholds ?? [] as $threshold)
                                    <tr class="border-b border-slate-200 hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 font-medium text-slate-900 capitalize">{{
                                            $threshold->parameter_type }}</td>
                                        <td class="px-6 py-4 text-slate-600">{{ $threshold->min_value ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">{{ $threshold->max_value ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-slate-900 capitalize">{{ $threshold->severity
                                            }}</td>
                                        <td class="px-6 py-4 text-slate-900">{{ $threshold->notify_patient ?
                                            'Sì' : 'No' }}</td>
                                        <td class="px-6 py-4 text-slate-900">{{ $threshold->notify_doctor ? 'Sì'
                                            : 'No' }}</td>
                                        <td class="px-6 py-4 text-slate-600">{{
                                            $threshold->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 text-slate-900">{{ $threshold->createdBy?->name ??
                                            '-' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <div x-data="{ openThreshold: null }" x-cloak>
                                                <button @click="openThreshold = {{ $threshold->id }}" title="elimina"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                                <!-- Overlay -->
                                                <div x-show="openThreshold === {{ $threshold->id }}"
                                                    @click="openThreshold = false"
                                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                                </div>

                                                <!-- Modal -->
                                                <div x-show="openThreshold === {{ $threshold->id }}"
                                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">

                                                    <h2 class="w-full text-center font-bold">Elimina
                                                        definitivamente?
                                                    </h2>
                                                    <div class="flex items-center justify-end gap-2 mt-5">
                                                        <button @click="openThreshold = !openThreshold" title="elimina"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                            Annulla
                                                        </button>
                                                        <form
                                                            action="{{ route('healthThresholds.destroy', [$patient->id, $threshold->id]) }}"
                                                            method="post" data-table="#health-soglie-table"
                                                            @submit.prevent="handleHealthThresholdDelete($event)">
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
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                            <p>Non ci sono soglie impostate per questo paziente.</p>
                                        </td>
                                    </tr>
                                    @endforelse --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Appuntamenti Tab --}}
                    <div x-show="activeTab ===  'appointments'" class="gap-6">
                        <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">
                            Appuntamenti
                        </h2>

                        <!-- Paginazione -->
                        <div class="mb-6" id="pagination-links-appointments" data-table="#appointments-table" {{--
                            @click="handleAppointmentsPaginationClick($event)" --}}>
                            {{-- {{ $appointments->links() }} --}}
                        </div>

                        <div class="w-full bg-white overflow-hidden rounded-xl border border-slate-200">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-white text-[11px] uppercase tracking-wider text-slate-900">
                                    <tr>
                                        <th class="px-6 py-3">Appuntamento con</th>
                                        <th class="px-6 py-3">Data</th>
                                        <th class="px-6 py-3">Durata</th>
                                        <th class="px-6 py-3 text-center">Modalità</th>
                                        <th class="px-6 py-3 text-center">Stato</th>
                                        <th class="px-6 py-3 text-center">Motivazione</th>
                                        <th class="px-6 py-3 text-center">Note del medico</th>
                                        <th class="px-6 py-3 text-center">Motivazione cancellazione</th>
                                        <th class="px-6 py-3">Cancellato da</th>
                                        <th class="px-6 py-3">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @forelse($appointments ?? [] as $appointment)
                                    <tr class="border-b border-slate-200 hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 font-medium text-slate-900 capitalize">
                                            {{ $appointment->doctor->name }}</td>
                                        <td class="px-6 py-4 font-medium text-slate-900 capitalize">
                                            {{ $appointment->formatDate($appointment->scheduled_at) }}</td>
                                        <td class="px-6 py-4 font-medium text-slate-900 capitalize">
                                            {{ $appointment->duration .'min' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            @if($appointment->type)
                                            <span
                                                class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $appointment->typeClasses() }}">
                                                {{ $appointment->typeLabel() }}
                                            </span>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($appointment->status)
                                            <span
                                                class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $appointment->statusClasses() }}">
                                                {{ $appointment->statusLabel() }}
                                            </span>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($appointment->reason)
                                            <div x-data="{ openModalReasonPat: null }" x-cloak>
                                                <button @click="openModalReasonPat = {{ $appointment->id }}"
                                                    title="apri"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                                <!-- Overlay -->
                                                <div x-show="openModalReasonPat === {{ $appointment->id }}"
                                                    @click="openModalReasonPat = false"
                                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                                </div>

                                                <!-- Modal -->
                                                <div x-show="openModalReasonPat === {{ $appointment->id }}"
                                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">
                                                    <p>{{ $appointment->reason ?? '-' }}</p>
                                                    <div class="flex items-center justify-end gap-2 mt-5">
                                                        <button @click="openModalReasonPat = !openModalReasonPat"
                                                            title="chiudi"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                            Annulla
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($appointment->notes)
                                            <div x-data="{ openModalNotesPat: null }" x-cloak>
                                                <button @click="openModalNotesPat = {{ $appointment->id }}" title="apri"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                                <!-- Overlay -->
                                                <div x-show="openModalNotesPat === {{ $appointment->id }}"
                                                    @click="openModalNotesPat = false"
                                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                                </div>

                                                <!-- Modal -->
                                                <div x-show="openModalNotesPat === {{ $appointment->id }}"
                                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">
                                                    <p>{{ $appointment->notes ?? '-' }}</p>
                                                    <div class="flex items-center justify-end gap-2 mt-5">
                                                        <button @click="openModalNotesPat = !openModalNotesPat"
                                                            title="chiudi"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                            Annulla
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($appointment->cancellation_reason)
                                            <div x-data="{ openModalCancReasPat: null }" x-cloak>
                                                <button @click="openModalCancReasPat = {{ $appointment->id }}"
                                                    title="apri"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                                <!-- Overlay -->
                                                <div x-show="openModalCancReasPat === {{ $appointment->id }}"
                                                    @click="openModalCancReasPat = false"
                                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                                </div>

                                                <!-- Modal -->
                                                <div x-show="openModalCancReasPat === {{ $appointment->id }}"
                                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">
                                                    <p>{{ $appointment->cancellation_reason ?? '-' }}</p>
                                                    <div class="flex items-center justify-end gap-2 mt-5">
                                                        <button @click="openModalCancReasPat = !openModalCancReasPat"
                                                            title="chiudi"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                            Annulla
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-slate-600">{{ $appointment->cancelled_by ??
                                            '-' }}</td>
                                        <td class="px-6 py-4 text-slate-600">
                                            <div x-data="{ openModalApp: null }" x-cloak>
                                                <button @click="openModalApp = {{ $appointment->id }}" title="elimina"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                                <!-- Overlay -->
                                                <div x-show="openModalApp === {{ $appointment->id }}"
                                                    @click="openModalApp = false"
                                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                                </div>

                                                <!-- Modal -->
                                                <div x-show="openModalApp === {{ $appointment->id }}"
                                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">

                                                    <h2 class="w-full text-center font-bold">Elimina
                                                        definitivamente?
                                                    </h2>
                                                    <div class="flex items-center justify-end gap-2 mt-5">
                                                        <button @click="openModalApp = !openModalApp" title="elimina"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                            Annulla
                                                        </button>
                                                        <form
                                                            action="{{ route('appointments.destroy', [$patient->id, $appointment->id]) }}"
                                                            method="post" data-table="#appointments-table"
                                                            @submit.prevent="handleAppointmentDelete($event)">
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

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                            <p>Non ci sono appuntamenti.</p>
                                        </td>
                                    </tr>
                                    @endforelse --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Cartella clinica Tab --}}
                    <div x-show="activeTab ===  'records'" class="gap-6">
                        <h2 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-4">
                            Cartella clinica
                        </h2>

                        <!-- Paginazione -->
                        <div class="mb-6" id="pagination-links-records" data-table="#health-records-table" {{--
                            @click="handleRecordsPaginationClick($event)" --}}>
                            {{-- {{ $medicalRecordsAsPatient->links() }} --}}
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
                                    {{-- @forelse($medicalRecordsAsPatient as $record)
                                    <tr class="border-b border-slate-200 hover:bg-slate-50 transition text-[14px]">
                                        <td class="px-6 py-4 font-medium text-slate-900 capitalize">{{
                                            $record->doctor->name }}</td>
                                        <td class="px-6 py-4 text-slate-600">{{
                                            $record->formatDate($record->visit_date) }}</td>
                                        <td class="px-6 py-4 text-slate-900">{{ $record->diagnosis }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($record->prescription)
                                            <div x-data="{ openPrescription: null }" x-cloak>
                                                <button @click="openPrescription = {{ $record->id }}" title="note"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                                <!-- Overlay -->
                                                <div x-show="openPrescription === {{ $record->id }}"
                                                    @click="openPrescription = false"
                                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                                </div>

                                                <!-- Modal -->
                                                <div x-show="openPrescription === {{ $record->id }}"
                                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">

                                                    <h2 class="w-full text-center font-bold">
                                                        Note
                                                    </h2>
                                                    <p>
                                                        {{ $record->prescription ?? '-'}}
                                                    </p>
                                                    <div class="flex items-center justify-end gap-2 mt-5">
                                                        <button @click="openPrescription = !openPrescription"
                                                            title="annulla"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                            Annulla
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-slate-900">
                                            @if($record->instructions)
                                            <div x-data="{ openInstructions: null }" x-cloak>
                                                <button @click="openInstructions = {{ $record->id }}" title="note"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
                                                    </svg>
                                                </button>
                                                <!-- Overlay -->
                                                <div x-show="openInstructions === {{ $record->id }}"
                                                    @click="openInstructions = false"
                                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                                </div>

                                                <!-- Modal -->
                                                <div x-show="openInstructions === {{ $record->id }}"
                                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">

                                                    <h2 class="w-full text-center font-bold">
                                                        Note
                                                    </h2>
                                                    <p>
                                                        {{ $record->instructions ?? '-'}}
                                                    </p>
                                                    <div class="flex items-center justify-end gap-2 mt-5">
                                                        <button @click="openInstructions = !openInstructions"
                                                            title="annulla"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                            Annulla
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
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
                                            <div x-data="{ open: null }" x-cloak>
                                                <button @click="open = {{ $record->id }}" title="elimina"
                                                    class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-200 bg-red-50 hover:bg-red-100 text-red-800 text-sm font-medium rounded-xl transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>

                                                <div x-show="open === {{ $record->id }}" @click="open = false"
                                                    class="fixed inset-0 bg-black bg-opacity-50 z-40">
                                                </div>


                                                <div x-show="open === {{ $record->id }}"
                                                    class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">

                                                    <h2 class="w-full text-center font-bold">Elimina
                                                        definitivamente?
                                                    </h2>
                                                    <div class="flex items-center justify-end gap-2 mt-5">
                                                        <button @click="open = !open" title="elimina"
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                                                            Annulla
                                                        </button>
                                                        <form method="post"
                                                            action="{{route('medicalRecords.destroy', [$patient->id, $record->id])}}"
                                                            data-table="#health-records-table"
                                                            @submit.prevent="handleRecordDelete($event)">
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
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-slate-500">
                                            <p>Nessun parametro disponibile.</p>
                                        </td>
                                    </tr>
                                    @endforelse --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>