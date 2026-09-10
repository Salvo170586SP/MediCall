<div>
    <x-modal title="Modifica allergie" confirm-label="Edit allergies" :close-on-click="false"
        close-on-event="allergies-updated" reset-method="resetAllergiesForm"
        wire:key="editAllergies-[{{$user->id}}-{{$allergies}}]">
        <x-slot:trigger>
            <button
                class="inline-flex gap-2 px-3 py-2 text-sm cursor-pointer rounded-full border border-sky-300 bg-sky-50 hover:bg-sky-100 font-bold text-sky-800">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
                Modifica
            </button>
        </x-slot:trigger>

        <x-slot:body>
            <textarea id="allergies" wire:model="allergies" cols="50" rows="10" placeholder="es: latte, medicinale..."
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-600 @error('allergies') border-red-500 @enderror"></textarea>
            @error('allergies')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <div class="text-end">
                <span class="font-bold text-xs">*inserire massimo 255 caratteri</span>
            </div>
        </x-slot:body>

        <x-slot:action>
            <button wire:click="editAllergies({{$user->id}})"
                class="inline-flex gap-2 px-3 py-2 text-sm cursor-pointer rounded-full border border-sky-300 bg-sky-50 hover:bg-sky-100 font-medium text-sky-800">
                Modifica
            </button>
        </x-slot:action>
    </x-modal>
</div>