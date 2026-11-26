<div>
    <div class="space-y-2">
        @role('Admin')
        <button wire:navigate href="{{ route('doctors') }}"
            class="inline-flex items-center gap-1.5 px-4 py-3 bg-white hover:shadow-lg text-gray-700 text-sm font-bold rounded-xl transition border border-gray-200 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            {{ __('Indietro') }}
        </button>
        @endrole

        {{-- Tab navigation --}}
        <div x-data="{ activeTab: 'doctor' }" class="mt-10">
            <div class="flex justify-between">
                <nav class="flex gap-2">
                    <button @click="activeTab = 'doctor'"
                        :class="activeTab === 'doctor' ? 'bg-white text-gray-800 border-gray-200' : 'bg-white text-gray-400 hover:text-gray-600'"
                        class="px-4 py-2 cursor-pointer font-medium border border-b-0 rounded-t-xl">
                        {{ __('Dati Dottore') }}
                    </button>
                    <button @click="activeTab = 'appointments'"
                        :class="activeTab === 'appointments' ? 'bg-white text-gray-800 border-gray-200' : 'bg-white text-gray-400 hover:text-gray-600'"
                        class="px-4 py-2 cursor-pointer font-medium border border-b-0 rounded-t-xl">
                        {{ __('Appuntamenti') }} <span
                            class="inline-flex ms-3 bg-gray-600 font-bold justify-center items-center text-white w-6 h-6 rounded-lg">{{$appointments->total()}}</span>
                    </button>
                </nav>
                <div class="px-4 py-2 border border border-b-0 rounded-t-xl bg-white gray-100 border-gray-200">
                    <p class="font-medium text-gray-500">
                        {{ __('Dott.') }}
                        <span class="font-bold text-gray-700">{{ $doctor->name }}</span>
                    </p>
                </div>
            </div>


            {{-- Tab content --}}
            <div class="bg-white rounded-4xl rounded-b-4xl rounded-l-none rounded-r-none border border-gray-200 p-6">

                {{-- dati dottore tab --}}
                <div x-show="activeTab === 'doctor'">
                    <div class="bg-white rounded-2xl border border-gray-200 p-4">
                        <div class="flex flex-col sm:flex-row sm:items-start gap-5">
                            {{-- Avatar iniziali --}}
                            @if($doctor->photo)
                            <figure class="w-48 h-38 overflow-hidden rounded-2xl border border-gray-300">
                                <img class="w-full h-full object-cover"
                                    src="{{ asset('storage/'.$doctor->photo?->file_path)}}" alt="{{$doctor->name}}">
                            </figure>
                            @else
                            <div class="w-18 h-18 shrink-0 rounded-2xl bg-gray-200 flex items-center justify-center text-gray-700 font-medium text-xl select-none"
                                style="width:72px;height:72px;">
                                {{ Str::initials( $doctor->name) }}
                            </div>
                            @endif

                            {{-- Nome --}}
                            <div class="flex-1 min-w-0">
                                <livewire:pages.doctors.code-badge :doctor="$doctor"
                                    :key="'doctor-code-'.$doctor->id" />
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <h1 class="text-xl font-medium text-gray-900">
                                        {{ __('Dott.') }} {{ $doctor->name }}
                                    </h1>

                                    @if($doctor->doctorDetail && $doctor->doctorDetail->is_active)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700">
                                        {{ __('Disponibile') }}
                                    </span>
                                    @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-600">
                                        {{ __('Non disponibile') }}
                                    </span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-500 font-medium mb-1">
                                    {{ $doctor->doctorDetail?->specialty }} · <span class="text-black">{{
                                        $doctor->doctorDetail?->sub_specialty }}</span>
                                </p>
                                <p class="text-sm text-gray-500 font-medium">{{ __('Matricola') }}:
                                    <span class="text-black">
                                        {{ $doctor->doctorDetail?->license_number }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-500 font-medium mt-3">{{ __('Email') }}:
                                    <span class="text-black">
                                        {{ $doctor->email }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-500 font-medium">{{ __('Telefono') }}:
                                    <span class="text-black">
                                        {{ $doctor->doctorDetail?->phone}}
                                    </span>
                                </p>
                            </div>

                            {{-- Azioni --}}
                            @role('Admin')
                            <div class="flex gap-2 flex-wrap">
                                <button wire:navigate href="/doctors/edit/{{$doctor->id}}"
                                    class="inline-flex justify-center items-center sm:justify-start gap-2 p-3 cursor-pointer rounded-xl bg-black hover:bg-gray-800 font-bold text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    </svg>
                                    {{ __('Modifica') }}
                                </button>
                            </div>
                            @endrole

                            @role('Doctor')
                            <div class="flex gap-2 flex-wrap">
                                <button wire:navigate href="/doctor/edit/{{$doctor->id}}"
                                    class="inline-flex justify-center items-center sm:justify-start gap-2 p-3 cursor-pointer rounded-xl bg-black hover:bg-gray-800 font-bold text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    </svg>
                                    {{ __('Modifica') }}
                                </button>
                            </div>
                            @endrole
                        </div>
                    </div>

                    {{-- Statistiche --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 my-2">
                        <div class="bg-white rounded-2xl border border-gray-200 p-4">
                            <p class="text-gray-500 mb-1">{{ __('Durata slot') }}</p>
                            <p class="text-2xl font-medium text-gray-900">
                                {{ $doctor->doctorDetail?->slot_duration }}
                                <span class="text-sm text-gray-400 font-normal">min</span>
                            </p>
                        </div>

                        <div class="bg-white rounded-2xl border border-gray-200 p-4">
                            <p class="text-gray-500 mb-1">{{ __('Giorni disponibili') }}</p>
                            <p class="text-2xl font-medium text-gray-900">
                                {{ $availableDaysCount }}
                                <span class="text-gray-500 text-sm font-normal">{{ __('giorni/sett') }}</span>
                            </p>
                        </div>

                        <div class="bg-white rounded-2xl border border-gray-200 p-4 col-span-2 sm:col-span-1">
                            <p class="text-gray-500 mb-1">{{ __('Pazienti seguiti') }}</p>
                            <p class="text-2xl font-medium text-gray-900">
                                {{ $doctor->patientsCount }}
                            </p>
                        </div>
                    </div>

                    {{-- Corpo principale --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

                        {{-- Biografia --}}
                        <div class="bg-white h-full rounded-2xl border border-gray-200 p-6">
                            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">{{
                                __('Biografia') }}</h2>

                            @if($doctor->doctorDetail && $doctor->doctorDetail->bio)
                            <p class="text-sm text-gray-600 h-[100px] leading-relaxed overflow-y-auto">
                                {{$doctor->doctorDetail->bio}}
                            </p>
                            @else
                            <p class="text-sm text-gray-500 italic">{{ __('Nessuna biografia inserita') }}</p>
                            @endif
                        </div>

                        {{-- Disponibilità --}}
                        <div class="bg-white h-full rounded-2xl border border-gray-200 p-6">
                            <h2 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-4">{{
                                __('Disponibilità') }}
                            </h2>

                            <div class="space-y-2">
                                @foreach ($weekDays as $key => $label)
                                @php
                                $hours = $availableHours[$key] ?? [];
                                $from = $hours[0] ?? null;
                                $to = $hours[1] ?? null;
                                $isAvailable = filled($from) && filled($to);
                                @endphp

                                <div
                                    class="flex items-center justify-between gap-4 rounded-2xl border px-4 py-2 {{ $isAvailable ? 'border-gray-200  bg-gray-100' : 'border-gray-200 bg-white' }}">
                                    <span class="{{ $isAvailable ? 'text-gray-600 font-medium ' : 'text-gray-400' }}">{{
                                        $label }}</span>
                                    <span class="{{ $isAvailable ? 'text-gray-600 font-medium ' : 'text-gray-400' }}">
                                        @if ($isAvailable)
                                        {{ $from }} → {{ $to }}
                                        @else
                                        {{ __('Non disponibile') }}
                                        @endif
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>


                {{-- Appuntamenti Pazienti --}}
                <div x-show="activeTab === 'appointments'">
                    <div class="flex items-center justify-between mb-10">
                        <h2 class="text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Appuntamenti Pazienti') }}
                        </h2>
                        @role('Doctor')
                        <livewire:pages.doctors.add-appointment-form :doctor="$doctor"
                            :key="'add-appointment-'.$doctor->id" />
                        @endrole
                    </div>


                    <div class="grid gap-4 lg:grid-cols-4 mb-6">
                        <x-search-input :label="__('Cerca')" inputClass="bg-white" id="searchGuest" model="searchGuest"
                            placeholder="{{ __('Cerca per nome') }}" />
                        <x-search-input inputClass="bg-white" id="searchScheduledAt" model="searchScheduledAt"
                            :label="__('Cerca per data')" type="date" />
                        <div>
                            <label for="type" class="block text-xs font-semibold text-slate-900 mb-2">{{ __('Cerca per
                                modalità') }}</label>
                            <select id="type" wire:model.live="searchType"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                <option value="">{{ __('Tutti') }}</option>
                                @foreach($types as $key => $value)
                                <option wire:key="{{$key}}" value="{{$value['type']}}">
                                    {{ __($value['label']) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="severity" class="block text-xs font-semibold text-slate-900 mb-2">{{ __('Cerca
                                per stato') }}</label>
                            <select id="status" wire:model.live="searchStatus"
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                                <option value="">{{ __('Tutti') }}</option>
                                @foreach($statuses as $key => $value)
                                <option wire:key="{{$key}}" value="{{$value['status']}}">
                                    {{ __($value['label']) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Paginazione -->
                    <div class="mb-6" id="pagination-links-appointments" data-table="#appointments-table">
                        {{ $appointments->links() }}
                    </div>

                    <div class="w-full overflow-hidden rounded-2xl border border-slate-200">
                        <table class="bg-white w-full text-left text-sm">
                            <thead class="bg-gray-100 text-[11px] uppercase tracking-wider text-slate-900">
                                <tr>
                                    <th class="px-6 py-3">{{ __('Appuntamento con') }}</th>
                                    <th class="px-3 py-3">{{ __('Data') }}</th>
                                    <th class="px-3 py-3">{{ __('Orario') }}</th>
                                    <th class="px-6 py-3 text-center">{{ __('Modalità') }}</th>
                                    <th class="px-6 py-3 text-center">{{ __('Stato') }}</th>
                                    <th class="px-6 py-3 text-center">{{ __('Motivazione visita') }}</th>
                                    <th class="px-6 py-3 text-center">{{ __('Note del medico') }}</th>
                                    <th class="px-6 py-3 text-end">{{ __('Azioni') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($appointments as $appointment)
                                <tr wire:key="appointment-{{$doctor->id}}-{{ $appointment->id }}"
                                    class="border-b border-slate-200">
                                    <td class="px-6 py-4 font-medium text-slate-900 capitalize">
                                        {{ $appointment->patient->name }}</td>
                                    <td class="px-2 py-4 font-medium text-slate-900 capitalize w-[230px]">
                                        {{ $appointment->formatDate($appointment->scheduled_at) }}</td>
                                    <td class="px-2 py-4 text-center font-medium text-slate-900 capitalize">
                                        {{ $appointment->formatClock($appointment->scheduled_at) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                        $typesColor = collect($types)->firstWhere('type',
                                        $appointment->type)['color'] ?? '';
                                        @endphp
                                        @role('Doctor')
                                        <select id="type-{{ $appointment->id }}"
                                            class="inline-flex justify-center items-center w-full px-3 py-2 rounded-xl font-medium border {{ $typesColor }}"
                                            wire:change="editTypeAppointment({{ $appointment->id }}, $event.target.value)">
                                            <option value="">- {{ __('Seleziona') }} -</option>

                                            @foreach($types as $value)
                                            <option wire:key="type-{{$value['type']}}" value="{{$value['type']}}"
                                                @selected($appointment->type == $value['type'])>
                                                {{ __($value['label']) }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @endrole
                                        @role('Admin')
                                        @foreach($types as $value)
                                        @if($appointment->type == $value['type'])
                                        <div wire:key="type-{{$value['type']}}"
                                            class="inline-flex justify-center items-center w-full px-3 py-1 rounded-xl font-medium border {{ $typesColor }}">
                                            {{ __($value['label']) }}
                                        </div>
                                        @endif
                                        @endforeach
                                        @endrole
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                        $statusColor = collect($statuses)->firstWhere('status',
                                        $appointment->status)['color'] ?? '';
                                        @endphp
                                        @role('Doctor')
                                        <select id="status-{{ $appointment->id }}"
                                            class="inline-flex justify-center items-center w-full px-3 py-2 rounded-xl font-medium border {{ $statusColor }}"
                                            wire:change="editStatusAppointment({{ $appointment->id }}, $event.target.value)">
                                            <option value="">- {{ __('Seleziona') }} -</option>

                                            @foreach($statuses as $value)
                                            <option wire:key="status-{{$value['status']}}" value="{{$value['status']}}"
                                                @selected($appointment->status == $value['status'])>
                                                {{ __($value['label']) }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @endrole

                                        @role('Admin')
                                        @foreach($statuses as $value)
                                        @if($appointment->status == $value['status'])
                                        <div wire:key="status-{{$value['status']}}"
                                            class="inline-flex justify-center items-center w-full px-3 py-1 rounded-xl font-medium border {{ $statusColor }}">
                                            {{ __($value['label']) }}
                                        </div>
                                        @endif
                                        @endforeach
                                        @endrole
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($appointment->reason)
                                        <x-modal :title="__('Motivazione visita')" confirm-label="reason"
                                            wire:key="reasonAppointment-[{{$appointment->id}}]">
                                            <x-slot:trigger>
                                                <button title="reason"
                                                    class="px-4 py-2 border border-gray-200 hover:bg-gray-50 text-gray-800 rounded-2xl transition-colors cursor-pointer">
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
                                        <x-modal :title="__('Note del medico')" confirm-label="note"
                                            wire:key="notesAppointment-[{{$appointment->id}}]">
                                            <x-slot:trigger>
                                                <button title="note"
                                                    class="px-4 py-2 border border-gray-200 hover:bg-gray-50 text-gray-800 rounded-2xl transition-colors cursor-pointer">
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
                                    <td class="py-4">
                                        <div class="flex space-x-2">
                                            <button wire:navigate href="/patients/details/{{$appointment->patient->id}}"
                                                title="{{__('Dettagli')}}"
                                                class="px-4 py-2 border border-gray-200 hover:bg-gray-50 text-gray-800 rounded-2xl transition-colors cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </button>
                                            <x-modal :title="__('Elimina definitivamente?')" confirm-label="elimina"
                                                wire:key="deleteAppointment-[{{$appointment->id}}]">
                                                <x-slot:trigger>
                                                    <button title="{{__('Elimina')}}"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-800 text-sm font-medium rounded-2xl transition-colors cursor-pointer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                            class="size-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </x-slot:trigger>

                                                <x-slot:body>
                                                    {{ __('Questa operazione è irreversibile') }}
                                                </x-slot:body>

                                                <x-slot:action>
                                                    <button wire:click="deleteAppointment({{$appointment->id}})"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 border border-red-300 bg-red-100 hover:bg-red-200 text-red-900 text-sm font-medium rounded-xl transition-colors">
                                                        {{ __('Elimina') }}
                                                    </button>
                                                </x-slot:action>
                                            </x-modal>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="bg-white px-6 py-8 text-center text-gray-600">
                                        <p>Non ci sono appuntamenti.</p>
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