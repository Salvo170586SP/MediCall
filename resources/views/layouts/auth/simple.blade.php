<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="flex w-full max-w-sm flex-col gap-2">
            <a href="{{ route('home') }}" class="flex flex-col items-center justify-center gap-2 font-medium" wire:navigate>
                <svg class="h-10 w-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.452a6 6 0 00-5.011 1.595m7.988 2.999a8 8 0 01-7.998-8m0 0a8 8 0 018-8 8 8 0 110 16 8 8 0 01-8-8m0 0l2.5 2.5m0 0l2.5-2.5" />
                </svg>
                <div class="text-center">
                    <h2 class="text-xl font-bold text-slate-900">MediCall</h2>
                    <p class="text-md text-slate-500">Gestione sanitaria</p>
                </div>
            </a>
            <div class="flex flex-col gap-6">
                {{ $slot }}
            </div>
        </div>
    </div>

    @persist('toast')
    <flux:toast.group>
        <flux:toast />
    </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>