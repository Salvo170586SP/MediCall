<div>
    @if(auth()->user()->guestDetail?->is_active)
    <x-welcome-modal id="welcome-guest-{{ auth()->user()->id }}" :is_active="auth()->user()->guestDetail?->is_active">
        <x-slot:action>
            <button wire:click="closeWelcomeModal({{auth()->user()->id}})"
                class="inline-flex gap-2 px-3 py-2 text-sm cursor-pointer rounded-xl bg-slate-100 hover:bg-slate-100 font-semibold text-slate-800">
                {{ __('Chiudi') }}
            </button>
        </x-slot:action>
    </x-welcome-modal>
    @endif


    <section class="max-w-7xl mx-auto mb-5 flex flex-col sm:flex-row gap-5 ">
        <div
            class="w-full flex flex-col items-start justify-between gap-4 border border-emerald-100 rounded-4xl bg-white px-6 py-8 lg:px-8">
            <div>
                <p class="text-slate-500 font-semibold mb-3">{{ __('La tua area personale') }}</p>
                <h2 class="text-3xl font-bold text-slate-900">{{ __('Benvenuto') }}, {{ auth()->user()->name }}</h2>
                <p class="mt-1 text-slate-600 text-xs lg:text-base">{{ __('Consulta appuntamenti, cartelle cliniche e comunicazioni direttamente dalla home di MediCall.') }}</p>
            </div>
            <div
                class="w-full bg-gray-100 p-3 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                <p class="text-slate-600 font-medium">{{ __('Completa i tuoi dati per ricevere un servizio più preciso.') }} </p>
                <button wire:navigate href="/guest/edit/{{auth()->user()->id}}"
                    class="inline-flex justify-center sm:justify-start gap-2 p-3 cursor-pointer rounded-xl bg-gray-500 hover:bg-gray-600 font-bold text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                    {{ __('Modifica il mio profilo') }}</button>

            </div>
        </div>

        <div class="sm:w-130 border border-emerald-100 rounded-4xl bg-white px-6 py-8 lg:px-8 space-y-5">
            <div class="flex gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                    <span class="font-bold text-gray-500">{{ auth()->user()->initials()}}</span>
                </div>
                <div class="flex flex-col">
                    <h2 class="text-lg font-semibold"> {{ auth()->user()->name }}</h2>
                    <p class="font-medium text-slate-500 text-sm">{{ __('Utente') }}: <span
                            class="font-bold text-sm">#{{
                            auth()->user()->guestDetail?->code_item }}</span>
                    </p>
                </div>
            </div>
            <div class="w-full text-sm font-medium space-y-2 text-slate-600">
                <p class="inline-flex justify-between w-full">{{ __('Nato a') }}: <span
                        class="font-bold text-black">{{auth()->user()->guestDetail?->birth_city}}</span></p>
                <p class="inline-flex justify-between w-full">{{ __('Data di nascita') }}: <span
                        class="font-bold text-black">{{auth()->user()->guestDetail?->formatDate(auth()->user()->guestDetail->birth_date)}}</span>
                </p>
                <p class="inline-flex justify-between w-full">{{ __('Codice fiscale') }}: <span
                        class="font-bold text-black">{{
                        auth()->user()->guestDetail?->tax_id_code }}</span></p>
                <p class="inline-flex justify-between w-full">{{ __('Telefono') }}: <span
                        class="font-bold text-black">{{
                        auth()->user()->guestDetail?->phone
                        }}</span></p>
            </div>
        </div>

    </section>

    <div class="h-full max-w-7xl mx-auto my-5">
        <h3 class="font-bold text-xl">Il tuo spazio</h3>
    </div>

    <section class="max-w-7xl mx-auto flex flex-col gap-3 lg:grid grid-cols-3">

        {{-- * Allergie --}}
        <div
            class="relative flex flex-col items-start justify-between gap-4 border border-emerald-100 rounded-4xl bg-white p-7">
            <div class="w-12 h-12 bg-gray-100 border border-gray-200 rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-bold text-slate-700">{{ __('Le tue allergie') }}</h2>
                <p class="text-sm font-bold text-slate-500">{{ __('Allergie e intolleranze registrate.') }}</p>
            </div>
            <div class="space-x-2 flex">
                <x-modal title="{{ __('Visualizza') }}" confirm-label="Allergies"
                    wire:key="allergies-modal-{{ $authenticatedUser->id }}">
                    <x-slot:trigger>
                        <button title="{{ __('Visualizza') }}"
                            class="px-4 py-3 cursor-pointer rounded-xl bg-gray-500 hover:bg-gray-600 font-bold text-white">
                            {{ __('Visualizza') }}
                        </button>
                    </x-slot:trigger>

                    <x-slot:body>
                        {{ $allergies ?: __('Nessuna allergia') }}
                    </x-slot:body>

                    <x-slot:action>
                    </x-slot:action>
                </x-modal>

                <livewire:pages.guest.allergies.edit-allergies wire:key="edit-allergies-{{ $authenticatedUser->id }}"
                    :user="$authenticatedUser" />
            </div>
        </div>

        {{-- * La tua storia clinica --}}
        <div
            class="relative flex flex-col items-start justify-between gap-4 border border-emerald-100 rounded-4xl bg-white p-7">
            <div class="w-12 h-12 bg-gray-100 border border-gray-200 rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-bold text-slate-700">{{ __('La tua storia clinica') }}</h2>
                <p class="text-sm font-bold text-slate-500">{{ __('Patologie, interventi e note cliniche.') }}</p>
            </div>

            <div class="space-x-2 flex">
                <x-modal title="{{ __('La tua storia clinica') }}" confirm-label="medical_history"
                    wire:key="medical-history-modal-{{ $authenticatedUser->id }}">
                    <x-slot:trigger>
                        <button title="{{ __('La tua storia clinica') }}"
                            class="px-4 py-3 cursor-pointer rounded-xl bg-gray-500 hover:bg-gray-600 font-bold text-white">
                            {{ __('Visualizza') }}
                        </button>
                    </x-slot:trigger>

                    <x-slot:body>
                        {{ $medical_history ?: __('Nessuna storia clinica') }}
                    </x-slot:body>

                    <x-slot:action>
                    </x-slot:action>
                </x-modal>

                <livewire:pages.guest.medical-history.edit-medical-history
                    wire:key="edit-medical-history-{{ $authenticatedUser->id }}" :user="$authenticatedUser" />
            </div>
        </div>

        {{-- I tuoi appuntamenti --}}
        <div
            class="relative flex flex-col items-start justify-between gap-4 border border-emerald-100 rounded-4xl bg-white p-7">
            <div
                class="w-12 h-12 bg-gray-100 border border-gray-200 rounded-xl flex items-center justify-center relative">
                @if ($hasNewAppointment)
                <span class="w-3 h-3 rounded-full absolute right-0 top-0 bg-blue-800" title="nuovo appuntamento"></span>
                @endif
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-bold text-slate-700">{{ __('I tuoi appuntamenti') }}</h2>
                <p class="text-sm font-bold text-slate-500">
                    {{ $appointments->count() }} {{ __('appuntamenti presi') }}
                </p>
            </div>

            <div class="space-x-2 flex">
                <button wire:navigate href="/guest/appointments"
                    class="px-4 py-3 cursor-pointer rounded-xl bg-gray-500 hover:bg-gray-600 font-bold text-white">
                    {{ __('Appuntamenti presi') }}
                </button>
            </div>
        </div>

        {{-- * I tuoi parametri --}}
        <div
            class="relative flex flex-col items-start justify-between gap-4 border border-emerald-100 rounded-4xl bg-white p-7">
            <div class="w-full flex justify-between items-start">
                <div class="w-12 h-12 bg-gray-100 border border-gray-200 rounded-xl flex items-center justify-center"">
                    <svg xmlns=" http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6  text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold bg-gray-100 text-gray-500 px-3 py-1 rounded-xl">
                    {{ $parameters->count() }} {{ __('misurati') }}
                </span>
            </div>

            <div class="flex flex-col">
                <h2 class="text-xl font-bold text-slate-700">{{ __('I tuoi parametri') }}</h2>
                <p class="text-sm font-bold text-slate-500">
                    {{ __('Registra e segui i valori nel tempo.') }}
                </p>
            </div>
            <div class=" space-x-2 flex">
                <button wire:navigate href="/guest/parameters/create"
                    class="px-4 py-3 cursor-pointer rounded-xl bg-gray-500 hover:bg-gray-600 font-bold text-white">
                    {{ __('Aggiungi') }}
                </button>
                <button wire:navigate href="/guest/parameters"
                    class="px-4 py-3 cursor-pointer rounded-xl border border-gray-300 hover:bg-gray-100 font-bold text-black">
                    {{ __('Storico') }}</button>
            </div>
        </div>

        {{-- I miei referti --}}
        <div
            class="relative flex flex-col items-start justify-between gap-4 border border-emerald-100 rounded-4xl bg-white p-7">
            <div class="w-full flex justify-between items-start">
                <div
                    class="w-12 h-12 bg-gray-100 border border-gray-200 rounded-xl flex items-center justify-center relative">
                    @if ($hasNewReport)
                    <span class="w-3 h-3 rounded-full absolute right-0 top-0 bg-blue-800" title="nuovo referto"></span>
                    @endif
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 text-gray-700">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                    </svg>
                </div>
                <span class="text-sm font-semibold bg-gray-100 text-gray-500 px-3 py-1 rounded-xl">
                    {{ $medical_records->count() }} {{ __('ricevuti') }}
                </span>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-bold text-slate-700">{{ __('I miei referti') }}</h2>
                <p class="text-sm font-bold text-slate-500">{{ __('Consulta e condividi i tuoi referti.') }}</p>
            </div>
            <div class="space-x-2 flex">
                <button wire:navigate href="/guest/medical-documents"
                    class="px-4 py-3 cursor-pointer rounded-xl bg-gray-500 hover:bg-gray-600 font-bold text-white">
                    {{ __('Ricevuti') }}</button>
                <button wire:navigate href="/guest/upload-documents"
                    class="px-4 py-3 cursor-pointer rounded-xl border border-gray-300 hover:bg-gray-100 font-bold text-black">
                    {{ __('Inviati') }}</button>
            </div>
        </div>

        {{-- Lista medici --}}
        <div
            class="relative flex flex-col items-start justify-between gap-4 border border-emerald-100 rounded-4xl bg-white p-7">
            <div class="w-12 h-12 bg-gray-100 border border-gray-200 rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
            <div class="flex flex-col">
                <h2 class="text-xl font-bold text-slate-700">{{ __('Lista medici') }}</h2>
                <p class="text-sm font-bold text-slate-500">{{ __('Trova e contatta nuovi specialisti.') }}</p>
            </div>

            <div class="space-x-2 flex">
                <button wire:navigate href="/guest/doctors"
                    class="flex items-center gap-3 px-4 py-3 cursor-pointer rounded-xl bg-gray-500 hover:bg-gray-600 font-bold text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    {{ __('Trova nuovo medico') }}</button>
            </div>
        </div>

    </section>
</div>