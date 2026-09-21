<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Crea account')"
            :description="__('Inserisci i tuoi dati qui sotto per creare il tuo account.')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <div class="w-full grid gap-2 grid-cols-2">
                <!-- Name -->
                <flux:input name="name" :label="__('Name')" :value="old('name')" type="text" required autofocus
                    autocomplete="name" :placeholder="__('Nome e cognome')" />
                <!-- Email Address -->
                <flux:input name="email" :label="__('Email')" :value="old('email')" type="email" required
                    autocomplete="email" placeholder="email@example.com" />
            </div>

            <div class="w-full grid grid-cols-2 gap-2 space-y-2">
                <flux:input name="phone" :label="__('Telefono')" :value="old('phone')" type="tel" autocomplete="tel" />
                <flux:input name="tax_id_code" :label="__('Codice fiscale')" :value="old('tax_id_code')" type="text"
                    required />
            </div>

            <div class="grid gap-2 sm:grid-cols-3">
                <flux:input name="birth_city" :label="__('Città di nascita')" :value="old('birth_city')" type="text"
                    required />
                <flux:input name="birth_date" :label="__('Data di nascita')" :value="old('birth_date')" type="date" />
                <flux:input name="gender" :label="__('Sesso')" :value="old('gender')" type="text" />
            </div>


            <div class="w-full grid grid-cols-2 gap-2 space-y-2">
                <!-- Password -->
                <flux:input name="password" :label="__('Password')" type="password" required autocomplete="new-password"
                    :placeholder="__('Password')"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    viewable />

                <!-- Confirm Password -->
                <flux:input name="password_confirmation" :label="__('Conferma password')" type="password" required
                    autocomplete="new-password" :placeholder="__('Conferma password')"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    viewable />
            </div>

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Crea account') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Hai già un account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>