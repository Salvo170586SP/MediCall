<x-layouts::auth :title="__('Registrazione medico')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Registrati come medico')"
            :description="__('Inserisci i tuoi dati professionali, la disponibilità e i dati di accesso.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form
            method="POST"
            action="{{ route('doctor.register.store') }}"
            class="flex flex-col gap-6"
            data-doctor-registration
        >
            @csrf

            @if ($errors->any())
                <div class="rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200" role="alert" data-validation-errors>
                    <p class="font-medium">{{ __('Controlla i dati inseriti:') }}</p>
                    <ul class="mt-2 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @foreach ($errors->keys() as $field)
                        <span class="hidden" data-error-field="{{ $field }}"></span>
                    @endforeach
                </div>
            @endif

            <ol class="grid grid-cols-3 gap-2 text-center text-xs sm:gap-3 sm:text-sm" aria-label="{{ __('Passaggi di registrazione') }}">
                <li>
                    <span class="block rounded-lg border border-zinc-300 px-2 py-3 aria-[current=step]:border-blue-600 aria-[current=step]:font-semibold aria-[current=step]:text-blue-700 dark:border-zinc-700 dark:aria-[current=step]:text-blue-300" data-step-indicator aria-current="step">
                        1. {{ __('Dati professionali') }}
                    </span>
                </li>
                <li>
                    <span class="block rounded-lg border border-zinc-300 px-2 py-3 aria-[current=step]:border-blue-600 aria-[current=step]:font-semibold aria-[current=step]:text-blue-700 dark:border-zinc-700 dark:aria-[current=step]:text-blue-300" data-step-indicator>
                        2. {{ __('Disponibilità') }}
                    </span>
                </li>
                <li>
                    <span class="block rounded-lg border border-zinc-300 px-2 py-3 aria-[current=step]:border-blue-600 aria-[current=step]:font-semibold aria-[current=step]:text-blue-700 dark:border-zinc-700 dark:aria-[current=step]:text-blue-300" data-step-indicator>
                        3. {{ __('Account') }}
                    </span>
                </li>
            </ol>

            <section class="flex flex-col gap-4" data-registration-step>
                <h2 class="text-lg font-semibold" tabindex="-1">{{ __('Dati del medico') }}</h2>

                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:input name="name" :label="__('Nome e cognome')" :value="old('name')" type="text" required autofocus autocomplete="name" />
                    <flux:input name="phone" :label="__('Telefono')" :value="old('phone')" type="tel" autocomplete="tel" />
                    <flux:input name="license_number" :label="__('Numero di iscrizione all\'albo')" :value="old('license_number')" type="text" required />
                    <flux:input name="specialty" :label="__('Specialità')" :value="old('specialty')" type="text" required />
                    <flux:input name="sub_specialty" :label="__('Sottospecialità')" :value="old('sub_specialty')" type="text" />
                    <flux:input name="city" :label="__('Città')" :value="old('city')" type="text" autocomplete="address-level2" />
                </div>

                <flux:textarea name="bio" :label="__('Presentazione professionale')" :value="old('bio')" rows="4" />

                <div class="flex justify-end">
                    <flux:button type="button" variant="primary" data-step-next>
                        {{ __('Continua') }}
                    </flux:button>
                </div>
            </section>

            <section class="flex flex-col gap-4" data-registration-step>
                <div>
                    <h2 class="text-lg font-semibold" tabindex="-1">{{ __('Disponibilità settimanale') }}</h2>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('Indica l’orario di inizio e fine per ogni giorno. Lascia vuoti entrambi i campi nei giorni in cui non sei disponibile.') }}</p>
                </div>

                <div class="grid gap-3 sm:grid-cols-3">
                    @foreach ([
                        'mon' => __('Lunedì'),
                        'tue' => __('Martedì'),
                        'wed' => __('Mercoledì'),
                        'thu' => __('Giovedì'),
                        'fri' => __('Venerdì'),
                        'sat' => __('Sabato'),
                        'sun' => __('Domenica'),
                    ] as $day => $label)
                        <fieldset class="rounded-lg border border-zinc-300 p-3 dark:border-zinc-700">
                            <legend class="px-1 text-sm font-medium">{{ $label }}</legend>
                            <div class="grid grid-cols-2 gap-3">
                                <flux:input name="available_hours[{{ $day }}][0]" :label="__('Dalle')" :value="old('available_hours.'.$day.'.0')" type="time" />
                                <flux:input name="available_hours[{{ $day }}][1]" :label="__('Alle')" :value="old('available_hours.'.$day.'.1')" type="time" />
                            </div>
                        </fieldset>
                    @endforeach
                </div>

                <div class="flex justify-between gap-3">
                    <flux:button type="button" data-step-previous>
                        {{ __('Indietro') }}
                    </flux:button>
                    <flux:button type="button" variant="primary" data-step-next>
                        {{ __('Continua') }}
                    </flux:button>
                </div>
            </section>

            <section class="flex flex-col gap-4" data-registration-step>
                <div>
                    <h2 class="text-lg font-semibold" tabindex="-1">{{ __('Dati di accesso') }}</h2>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">{{ __('Conferma il tuo indirizzo email e scegli una password per creare il profilo.') }}</p>
                </div>

                <flux:input name="email" :label="__('Email')" :value="old('email')" type="email" required autocomplete="email" placeholder="email@example.com" />
                <flux:input name="email_confirmation" :label="__('Conferma email')" :value="old('email_confirmation')" type="email" required autocomplete="email" placeholder="email@example.com" />

                <div class="grid gap-4 sm:grid-cols-2">
                    <flux:input name="password" :label="__('Password')" type="password" required autocomplete="new-password" passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}" viewable />
                    <flux:input name="password_confirmation" :label="__('Conferma password')" type="password" required autocomplete="new-password" passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}" viewable />
                </div>

                <div class="flex justify-between gap-3">
                    <flux:button type="button" data-step-previous>
                        {{ __('Indietro') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary" data-test="register-doctor-button">
                        {{ __('Crea profilo medico') }}
                    </flux:button>
                </div>
            </section>
        </form>

        <div class="space-x-1 text-center text-sm text-zinc-600 rtl:space-x-reverse dark:text-zinc-400">
            <span>{{ __('Hai già un account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>
