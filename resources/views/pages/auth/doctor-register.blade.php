<x-layouts::auth :title="__('Registrazione medico')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Registrati come medico')"
            :description="__('Inserisci i dati del tuo account e della tua attività professionale.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('doctor.register.store') }}" class="flex flex-col gap-6">
            @csrf

            <div class="w-full grid grid-cols-2 gap-2 space-y-2">
                <flux:input name="name" :label="__('Nome e cognome')" :value="old('name')" type="text" required autofocus autocomplete="name" />
                <flux:input name="email" :label="__('Email')" :value="old('email')" type="email" required autocomplete="email" placeholder="email@example.com" />
                <flux:input name="phone" :label="__('Telefono')" :value="old('phone')" type="tel" autocomplete="tel" />
                <flux:input name="license_number" :label="__('Numero di iscrizione all\'albo')" :value="old('license_number')" type="text" required />
            </div>

            <div class="grid gap-2 sm:grid-cols-3">
                <flux:input name="specialty" :label="__('Specialità')" :value="old('specialty')" type="text" required />
                <flux:input name="sub_specialty" :label="__('Sottospecialità')" :value="old('sub_specialty')" type="text" />
                <flux:input name="city" :label="__('Città')" :value="old('city')" type="text" autocomplete="address-level2" />
            </div>

            <flux:textarea name="bio" :label="__('Presentazione professionale')" :value="old('bio')" rows="4" />

            <div class="grid grid-cols-2 gap-2">
                <flux:input name="password" :label="__('Password')" type="password" required autocomplete="new-password" passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}" viewable />
                    <flux:input name="password_confirmation" :label="__('Conferma password')" type="password" required autocomplete="new-password" passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}" viewable />
            </div>

            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Crea account medico') }}
            </flux:button>
        </form>

        <div class="space-x-1 text-center text-sm text-zinc-600 rtl:space-x-reverse dark:text-zinc-400">
            <span>{{ __('Hai già un account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    </div>
</x-layouts::auth>