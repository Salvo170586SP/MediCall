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
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900">{{ __('Inviati') }}</h1>
                <p class="mt-1 text-sm text-slate-600">{{ __('Le tue documentazioni inviate associati al tuo profilo')
                    }}</p>
            </div>
        </div>
    </section>

    <section x-data="{isOpen: false}" class="mx-auto mb-5 max-w-7xl">
        <div class="text-end">
            <button class="rounded-xl cursor-pointer bg-black p-3 text-white transition hover:bg-gray-700"
                @click="isOpen = !isOpen">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </button>
        </div>
        <div x-show="isOpen" x-transition
            class="mx-auto mb-5 max-w-7xl rounded-4xl border border-gray-200 bg-white p-6 mt-5">
            <h2 class="mb-1 text-lg font-semibold text-slate-950">{{ __('Invia un documento') }}</h2>
            <p class="mb-5 text-sm text-slate-500">
                {{ __('Seleziona uno dei dottori associati al tuo profilo e carica il documento.') }}
            </p>

            @if ($successMessage)
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ $successMessage }}
            </div>
            @endif

            <form wire:submit="sendDocument" class="grid gap-4 md:grid-cols-2">
                <div>
                    <label for="doctor_id" class="mb-2 block text-sm font-medium text-slate-700">
                        {{ __('Dottore') }}
                    </label>
                    <select id="doctor_id" wire:model="doctor_id"
                        class="w-full rounded-xl border border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500 p-2">
                        <option value="">- {{ __('Seleziona') }} -</option>
                        @foreach ($associatedDoctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="title" class="mb-2 block text-sm font-medium text-slate-700">
                        {{ __('Nome') }}
                    </label>
                    <input id="title" type="text" wire:model="title" maxlength="255"
                        class="w-full rounded-xl border border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500 p-2"
                        placeholder="{{ __('Es. Analisi del sangue') }}">
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="document_type" class="mb-2 block text-sm font-medium text-slate-700">
                        {{ __('Tipo') }}
                    </label>
                    <select id="document_type" wire:model="document_type"
                        class="w-full rounded-xl border border-slate-300 text-sm focus:border-sky-500 focus:ring-sky-500 p-2">
                        <option value="">- seleziona -</option>
                        @foreach(App\Enums\DocumentType::cases() as $case)
                        <option value="{{ $case }}">{{ $case->label() }}</option>
                        @endforeach
                    </select>
                    @error('document_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col items-start justify-start">
                    <span class=" text-sm font-medium mb-2">{{ __('Documento') }}</span>
                    <label for="document"
                        class="text-center font-bold bg-slate-50 rounded-xl text-gray-500 px-4 py-2 hover:bg-gray-100 cursor-pointer">
                        {{ __('Seleziona file') }}
                        <input id="document" type="file" wire:model="document" accept=".jpg,.jpeg,.png,.webp,.pdf"
                            class="hidden ">
                    </label>
                    @if ($document)
                    <p class="mt-1 text-sm text-slate-600 font-semibold">
                        File selezionato: <span class="font-normal">{{ $document->getClientOriginalName() }}</span>
                    </p>
                    @endif
                    @error('document')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <button type="submit"
                        class="inline-flex items-center rounded-xl bg-black px-5 py-2.5 font-semibold text-white transition hover:bg-gray-700 cursor-pointer"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ __('Invia') }}</span>
                        <span wire:loading>{{ __('Invio in corso...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="mx-auto mb-5 max-w-7xl overflow-hidden rounded-4xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 px-5 py-5">
            <h2 class="text-lg font-semibold text-slate-950">{{ __('Documentazioni inviate') }}</h2>
        </div>

        @if ($sentDocuments->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-6 py-3">{{ __('Documento') }}</th>
                        <th class="px-6 py-3">{{ __('Tipo') }}</th>
                        <th class="px-6 py-3">{{ __('Dottore') }}</th>
                        <th class="px-6 py-3">{{ __('Data') }}</th>
                        <th class="px-6 py-3 text-center">{{ __('Scarica') }}</th>
                        <th class="px-6 py-3 text-center">{{ __('Elimina') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($sentDocuments as $sentDocument)
                    <tr>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $sentDocument->title }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $sentDocument->type->label() }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $sentDocument->recipient?->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $sentDocument->created_at->translatedFormat('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a download href="{{ asset('storage/'.$sentDocument->file_path) }}"
                                title="{{ __('Scarica') }} {{ $sentDocument->original_filename }}"
                                class="inline-flex p-2 cursor-pointer border border-gray-300 hover:bg-gray-100 rounded-xl transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12M12 16.5V3" />
                                </svg>
                            </a>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <x-modal title="{{ __('Elimina definitivamente?') }}" confirm-label="Elimina"
                                confirm-class="border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
                                <x-slot:trigger>
                                    <button title="{{ __('Elimina') }}"
                                        class="p-2 bg-red-100 hover:bg-red-200 text-red-800 rounded-xl transition-colors cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </x-slot:trigger>

                                <x-slot:body>
                                    <div class="w-[400px]">
                                        {{ __('Questa operazione è irreversibile') }}
                                    </div>
                                </x-slot:body>

                                <x-slot:action>
                                    <button wire:click="deleteDoc({{$sentDocument->id}})"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-100 hover:bg-red-200 text-red-900 text-sm font-medium rounded-xl transition-colors">
                                        {{ __('Elimina') }}
                                    </button>
                                </x-slot:action>

                            </x-modal>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="m-6">
            {{ $sentDocuments->links() }}
        </div>
        @else
        <div class="px-5 py-12 text-center text-sm text-slate-500">
            {{ __('Non hai ancora inviato documenti ai dottori.') }}
        </div>
        @endif
    </section>
</div>