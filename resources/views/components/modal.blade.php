@props([
'id', // identificatore unico (es. "delete-doctor-{$id}")
'title', // titolo della modale
'confirmLabel' => 'Conferma',
'cancelLabel' => 'Annulla',
'confirmClass' => 'border-red-200 bg-red-50 hover:bg-red-100 text-red-800',
])

<div x-data="{ open: false }" x-cloak>

    {{-- Trigger slot: qualsiasi bottone/link che apre la modale --}}
    <div @click="open = true">
        {{ $trigger }}
    </div>

    <!-- Overlay -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false"
        class="fixed inset-0 bg-black/30 z-40">
    </div>

    <!-- Modal -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl shadow-2xl w-full max-w-md z-50 p-6">

        {{-- Titolo --}}
        <h2 class="w-full text-center font-bold text-gray-800">{{ $title }}</h2>

        {{-- Contenuto opzionale --}}
        @isset($body)
        <div class="mt-3 text-sm text-gray-600 text-center">
            {{ $body }}
        </div>
        @endisset

        {{-- Azioni --}}
        <div class="flex items-center justify-end gap-2 mt-5">
            <button @click="open = false" type="button"
                class="inline-flex items-center gap-1.5 px-4 py-2 border border-gray-200 bg-gray-50 hover:bg-gray-100 text-gray-800 text-sm font-medium rounded-xl transition-colors">
                {{ $cancelLabel }}
            </button>

            {{-- Action slot: il form o qualsiasi azione di conferma --}}
            <div @click="open = false">
                {{ $action }}
            </div>
        </div>
    </div>
</div>