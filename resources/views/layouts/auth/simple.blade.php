<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen w-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
        <div class="absolute right-6 top-6">
            <livewire:components.language-switcher />
        </div>
        <div class="flex w-full max-w-3xl flex-col gap-2">
            <a href="{{ route('home') }}" class="flex flex-col items-center justify-center gap-2 font-medium"
                wire:navigate>
                <x-icon.medicall-ico />
                <div class="text-center">
                    <h2 class="text-xl font-bold text-slate-900">MediCall</h2>
                    <p class="text-md text-slate-500">{{ __('Gestione sanitaria') }}</p>
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