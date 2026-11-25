<div>
    <x-modal title="{{ __('Modifica') }}" confirm-label="Edit allergies" :close-on-click="false"
        close-on-event="allergies-updated" reset-method="resetAllergiesForm"
        wire:key="editAllergies-[{{$user->id}}-{{$allergies}}]">
        <x-slot:trigger>
            <button
                class="px-4 py-3 cursor-pointer rounded-xl hover:bg-gray-100 font-bold text-black border border-gray-300">
                {{ __('Modifica') }}
            </button>
        </x-slot:trigger>

        <x-slot:body>
            <textarea id="allergies" wire:model="allergies" cols="50" rows="5" placeholder="{{ __('es: latte, medicinale...') }}"
                class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm text-slate-600 @error('allergies') border-red-500 @enderror"></textarea>
            @error('allergies')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <div class="text-end">
                <span class="font-bold text-xs">*{{ __('Inserire massimo 255 caratteri') }}</span>
            </div>
        </x-slot:body>

        <x-slot:action>
            <button wire:click="editAllergies({{$user->id}})"
                class="px-4 py-2 cursor-pointer rounded-xl bg-black font-bold text-white">
                {{ __('Modifica') }}
            </button>
        </x-slot:action>
    </x-modal>
</div>