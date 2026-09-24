<div>
    <div class="rounded-xl flex p-1 gap-1 bg-white">
        <button  
            class="@if(app()->getLocale() === 'it') bg-sky-900 text-white @else bg-sky-50 hover:bg-sky-100 @endif cursor-pointer font-semibold px-4 py-1 rounded-xl"
            wire:click="switchLocale('it')" wire:loading.attr="disabled">
            IT
        </button>
        <button 
            class="@if(app()->getLocale() === 'en') bg-sky-900  text-white @else bg-sky-50 hover:bg-sky-100 @endif cursor-pointer font-semibold px-4 py-1 rounded-xl"
            wire:click="switchLocale('en')" wire:loading.attr="disabled">
            ENG
        </button>
    </div>
</div>