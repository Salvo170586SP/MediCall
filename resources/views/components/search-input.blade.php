@props([
'model', // nome della property Livewire (es. searchDoctor)
'id' => null, // id/for del campo, default = model
'label' => 'Cerca',
'placeholder' => 'Cerca...',
'type' => 'text',
'debounce' => null, // es. '300ms' -> wire:model.live.debounce.300ms
'inputClass' => ''
])

@php
$inputId = $id ?? $model;
$wireModel = $debounce ? "wire:model.live.debounce.{$debounce}" : 'wire:model.live';
@endphp

<div>
    <label for="{{ $inputId }}" class="block text-xs font-semibold text-slate-900 mb-2">
        {{ $label }}
    </label>
    <input id="{{ $inputId }}" {{ $wireModel }}="{{ $model }}" type="{{ $type }}" placeholder="{{ $placeholder }}" {{
        $attributes->merge([
    'class' => 'w-full rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-800
    focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200 ' . $inputClass
    ]) }}
    />
</div>