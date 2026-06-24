@props([
'type' => 'success'
])

@php
$classes = match($type) {
'success' => 'bg-emerald-100 border-emerald-300 text-emerald-800',
'error' => 'bg-red-100 border-red-300 text-red-800',
'warning' => 'bg-yellow-100 border-yellow-300 text-yellow-800',
default => 'bg-gray-100 border-gray-300 text-gray-800',
};
@endphp

@if (session()->has($type))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show" x-transition
    class="mb-6 rounded-xl border px-4 py-3 {{ $classes }}">
    {{ session($type) }}
</div>
@endif