@props([
'id',
'is_active',
'confirmClass' => 'border-red-200 bg-red-50 hover:bg-red-100 text-red-800',
'closeOnClick' => true, // comportamento di default invariato per le altre modali
'closeOnEvent' => null, // nome evento Livewire che chiude la modale
])

<div x-data="{ open: @js($is_active) }" x-cloak @if ($closeOnEvent) x-on:{{ $closeOnEvent }}.window="open = false"
    @endif>
    <!-- Overlay -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/30 z-40">
    </div>

    <!-- Modal -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="z-50 fixed top-1/2 left-1/2 max-h-[90vh] w-[calc(100%-2rem)] max-w-4xl -translate-x-1/2 -translate-y-1/2 overflow-y-auto rounded-xl bg-white p-6 shadow-2xl sm:p-8">

        {{-- Titolo --}}
        <div class="text-center">
            <p class="text-sm font-semibold uppercase tracking-wide text-sky-600">MediCall</p>
            <h2 class="mt-1 w-full text-2xl font-bold text-gray-800">Benvenuto nella nostra piattaforma</h2>
            <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-gray-600">
                Tutto ciò che serve per organizzare e seguire il percorso sanitario in un unico spazio, con informazioni
                sempre ordinate e facilmente accessibili.
            </p>
        </div>

        {{-- Funzionalita principali --}}
        <div class="mt-6 grid gap-3 sm:grid-cols-2">
            <div class="rounded-lg border border-sky-100 bg-sky-50 p-4">
                <h3 class="font-semibold text-sky-900">Dashboard e visione d'insieme</h3>
                <p class="mt-1 text-sm leading-5 text-sky-800">
                    Controlla rapidamente appuntamenti, pazienti, documenti e cartelle cliniche recenti.
                </p>
            </div>

            <div class="rounded-lg border border-emerald-100 bg-emerald-50 p-4">
                <h3 class="font-semibold text-emerald-900">Appuntamenti</h3>
                <p class="mt-1 text-sm leading-5 text-emerald-800">
                    Consulta le visite programmate e tieni sotto controllo date, orari, stato e dettagli degli incontri.
                </p>
            </div>

            <div class="rounded-lg border border-violet-100 bg-violet-50 p-4">
                <h3 class="font-semibold text-violet-900">Pazienti e professionisti</h3>
                <p class="mt-1 text-sm leading-5 text-violet-800">
                    Gestisci profili e informazioni anagrafiche, con accessi e strumenti dedicati al tuo ruolo.
                </p>
            </div>

            <div class="rounded-lg border border-amber-100 bg-amber-50 p-4">
                <h3 class="font-semibold text-amber-900">Cartelle e documenti</h3>
                <p class="mt-1 text-sm leading-5 text-amber-800">
                    Raccogli cartelle cliniche, referti e altri documenti per ritrovare la storia sanitaria quando serve.
                </p>
            </div>

            <div class="rounded-lg border border-cyan-100 bg-cyan-50 p-4 sm:col-span-2">
                <h3 class="font-semibold text-cyan-900">Monitoraggio della salute</h3>
                <p class="mt-1 text-sm leading-5 text-cyan-800">
                    Registra i parametri di salute, osserva l'andamento nel tempo e usa le soglie configurate per
                    individuare più facilmente i valori che meritano attenzione.
                </p>
            </div>
        </div>

        <p class="mt-5 text-center text-xs leading-5 text-gray-500">
            Per ulteriori informazioni non esitare a contattarci.
        </p>

        {{-- Azioni --}}
        <div class="mt-6 flex items-center justify-end gap-2">
            <div @if ($closeOnClick) @click="open = false" @endif>
                {{ $action }}
            </div>
        </div>
    </div>
</div>