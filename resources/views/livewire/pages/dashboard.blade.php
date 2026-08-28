<div>
    <div class="mb-8 flex flex-col gap-2">
        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-sky-700">Panoramica</p>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-950">Dashboard</h1>
        <p class="text-sm text-slate-500">Una sintesi aggiornata dell'attività di MediCall.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <flux:card class="border-sky-100 bg-sky-50/70 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-600">Pazienti</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-950">{{ number_format($patientsCount, 0, ',', '.')
                        }}</p>
                </div>
                <span class="flex size-11 items-center justify-center rounded-xl bg-sky-600 text-white">
                    <flux:icon name="user-group" class="size-6" />
                </span>
            </div>
        </flux:card>

        <flux:card class="border-emerald-100 bg-emerald-50/70 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-600">Dottori</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-950">{{ number_format($doctorsCount, 0, ',', '.')
                        }}</p>
                </div>
                <span class="flex size-11 items-center justify-center rounded-xl bg-emerald-600 text-white">
                    <flux:icon name="user-plus" class="size-6" />
                </span>
            </div>
        </flux:card>

        <flux:card class="border-amber-100 bg-amber-50/70 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-600">Appuntamenti</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-950">{{ number_format($appointmentsCount, 0, ',',
                        '.') }}</p>
                </div>
                <span class="flex size-11 items-center justify-center rounded-xl bg-amber-500 text-white">
                    <flux:icon name="calendar-days" class="size-6" />
                </span>
            </div>
        </flux:card>

        <flux:card class="border-violet-100 bg-violet-50/70 p-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-600">Archivio complessivo</p>
                    <p class="mt-3 text-3xl font-semibold text-slate-950">
                        {{ number_format($documentsCount /* + $medicalRecordsCount */, 0, ',', '.') }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">{{ $documentsCount }} documenti · {{ $medicalRecordsCount }}
                        cartelle cliniche</p>
                </div>
                <span class="flex size-11 items-center justify-center rounded-xl bg-violet-600 text-white">
                    <flux:icon name="archive-box" class="size-6" />
                </span>
            </div>
        </flux:card>
    </div>

    <section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div
            class="flex flex-col gap-2 border-b border-slate-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">Appuntamenti recenti</h2>
                <p class="text-sm text-slate-500">Gli ultimi 10 appuntamenti registrati, dal più recente.</p>
            </div>
            <flux:badge color="sky">{{ $latestAppointments->count() }} di {{ $appointmentsCount }}</flux:badge>
        </div>

        @if($latestAppointments->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Data e ora</th>
                        <th class="px-5 py-3 font-semibold">Paziente</th>
                        <th class="px-5 py-3 font-semibold">Dottore</th>
                        <th class="px-5 py-3 font-semibold">Stato</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($latestAppointments as $appointment)
                    <tr wire:key="dashboard-appointment-{{ $appointment->id }}" class="text-slate-700">
                        <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-950">
                            {{ $appointment->scheduled_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-5 py-4">{{ $appointment->patient?->name ?? 'Paziente rimosso' }}</td>
                        <td class="px-5 py-4">{{ $appointment->doctor?->name ?? 'Dottore rimosso' }}</td>
                        <td class="px-5 py-4">
                            @foreach($statuses as $value)
                            <div wire:key="statuses-[{{$appointment->id}}-{{$value['status']}}]">
                                @if($appointment->status == $value['status'] )
                                <span
                                    class="inline-flex justify-center items-center font-bold px-3 py-1 rounded-lg {{$appointment->status == $value['status'] ? $value['color'] : '' }}">
                                    {{ $value['label'] }}
                                </span>
                                @endif
                            </div>
                            @endforeach
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-5 py-12 text-center text-sm text-slate-500">Non ci sono ancora appuntamenti da mostrare.</div>
        @endif
    </section>
</div>