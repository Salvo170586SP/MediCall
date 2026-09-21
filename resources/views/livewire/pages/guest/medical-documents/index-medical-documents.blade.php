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
                <h1 class="text-xl font-bold text-slate-900">I tuoi referti medici</h1>
                <p class="mt-1 text-sm text-slate-600">I referti medici associati al tuo profilo.</p>
            </div>
        </div>
    </section>
    <section class="mx-auto max-w-7xl overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div
            class="flex flex-col gap-2 border-b border-slate-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">Referti medici</h2>
                <p class="text-sm text-slate-500">I referti più recenti sono mostrati per primi.</p>
            </div>

            <div class="flex flex-col gap-5">
                <span
                    class="inline-flex w-fit items-center rounded-lg bg-sky-100 px-3 py-1 text-sm font-semibold text-sky-800">
                    {{ $medicalRecordsAsPatient->total() }} {{ $medicalRecordsAsPatient->total() === 1 ? 'referto' :
                    'referti' }}
                </span>
            </div>
        </div>


        @if ($medicalRecordsAsPatient->isNotEmpty())
        <!-- Paginazione -->
        <div class="m-6">
            {{ $medicalRecordsAsPatient->links() }}
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                            Medico</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                            Data di Visita</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                            Diagnosi</th>
                        <th class="px-6 py-3 text-center font-semibold text-slate-900 uppercase tracking-wider">
                            Prescrizioni</th>
                        <th class="px-6 py-3 text-center font-semibold text-slate-900 uppercase tracking-wider">
                            Consigli</th>
                        <th class="px-6 py-3  text-center font-semibold text-slate-900 uppercase tracking-wider">
                            Prossimo controllo il</th>
                        <th class="px-6 py-3  text-center font-semibold text-slate-900 uppercase tracking-wider">
                            Allegato</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($medicalRecordsAsPatient as $record)
                    <tr class="border-b border-slate-200 hover:bg-slate-50 transition text-[14px]">
                        <td class="px-6 py-4 font-medium text-slate-900 capitalize">{{
                            $record->doctor->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{
                            $record->formatDate($record->visit_date) }}</td>
                        <td class="px-6 py-4 text-slate-900">{{ $record->diagnosis }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($record->prescription)
                            <x-modal title="Prescrizioni" confirm-label="prescrizioni"
                                wire:key="prescription-[{{$record->id}}]"
                                confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                <x-slot:trigger>
                                    <button title="note"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-100 hover:bg-sky-200 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
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
                        <td class="px-6 py-4 text-center">
                            @if($record->instructions)
                            <x-modal title="Consigli" confirm-label="consigli" wire:key="instructions-[{{$record->id}}]"
                                confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                <x-slot:trigger>
                                    <button title="note"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-sky-100 hover:bg-sky-200 text-sky-800 text-sm font-medium rounded-xl transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
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
                            @if($record->medicalRecordDocument)
                            <a download href="{{ asset('storage/'.$record->medicalRecordDocument->file_path) }}"
                                title="Scarica {{ $record->medicalRecordDocument->file_path }}"
                                class="inline-flex items-center justify-center rounded-xl bg-emerald-100 p-2 text-emerald-800 transition-colors hover:bg-emerald-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12M12 16.5V3" />
                                </svg>
                            </a>
                            @else
                            -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-5 py-12 text-center text-sm text-slate-500">
            Non ci sono ancora referti medici da mostrare.
        </div>
        @endif
    </section>
</div>