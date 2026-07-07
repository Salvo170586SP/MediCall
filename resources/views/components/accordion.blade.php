@props(['title', 'defaultOpen' => false])

<div x-data="{ open: {{ $defaultOpen ? 'true' : 'false' }} }">
    <button type="button" @click="open = !open"
        class="flex w-full items-center justify-between group border border-gray-200 bg-gray-50 my-2 py-4 px-2 text-left hover:bg-gray-100  rounded-2xl">
        <span class="text-base font-bold text-center w-full text-gray-500 group-focus:text-gray-600">
            {{ $title }}
        </span>

        <svg :class="{ 'rotate-180': open }"
            class="h-5 w-5 text-gray-500 group-focus:text-gray-600 transition-transform duration-200 flex-shrink-0" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-collapse x-cloak class="pb-4">
        {{ $slot }}
    </div>
</div>