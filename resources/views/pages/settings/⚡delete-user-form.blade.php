<?php

use Livewire\Component;

new class extends Component {}; ?>

<section class="mt-10 space-y-6">
    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger" data-test="delete-user-button">
            {{ __('Elimina account') }}
        </flux:button>
    </flux:modal.trigger>

    <livewire:pages::settings.delete-user-modal />
</section>
