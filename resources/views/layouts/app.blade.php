<x-layouts::app.header :title="$title ?? null">
    <flux:main>

        <nav class="max-lg:hidden -mt-3 mb-5 flex justify-center items-center w-full gap-5 text-sm">
            <button wire:navigate
                class="cursor-pointer shadow border border-zinc-300 font-medium px-3 py-1 rounded-full {{request()->routeIs('dashboard') ? 'bg-gray-400 text-white' : ' bg-zinc-50 text-zinc-500'}}"
                href="{{route('dashboard')}}">
                {{ __('Dashboard') }}
            </button>
            <button wire:navigate href="{{route('doctors')}}"
                class="cursor-pointer shadow border border-zinc-300 font-medium px-3 py-1 rounded-full {{request()->routeIs('doctors') ? 'bg-gray-400 text-white' : ' bg-zinc-50 text-zinc-500'}}">
                {{ __('Dottori') }}
            </button>
            <button wire:navigate href="{{route('patients')}}"
                class="cursor-pointer shadow border border-zinc-300 font-medium px-3 py-1 rounded-full {{request()->routeIs('patients') ? 'bg-gray-400 text-white' : 'bg-zinc-50 text-zinc-500'}}">
                {{ __('Pazienti') }}
            </button>
        </nav>

        {{ $slot }}
    </flux:main>
</x-layouts::app.header>