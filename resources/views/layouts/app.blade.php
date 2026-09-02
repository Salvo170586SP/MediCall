<x-layouts::app.header :title="$title ?? null">
    <flux:main>
        @role(['Admin','Doctor'])
        <nav class="max-lg:hidden mb-5 flex justify-center items-center w-full gap-5 text-sm">
            <button wire:navigate
                class="flex items-center justify-between cursor-pointer font-bold text-[15px] px-4 py-1 rounded-lg {{request()->routeIs('dashboard') ? 'bg-sky-100 text-sky-800' : ' bg-zinc-100 text-zinc-900 hover:bg-zinc-200'}}"
                href="{{route('dashboard')}}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="M9.293 2.293a1 1 0 0 1 1.414 0l7 7A1 1 0 0 1 17 11h-1v6a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-6H3a1 1 0 0 1-.707-1.707l7-7Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="ms-2">
                    {{ __('Dashboard') }}
                </div>
            </button>
            
            @role('Admin')
            <button wire:navigate href="{{route('doctors')}}"
                class="flex items-center justify-between cursor-pointer text-[15px] font-bold px-4 py-1 rounded-lg {{request()->routeIs('doctors') ? 'bg-sky-100 text-sky-700' : 'bg-zinc-100 text-zinc-900 hover:bg-zinc-200'}}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="M2.625 6.75a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875 0A.75.75 0 0 1 8.25 6h12a.75.75 0 0 1 0 1.5h-12a.75.75 0 0 1-.75-.75ZM2.625 12a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0ZM7.5 12a.75.75 0 0 1 .75-.75h12a.75.75 0 0 1 0 1.5h-12A.75.75 0 0 1 7.5 12Zm-4.875 5.25a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875 0a.75.75 0 0 1 .75-.75h12a.75.75 0 0 1 0 1.5h-12a.75.75 0 0 1-.75-.75Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="ms-2">
                    {{ __('Lista Dottori') }}
                </div>
            </button>
            @endrole

            @role('Doctor')
            <button wire:navigate href="{{route('doctor.profile')}}"
                class="flex items-center justify-between cursor-pointer text-[15px] font-bold px-4 py-1 rounded-lg {{request()->routeIs('doctor.profile') ? 'bg-sky-100 text-sky-700' : 'bg-zinc-100 text-zinc-900 hover:bg-zinc-200'}}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="ms-2">
                    {{ __('Il mio profilo') }}
                </div>
            </button>
            @endrole

            <button wire:navigate href="{{route('patients')}}"
                class="flex items-center justify-between cursor-pointer text-[15px] font-bold px-4 py-1 rounded-lg {{request()->routeIs('patients') ? 'bg-sky-100 text-sky-700' : 'bg-zinc-100 text-zinc-900 hover:bg-zinc-200'}}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd"
                        d="M2.625 6.75a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875 0A.75.75 0 0 1 8.25 6h12a.75.75 0 0 1 0 1.5h-12a.75.75 0 0 1-.75-.75ZM2.625 12a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0ZM7.5 12a.75.75 0 0 1 .75-.75h12a.75.75 0 0 1 0 1.5h-12A.75.75 0 0 1 7.5 12Zm-4.875 5.25a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875 0a.75.75 0 0 1 .75-.75h12a.75.75 0 0 1 0 1.5h-12a.75.75 0 0 1-.75-.75Z"
                        clip-rule="evenodd" />
                </svg>
                <div class="ms-2">
                    @role('Admin')
                    {{ __('Lista Pazienti') }}
                    @endrole
                    @role('Doctor')
                    {{ __('I miei Pazienti') }}
                    @endrole
                </div>
            </button>

            <button wire:navigate href="{{route('documents')}}"
                class="flex items-center justify-between cursor-pointer text-[15px] font-bold px-4 py-1 rounded-lg {{request()->routeIs('documents') ? 'bg-sky-100 text-sky-700' : 'bg-zinc-100 text-zinc-900 hover:bg-zinc-200'}}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-4">
                    <path d="M21 6.375c0 2.692-4.03 4.875-9 4.875S3 9.067 3 6.375 7.03 1.5 12 1.5s9 2.183 9 4.875Z" />
                    <path
                        d="M12 12.75c2.685 0 5.19-.586 7.078-1.609a8.283 8.283 0 0 0 1.897-1.384c.016.121.025.244.025.368C21 12.817 16.97 15 12 15s-9-2.183-9-4.875c0-.124.009-.247.025-.368a8.285 8.285 0 0 0 1.897 1.384C6.809 12.164 9.315 12.75 12 12.75Z" />
                    <path
                        d="M12 16.5c2.685 0 5.19-.586 7.078-1.609a8.282 8.282 0 0 0 1.897-1.384c.016.121.025.244.025.368 0 2.692-4.03 4.875-9 4.875s-9-2.183-9-4.875c0-.124.009-.247.025-.368a8.284 8.284 0 0 0 1.897 1.384C6.809 15.914 9.315 16.5 12 16.5Z" />
                    <path
                        d="M12 20.25c2.685 0 5.19-.586 7.078-1.609a8.282 8.282 0 0 0 1.897-1.384c.016.121.025.244.025.368 0 2.692-4.03 4.875-9 4.875s-9-2.183-9-4.875c0-.124.009-.247.025-.368a8.284 8.284 0 0 0 1.897 1.384C6.809 19.664 9.315 20.25 12 20.25Z" />
                </svg>
                <div class="ms-2">
                    {{ __('Archivio') }}
                </div>
            </button>
        </nav>
        @endrole
        
        {{ $slot }}
    </flux:main>
</x-layouts::app.header>