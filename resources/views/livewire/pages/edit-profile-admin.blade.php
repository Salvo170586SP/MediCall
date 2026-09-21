<div>
    <div class="mx-auto flex w-full max-w-5xl flex-col gap-6">
        <div>

            <button wire:navigate href="/dashboard"
                class="inline-flex items-center gap-1.5 px-4 py-3 bg-zinc-100 hover:bg-zinc-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                Indietro
            </button>
        </div>
        <div
            class="rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-7 sm:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <flux:heading size="xl" class="text-slate-900">Il mio account</flux:heading>
                    <flux:subheading class="mt-1 max-w-2xl text-slate-600">
                        Gestisci i tuoi dati principali di accesso e mantieni protetto il tuo account MediCall.
                    </flux:subheading>
                </div>
                <flux:badge color="emerald" icon="check-circle">Account attivo</flux:badge>
            </div>
        </div>

        {{-- modifica nome e email --}}
        <section class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-6 flex items-start gap-3">
                <div
                    class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300">
                    <flux:icon.user class="size-5" />
                </div>
                <div>
                    <flux:heading size="lg">Dati personali</flux:heading>
                    <flux:subheading>Nome, contatti e informazioni anagrafiche.</flux:subheading>
                </div>
            </div>

            <form wire:submit="updateProfileInformation" class="space-y-6 mb-6">
                <div class="grid gap-5 md:grid-cols-2">
                    <flux:input wire:model="name" label="Nome e cognome" type="text" required autocomplete="name" />
                    <flux:input wire:model="email" label="Email" type="email" required autocomplete="email" />
                </div>

                <div class="flex justify-end border-t border-zinc-200 pt-5 dark:border-zinc-700">
                    <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                        Salva dati personali
                    </flux:button>
                </div>
            </form>

            <div class="flex justify-between items-center border-t border-zinc-200 py-5">
                <flux:checkbox wire:model="notifications" label="Attiva \ Disattiva Notifiche" />
                <flux:button variant="primary" wire:click="submitNotificated">
                    Salva cambiamento
                </flux:button>
            </div>
        </section>

        {{-- modifica password --}}
        <section class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-6 flex items-start gap-3">
                <div
                    class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                    <flux:icon.key class="size-5" />
                </div>
                <div>
                    <flux:heading size="lg">Password</flux:heading>
                    <flux:subheading>Usa una password lunga e unica per proteggere i tuoi dati sanitari.
                    </flux:subheading>
                </div>
            </div>

            <form wire:submit="updatePassword" class="space-y-5">
                <flux:input wire:model="current_password" label="Password attuale" type="password" required
                    autocomplete="current-password" viewable />
                <div class="grid gap-5 md:grid-cols-2">
                    <flux:input wire:model="password" label="Nuova password" type="password" required
                        autocomplete="new-password"
                        passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                        viewable />
                    <flux:input wire:model="password_confirmation" label="Conferma nuova password" type="password"
                        required autocomplete="new-password"
                        passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                        viewable />
                </div>

                <div class="flex justify-end border-t border-zinc-200 pt-5 dark:border-zinc-700">
                    <flux:button variant="primary" type="submit" wire:loading.attr="disabled">
                        Aggiorna password
                    </flux:button>
                </div>
            </form>
        </section>

        {{-- elimina account --}}
        <section class="rounded-2xl border border-zinc-200 bg-white p-6 sm:p-8 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="mb-6 flex items-start gap-3">
                <div class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-700">
                    <flux:icon.exclamation-triangle class="size-5" />
                </div>
                <div>
                    <flux:heading size="lg">Elimina account</flux:heading>
                    <flux:subheading>Attenzione! Eliminando l'account perderai le tue credenziali di accesso e tutti
                        i dati correlati non saranno più recuperabili
                    </flux:subheading>
                </div>

                <livewire:pages::settings.delete-user-form />
            </div>
        </section>
    </div>
</div>