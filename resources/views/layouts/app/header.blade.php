<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">

    <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">

        <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

        <div class="shrink-0 flex items-center justify-center">
            <figure class="w-[45px] border rounded-full overflow-hidden shadow me-3">
                <img class="w-full object-fit" src="{{asset('assets/imgs/logo.png')}}" alt="logo">
            </figure>
            <h2 class="font-bold text-sky-800">MediCall</h2>
        </div>

        <flux:spacer />

        <x-desktop-user-menu />
    </flux:header>





    <!-- Mobile Menu -->
    <flux:sidebar collapsible="mobile" sticky
        class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <div class="shrink-0 flex items-center justify-center">
                <figure class="w-[45px] border rounded-full overflow-hidden shadow me-3">
                    <img class="w-full object-fit" src="{{asset('assets/imgs/logo.png')}}" alt="logo">
                </figure>
                <h2 class="font-bold text-sky-800">MediCall</h2>
            </div>

            <flux:sidebar.collapse
                class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Platform')">
                <flux:sidebar.item icon="layout-grid" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                @role('Admin')
                <flux:sidebar.item wire:navigate :href="route('doctors')" icon="users"
                    :current="request()->routeIs('doctors')">
                    {{ __('Dottori') }}
                </flux:sidebar.item>
                @endrole
                
                @role('Doctor')
                <flux:sidebar.item icon="user" :href="route('doctor.profile')"
                    :current="request()->routeIs('doctor.profile')" wire:navigate>
                    {{ __('Il mio profilo') }}
                </flux:sidebar.item>
                @endrole
                
                <flux:sidebar.item wire:navigate :href="route('patients')" icon="users"
                    :current="request()->routeIs('patients')">
                    {{ __('Pazienti') }}
                </flux:sidebar.item>

                <flux:sidebar.item wire:navigate :href="route('documents')" icon="users"
                    :current="request()->routeIs('documents')">
                    {{ __('Documenti') }}
                </flux:sidebar.item>

            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />


    </flux:sidebar>



    {{ $slot }}

    @persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>