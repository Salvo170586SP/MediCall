<div>
    <x-modal title="{{ __('Modifica storia clinica') }}" confirm-label="Edit Medical History" :close-on-click="false"
        close-on-event="medical-history-updated" reset-method="resetHistoryForm"
        wire:key="editMedicalHistory-{{ $user->id }}-{{ $medical_history }}">
        <x-slot:trigger>
            <button class="px-4 py-3 cursor-pointer rounded-xl hover:bg-gray-100 font-bold text-black border border-gray-300">
                {{ __('Modifica') }}
            </button>
        </x-slot:trigger>

        <x-slot:body>
            <textarea id="medical_history" wire:model="medical_history" cols="50" rows="5"
                class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm text-slate-600 @error('medical_history') border-red-500 @enderror"></textarea>
            @error('medical_history')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </x-slot:body>

        <x-slot:action>
            <button wire:click="editMedicalHistory({{$user->id}})"
                class="px-4 py-2 cursor-pointer rounded-xl bg-black font-bold text-white">
                {{ __('Modifica') }}
            </button>
        </x-slot:action>
    </x-modal>
</div>