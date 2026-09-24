<div>
    <div class="mx-auto max-w-7xl">
        <button wire:navigate href="/guest/dashboard"
            class="inline-flex items-center gap-1.5 mb-5 rounded-xl border border-slate-200 bg-zinc-100 px-4 py-3 text-sm font-bold text-slate-700 transition-colors hover:bg-sky-100 hover:text-sky-800">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
        </button>
    </div>

    <div class="mx-auto flex w-full max-w-7xl flex-col gap-6">
        <div
            class="rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-7 sm:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <flux:heading size="xl" class="text-slate-900">{{ __('Il mio profilo') }}</flux:heading>
                    <flux:subheading class="mt-1 max-w-2xl text-slate-600">
                        {{ __('Gestisci i tuoi dati personali e mantieni protetto il tuo account MediCall') }}
                    </flux:subheading>
                </div>
                <flux:badge color="emerald" icon="check-circle">{{ __('Account attivo') }}</flux:badge>
            </div>
        </div>

        <section class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-6 flex items-start gap-3">
                <div
                    class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">
                    <flux:icon.user class="size-5" />
                </div>
                <div>
                    <flux:heading size="lg">{{ __('Dati personali') }}
                    </flux:heading>
                    <flux:subheading>{{ __('Nome, contatti e informazioni anagrafiche') }}</flux:subheading>
                </div>
            </div>

            <form wire:submit="updateProfileInformation" class="space-y-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <flux:input wire:model="name" label="{{ __('Nome e cognome') }}" type="text" required
                        autocomplete="name" />
                    <flux:input wire:model="email" label="Email" type="email" required autocomplete="email" />
                    <flux:input wire:model="phone" label="{{ __('Telefono') }}" type="tel" autocomplete="tel" />
                    <flux:input wire:model="tax_id_code" label="{{ __('Codice fiscale') }}" type="text"
                        autocomplete="off" />
                    <flux:input wire:model="birth_city" label="{{ __('Città di nascita') }}" type="text"
                        autocomplete="address-level2" />
                    <flux:input wire:model="birth_date" label="{{ __('Data di nascita') }}" type="date"
                        autocomplete="bday" />
                    <flux:input wire:model="gender" label="{{ __('Sesso') }}" type="text" autocomplete="sex" />
                </div>
                <flux:checkbox wire:model="notifications" label="{{ __('Attiva - Disattiva Notifiche') }}" />

                <div class="flex justify-end border-t border-zinc-200 pt-5 dark:border-zinc-700">
                    <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                        {{ __('Salva') }}
                    </flux:button>
                </div>
            </form>
        </section>

        <section class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-6 flex items-start gap-3">
                <div
                    class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                    <flux:icon.key class="size-5" />
                </div>
                <div>
                    <flux:heading size="lg">Password</flux:heading>
                    <flux:subheading>{{ __('Usa una password lunga e unica per proteggere i tuoi dati sanitari') }}
                    </flux:subheading>
                </div>
            </div>

            <form wire:submit="updatePassword" class="space-y-5">
                <flux:input wire:model="current_password" label="{{ __('Password attuale') }}" type="password" required
                    autocomplete="current-password" viewable />
                <div class="grid gap-5 md:grid-cols-2">
                    <flux:input wire:model="password" label="{{ __('Nuova password') }}" type="password" required
                        autocomplete="new-password"
                        passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                        viewable />
                    <flux:input wire:model="password_confirmation" label="{{ __('Conferma nuova password') }}"
                        type="password" required autocomplete="new-password"
                        passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                        viewable />
                </div>

                <div class="flex justify-end border-t border-zinc-200 pt-5 dark:border-zinc-700">
                    <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                        {{ __('Aggiorna password') }}
                    </flux:button>
                </div>
            </form>
        </section>

        {{-- elimina account --}}
        <section class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-6 flex items-start justify-between">
                <div class="flex items-start space-x-3">
                    <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700">
                        <flux:icon.exclamation-triangle class="size-5" />
                    </div>
                    <div>
                        <flux:heading size="lg">{{ __('Elimina account')
                            }}</flux:heading>
                        <flux:subheading>{{ __('Attenzione! Eliminando l\'account perderai le tue credenziali di accesso e tutti i dati correlati non saranno più recuperabili') }}
                        </flux:subheading>
                    </div>
                </div>

                <livewire:pages::settings.delete-user-form />
            </div>
        </section>
    </div>
</div>