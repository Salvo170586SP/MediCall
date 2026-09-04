<div>
    <x-modal title="Modifica Storia Clinica" confirm-label="Edit Medical History" :close-on-click="false"
        close-on-event="medical-history-updated" reset-method="resetHistoryForm" wire:key="editMedicalHistory-{{ $user->id }}-{{ $medical_history }}">
        <x-slot:trigger>
            <button 
                class="inline-flex gap-2 px-3 py-2 text-sm cursor-pointer rounded-full bg-black hover:bg-gray-700 font-medium text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
                Modifica
            </button>
        </x-slot:trigger>

        <x-slot:body>
            <textarea id="medical_history" wire:model="medical_history" cols="50" rows="10"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm text-slate-600 @error('medical_history') border-red-500 @enderror"></textarea>
            @error('medical_history')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </x-slot:body>

        <x-slot:action>
            <button wire:click="editMedicalHistory({{$user->id}})"
                class="inline-flex gap-2 px-3 py-2 text-sm cursor-pointer rounded-full bg-black hover:bg-gray-700 font-medium text-white">
                Modifica
            </button>
        </x-slot:action>
    </x-modal>
</div>