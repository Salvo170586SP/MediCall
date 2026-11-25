<div>
    <div class="mx-auto max-w-7xl">
        <button wire:navigate href="/guest/dashboard"
            class="mb-5 rounded-full border border-gray-200 bg-white p-4 hover:shadow-sm transition cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
        </button>
    </div>

    <section class="mx-auto mb-5 max-w-7xl rounded-4xl border border-gray-200 bg-white px-6 py-8 lg:px-8">
        <div class="flex items-start gap-4">
            <div class="mt-1 flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">{{ __('Ricevuti') }}</h1>
                <p class="mt-1 text-sm text-slate-600">{{ __('Documentazioni mediche ricevute associati al tuo profilo') }}</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl overflow-hidden rounded-4xl border border-slate-200 bg-white">
        <div
            class="flex flex-col gap-2 border-b border-slate-200 px-5 py-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">{{ __('Documentazioni ricevute') }}</h2>
                <p class="text-sm text-slate-500">{{ __('I più recenti sono mostrati per primi') }}</p>
            </div>

            <div class="flex flex-col gap-5">
                <span class="rounded-lg bg-gray-100 px-4 py-1 font-semibold text-gray-800">
                    {{ $medicalRecordsAsPatient->total() }} {{ __('doc') }}
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
                            {{ __('Dottore') }}</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                            {{ __('Data di visita') }}</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-900 uppercase tracking-wider">
                            {{ __('Diagnosi') }}</th>
                        <th class="px-6 py-3 text-center font-semibold text-slate-900 uppercase tracking-wider">
                            {{ __('Prescrizioni') }}</th>
                        <th class="px-6 py-3 text-center font-semibold text-slate-900 uppercase tracking-wider">
                            {{ __('Consigli') }}</th>
                        <th class="px-6 py-3  text-center font-semibold text-slate-900 uppercase tracking-wider">
                            {{ __('Allegato') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($medicalRecordsAsPatient as $record)
                    <tr class="border-b border-slate-200 text-[14px]">
                        <td class="px-6 py-4 font-medium text-slate-900 capitalize">{{
                            $record->doctor->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{
                            $record->formatDate($record->visit_date) }}</td>
                        <td class="px-6 py-4 text-slate-900">{{ $record->diagnosis }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($record->prescription)
                            <x-modal title="{{ __('Prescrizioni') }}" confirm-label="prescrizioni"
                                wire:key="prescription-[{{$record->id}}]">
                                <x-slot:trigger>
                                    <button title="{{ __('Prescrizioni') }}"
                                        class="p-2 cursor-pointer border border-gray-300 hover:bg-gray-100 rounded-xl transition-colors">
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
                            <x-modal title="{{ __('Consigli') }}" confirm-label="consigli"
                                wire:key="instructions-[{{$record->id}}]">
                                <x-slot:trigger>
                                    <button title="{{ __('Consigli') }}"
                                        class="p-2 cursor-pointer border border-gray-300 hover:bg-gray-100 rounded-xl transition-colors">
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
                            @if($record->medicalRecordDocument)
                            <a download href="{{ asset('storage/'.$record->medicalRecordDocument->file_path) }}"
                                title="{{ __('Scarica') }} {{ $record->medicalRecordDocument->file_path }}"
                                class="inline-flex items-center justify-center rounded-xl border border-gray-300 p-2 text-gray-800 transition-colors hover:bg-gray-100">
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
            {{ __('Non ci sono ancora referti medici da mostrare ') }}
        </div>
        @endif
    </section>
</div>