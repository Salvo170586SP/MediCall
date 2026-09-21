<div>
    <div x-data="{ isOpen: false }" class="relative">
        <button type="button" @click="isOpen = !isOpen" aria-label="Apri notifiche"
            class="relative flex cursor-pointer items-center rounded-lg px-1.5 py-1 text-start text-sm hover:bg-gray-100 focus:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            @if ($unreadCount)
            <span
                class="absolute -right-1 -top-1 flex size-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                {{ $unreadCount }}
            </span>
            @endif
        </button>

        <div x-show="isOpen" x-cloak @click.outside="isOpen = false"
            class="absolute right-0 top-12 z-50 h-96 w-80 overflow-y-auto rounded-xl border bg-white p-3 text-sm shadow-lg">
            <div class="flex items-center justify-between border-b py-2 font-medium text-gray-800">
                <span>Notifiche</span>
                @if (count($notifications))
                <button type="button" wire:click="deleteAllNotifications" title="Elimina tutte le notifiche"
                    class="inline-flex items-center rounded-xl bg-red-100 p-1 text-red-800 transition-colors hover:bg-red-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 1-7.5 0" />
                    </svg>
                </button>
                @endif
            </div>

            <div class="flex flex-col gap-2 py-2">
                @forelse ($notifications as $notification)
                <div wire:key="notification-{{ $notification['id'] }}"
                    class="flex items-start justify-between gap-2 rounded-lg p-2 {{ $notification['read'] ? 'bg-gray-50' : 'bg-teal-50' }}">
                    <button type="button" wire:click="markAsRead('{{ $notification['id'] }}')" class="text-left">
                        <p class="text-xs font-medium text-gray-800">{{ $notification['message'] }}</p>
                        <span class="text-[11px] text-gray-500">{{ $notification['created_at'] }}</span>
                    </button>
                    <button type="button" wire:click="deleteNotification('{{ $notification['id'] }}')" title="Elimina"
                        class="inline-flex shrink-0 items-center rounded-xl bg-red-100 p-1 text-red-800 transition-colors hover:bg-red-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 1-7.5 0" />
                        </svg>
                    </button>
                </div>
                @empty
                <p class="py-8 text-center text-sm text-gray-400">Nessuna notifica</p>
                @endforelse
            </div>
        </div>

        @script
        <script>
            window.Echo?.private('App.Models.User.{{ auth()->id() }}')
                .notification((notification) => {
                    window.Livewire.dispatch('notification-received');

                    if (notification.event === 'appointment_created') {
                        window.Livewire.dispatch('appointment-created-notification', {
                            appointmentId: notification.appointment_id,
                        });
                    }
                   
                    if (notification.event === 'document_added') {
                        window.Livewire.dispatch('report-created-notification');
                    }
                });
        </script>
        @endscript
    </div>