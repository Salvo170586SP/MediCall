<div @if ($this->codeItemPending) wire:poll.1500ms="refreshCode" @endif>
    @if ($this->codeItemPending)
    <span class="text-sm text-gray-500">Generazione codice in corso...</span>
    @else
    <span class="text-xs text-gray-500 font-semibold">#{{ $patient->guestDetail->code_item }}</span>
    @endif
</div>