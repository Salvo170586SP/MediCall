<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">

    <flux:header class="border-b border-zinc-200 bg-zinc-50">
        @role(['Doctor', 'Admin'])
        <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />
        @endrole
        
        <div class="shrink-0 flex items-center justify-center gap-3">
            <x-icon.medicall-ico />
            <div>
                <h2 class="text-sm font-bold text-slate-900">MediCall</h2>
                <p class="text-xs text-slate-500">{{ __('Gestione sanitaria') }}</p>
            </div>
        </div>

        <flux:spacer />

        @role(['Guest','Doctor'])
        <div class="flex items-center gap-8">
            <x-accordion-notifications title="Notifiche" />
        </div>
        @endrole

        <div class="me-15">
            <livewire:components.language-switcher />
        </div>

        <flux:dropdown position="bottom" align="start">
            @role('Guest')
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                    class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100"
                    data-test="logout-button">
                    {{ __('Log out') }}
                </flux:menu.item>
            </form>
            @endrole
        </flux:dropdown>

        @role(['Admin','Doctor'])
        <div class="flex items-center">
            <x-desktop-user-menu />
        </div>
        @endrole
    </flux:header>




    <!-- Mobile Menu -->
    @role(['Doctor', 'Admin'])
    <flux:sidebar collapsible="mobile" sticky
        class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <div class="shrink-0 flex items-center justify-center gap-3">
                <svg class="h-10 w-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.452a6 6 0 00-5.011 1.595m7.988 2.999a8 8 0 01-7.998-8m0 0a8 8 0 018-8 8 8 0 110 16 8 8 0 01-8-8m0 0l2.5 2.5m0 0l2.5-2.5" />
                </svg>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">MediCall</h2>
                    <p class="text-xs text-slate-500">{{ __('Gestione sanitaria') }}</p>
                </div>
            </div>

            <flux:sidebar.collapse
                class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Platform')">
                @role(['Doctor', 'Admin'])
                <flux:sidebar.item icon="layout-grid" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>
                @endrole

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

                @role(['Doctor', 'Admin'])
                <flux:sidebar.item wire:navigate :href="route('patients')" icon="users"
                    :current="request()->routeIs('patients')">
                    {{ __('Pazienti') }}
                </flux:sidebar.item>

                <flux:sidebar.item wire:navigate :href="route('documents')" icon="users"
                    :current="request()->routeIs('documents')">
                    {{ __('Documenti') }}
                </flux:sidebar.item>
                @endrole
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />


    </flux:sidebar>
    @endrole



    {{ $slot }}

    @persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>