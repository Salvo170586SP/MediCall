<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('Welcome') }} - {{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @fonts
</head>

<body class="bg-white text-slate-900 antialiased min-h-screen">
    <!-- Header -->
    <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/80 backdrop-blur-sm">
        <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
            <div class="flex items-center gap-2">
                <x-icon.medicall-ico />
                <div>
                    <p class="text-sm font-bold text-slate-900">MediCall</p>
                    <p class="text-xs text-slate-500">Gestione Sanitaria</p>
                </div>
            </div>

            @if (Route::has('login'))
            <nav class="flex items-center gap-2">
                @auth
                @if (auth()->user()->hasRole('Guest'))
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                        Esci
                    </button>
                </form>
                <a href="{{ route('guest.dashboard') }}"
                    class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-700">
                    Dashboard
                </a>
                @else
                <a href="{{ route('dashboard') }}"
                    class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-700">
                    Dashboard
                </a>
                @endif
                @else
                <a href="{{ route('login') }}"
                    class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-100">
                    Accedi
                </a>
                @endauth
            </nav>
            @endif
        </div>
    </header>

    <main class="w-full">
        <!-- Hero Section -->
        <section class="px-6 py-16 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="grid items-center gap-12 lg:grid-cols-2">
                    <!-- Left: Content -->
                    <div>
                        <div class="mb-4 inline-flex items-center rounded-full bg-teal-50 px-4 py-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-teal-700">Soluzione
                                Healthcare</span>
                        </div>
                        <h1 class="text-5xl font-black leading-tight text-slate-900">
                            Gestione Sanitaria Semplificata
                        </h1>
                        <p class="mt-6 text-lg leading-relaxed text-slate-600">
                            MediCall centralizza la programmazione delle visite, la gestione delle cartelle cliniche e
                            il monitoraggio dei pazienti. Un'unica piattaforma per coordinare medici, pazienti e
                            appuntamenti.
                        </p>
                        @guest
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-600 px-6 py-3 font-semibold text-white transition hover:bg-teal-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Registrati Ora
                            </a>
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center justify-center gap-2 rounded-lg border-2 border-slate-200 bg-white px-6 py-3 font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Accedi
                            </a>
                        </div>
                        @endguest
                    </div>

                    <!-- Right: Visual -->
                    <div class="text-end">
                        <figure class="w-full h-full overflow-hidden rounded-2xl">
                            <img class="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
                                src="{{asset('assets/imgs/img.jpg')}}" alt="wlcome-img">
                        </figure>
                        <span class="text-xs text-slate-400">*Immagine generata con Google Gemini</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Grid -->
        <section class="border-t border-slate-200 bg-slate-50 px-6 py-16 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="mb-12 text-center">
                    <h2 class="text-3xl font-bold text-slate-900">Servizi Principali</h2>
                    <p class="mt-4 text-slate-600">Tutto ciò di cui hai bisogno per gestire efficacemente la pratica
                        sanitaria</p>
                </div>

                <div class="grid gap-8 md:grid-cols-3">
                    <!-- Service 1: Appointments -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-8 transition hover:border-teal-200 hover:shadow-lg hover:shadow-teal-100">
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-teal-100">
                            <svg class="h-7 w-7 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Programmazione Appuntamenti</h3>
                        <p class="mt-3 text-slate-600">Gestisci facilmente prenotazioni, orari disponibili e promemoria
                            automatici. Riduci i no-show e ottimizza l'utilizzo della tua agenda.</p>
                        <ul class="mt-4 space-y-2 text-sm text-slate-500">
                            <li class="flex items-center gap-2">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-teal-400"></span>
                                Calendario condiviso
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-teal-400"></span>
                                Notifiche in tempo reale
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-teal-400"></span>
                                Sincronizzazione automatica
                            </li>
                        </ul>
                    </div>

                    <!-- Service 2: Medical Records -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-8 transition hover:border-blue-200 hover:shadow-lg hover:shadow-blue-100">
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100">
                            <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Cartelle Cliniche</h3>
                        <p class="mt-3 text-slate-600">Centralizza tutta la documentazione medica dei pazienti in un
                            unico luogo sicuro. Accesso rapido alla storia clinica completa.</p>
                        <ul class="mt-4 space-y-2 text-sm text-slate-500">
                            <li class="flex items-center gap-2">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                                Cronologia completa
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                                Documenti strutturati
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-400"></span>
                                Ricerca veloce
                            </li>
                        </ul>
                    </div>

                    <!-- Service 3: Patient Monitoring -->
                    <div
                        class="rounded-2xl border border-slate-200 bg-white p-8 transition hover:border-cyan-200 hover:shadow-lg hover:shadow-cyan-100">
                        <div class="mb-4 inline-flex h-14 w-14 items-center justify-center rounded-xl bg-cyan-100">
                            <svg class="h-7 w-7 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">Monitoraggio Pazienti</h3>
                        <p class="mt-3 text-slate-600">Traccia parametri vitali, follow-up e progressi clinici. Ricevi
                            avvisi automatici per condizioni che richiedono attenzione.</p>
                        <ul class="mt-4 space-y-2 text-sm text-slate-500">
                            <li class="flex items-center gap-2">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                                Dashboard analitica
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                                Avvisi intelligenti
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="inline-block h-1.5 w-1.5 rounded-full bg-cyan-400"></span>
                                Report automatici
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Highlight -->
        <section class="px-6 py-16 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <div class="grid gap-12 lg:grid-cols-2">
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900">Progettato per i Professionisti Sanitari</h2>
                        <p class="mt-4 text-lg text-slate-600">MediCall semplifica i workflow quotidiani e riduce
                            l'onere amministrativo, permettendoti di concentrarti sulla cura dei pazienti.</p>
                        <ul class="mt-8 space-y-4">
                            <li class="flex items-start gap-4">
                                <div
                                    class="mt-1 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100">
                                    <svg class="h-4 w-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">Riduce i Tempi Amministrativi</h3>
                                    <p class="mt-1 text-slate-600">Automatizza attività ripetitive e concentrati sulla
                                        pratica clinica.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div
                                    class="mt-1 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100">
                                    <svg class="h-4 w-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">Migliora la Sicurezza Dati</h3>
                                    <p class="mt-1 text-slate-600">Conformità GDPR e crittografia end-to-end per
                                        proteggere i dati sensibili.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-4">
                                <div
                                    class="mt-1 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-teal-100">
                                    <svg class="h-4 w-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-900">Aumenta la Soddisfazione dei Pazienti</h3>
                                    <p class="mt-1 text-slate-600">Prenotazioni semplificate, comunicazioni trasparenti
                                        e accesso ai propri dati.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 p-12 text-white">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="rounded-xl bg-white/10 backdrop-blur-sm p-6">
                                <p class="text-3xl font-bold">95%</p>
                                <p class="mt-2 text-sm text-teal-100">Riduzione Errori Amministrativi</p>
                            </div>
                            <div class="rounded-xl bg-white/10 backdrop-blur-sm p-6">
                                <p class="text-3xl font-bold">4h</p>
                                <p class="mt-2 text-sm text-teal-100">Tempo Risparmiato al Giorno</p>
                            </div>
                            <div class="rounded-xl bg-white/10 backdrop-blur-sm p-6">
                                <p class="text-3xl font-bold">24/7</p>
                                <p class="mt-2 text-sm text-teal-100">Accesso ai Dati Clinici</p>
                            </div>
                            <div class="rounded-xl bg-white/10 backdrop-blur-sm p-6">
                                <p class="text-3xl font-bold">99.9%</p>
                                <p class="mt-2 text-sm text-teal-100">Disponibilità Garantita</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-slate-700 px-6 py-16 text-white lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <h2 class="text-3xl font-bold">Sei un medico?</h2>
                <p class="mt-4 text-lg font-medium text-slate-300">Inizia gratuitamente e scopri come puoi semplificare
                    la
                    gestione dei tuoi pazienti.</p>
                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-center">
                    <a href="{{ route('doctor.register') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-teal-600 px-8 py-3 font-semibold transition hover:bg-teal-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Registrati qui
                    </a>
                </div>
            </div>
        </section>


        <!-- Footer -->
        <footer class="border-t border-slate-200 bg-slate-50 px-6 py-5 lg:px-8">
            <div class="mx-auto max-w-7xl text-center">
                <p class="text-sm text-slate-600">
                    &copy; {{ now()->year }} MediCall - Gestione Sanitaria Professionale. <br>
                    Questa applicazione è stata realizzata da Salvatore Pitanza. Tutti i diritti sono riservati.
                </p>
            </div>
        </footer>
    </main>
</body>

</html>