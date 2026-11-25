<div>
    <div class="rounded-lg bg-gray-100 flex p-1 gap-1">
        <button  
            class="@if(app()->getLocale() === 'it') bg-gray-500 hover:bg-gray-600 text-white @else hover:bg-gray-200 text-gray-500 @endif text-sm cursor-pointer font-semibold px-4 py-1 rounded-lg" 
            wire:click="switchLocale('it')" wire:loading.attr="disabled">
            IT
        </button>
        <button 
            class="@if(app()->getLocale() === 'en') bg-gray-500 hover:bg-gray-600 text-white @else hover:bg-gray-200 text-gray-500 @endif text-sm cursor-pointer font-semibold px-4 py-1 rounded-lg"
            wire:click="switchLocale('en')" wire:loading.attr="disabled">
            ENG
        </button>
    </div>
</div>