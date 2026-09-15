<div x-data="{isOpen: false}" class="relative">

    <button @click="isOpen = true" class="flex items-center gap-2 px-1.5 py-1 text-start text-sm cursor-pointer hover:bg-gray-100 focus:bg-gray-100 rounded-lg">
        @if(auth()->user()->photo?->file_path)
        <figure class="w-9 h-9 overflow-hidden border rounded-lg">
            <img class="w-full h-full object-center" src="{{asset('storage/'.auth()->user()->photo?->file_path)}}" alt="{{auth()->user()->name}}">
        </figure>
        @else
        <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />
        @endif

        <div class="grid flex-1 text-start text-sm leading-tight">
            <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
            <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
        </div>
    </button>

    <div x-show="isOpen == true" cloak @click.outside="isOpen = false" class="absolute top-14 right-0 bg-white border rounded-xl w-50 p-2 shadow">
        <flux:menu.radio.group>
            <flux:menu.item href="/edit-profile/{{auth()->user()->id}}" icon="cog" wire:navigate class="hover:bg-slate-50 cursor-pointer">
                {{ __('Modifica account') }}
            </flux:menu.item>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" 
                    class="w-full cursor-pointer hover:bg-slate-50" data-test="logout-button">
                    {{ __('Log out') }}
                </flux:menu.item>
            </form>
        </flux:menu.radio.group>
    </div>
</div>