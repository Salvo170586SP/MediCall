<div class="mx-auto max-w-7xl">

    <div class="flex justify-between items-center">
        <div class="mb-5">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cerca il tuo medico') }}
            </h2>
            <small>
                {{ __('*Clicca sulle card per ulteriori dettagli') }}
            </small>
        </div>

        <button wire:navigate href="/guest/dashboard"
            class="inline-flex items-center gap-1.5 px-4 py-3 bg-zinc-100 hover:bg-zinc-200 text-gray-700 text-sm font-medium rounded-xl transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
            </svg>
            Indietro
        </button>
    </div>

    <div class="flex items-center gap-5 mb-6 items-center">
        <x-search-input inputClass="bg-gray-50" id="search" model="search" placeholder="Cerca per nome o email..." />
        <x-search-input inputClass="bg-gray-50" label="Cerca per città" id="search_city" model="search_city" placeholder="Cerca per città" />

        <label for="is_active" class="block text-sm font-semibold text-slate-900 flex items-center gap-2">
            Filtra per disponibilità
            <input type="checkbox" id="is_active"
                wire:model.live="is_active" />
        </label>
    </div>

    <div class="m-6">
        {{$users_doctor->links()}}
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
        @forelse($users_doctor as $user_doctor)
        <article wire:key="doctors-{{ $user_doctor->id }}" wire:navigate href="doctors/details/{{$user_doctor->id}}"
            class="flex h-full cursor-pointer flex-col overflow-hidden rounded-2xl border border-slate-300 bg-gradient-to-r from-emerald-50 to-teal-50 transition hover:scale-[1.03]">
            <div class="flex items-start justify-between gap-4 border-b border-slate-100 bg-slate-100 px-5 py-3">
                <div class="min-w-0">

                    <div class="flex gap-3">
                        {{-- Avatar iniziali --}}
                        @if($user_doctor->photo)
                        <figure class="w-[72px] h-[72px] overflow-hidden rounded-full border border-gray-300">
                            <img class="w-full h-full object-cover"
                                src="{{ asset('storage/'.$user_doctor->photo?->file_path)}}"
                                alt="{{$user_doctor->name}}">
                        </figure>
                        @else

                        <div class="w-48 h-38 shrink-0 rounded-full bg-gray-200 flex items-center justify-center text-blue-700 font-medium text-xl select-none"
                            style="width:72px;height:72px;">
                            {{ collect(explode(' ', $user_doctor->name))->map(fn($w) =>
                            strtoupper($w[0]))->take(2)->implode('') }}
                        </div>
                        @endif
                        <div>
                            <h3 class="mt-1 truncate text-lg font-semibold text-slate-900">{{ $user_doctor->name }}</h3>
                            <div class="mt-3">
                                <span
                                    class="inline-flex rounded-full bg-emerald-200 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    {{ $user_doctor->doctorDetail->specialty ?? 'Specializzazione non indicata' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <span
                    class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ $user_doctor->doctorDetail && $user_doctor->doctorDetail->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                    {{ $user_doctor->doctorDetail && $user_doctor->doctorDetail->is_active ? 'Disponibile' : 'Non
                    disponibile' }}
                </span>
            </div>

            <div class="flex flex-1 flex-col gap-5 px-5 py-5">

                <dl class="space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <dt class="w-20 shrink-0 font-medium text-slate-500">Città</dt>
                        <dd class="min-w-0 break-words font-medium text-slate-800">{{ $user_doctor->doctorDetail->city }}</dd>
                    </div>
                    <div class="flex items-start gap-3">
                        <dt class="w-20 shrink-0 font-medium text-slate-500">Email</dt>
                        <dd class="min-w-0 break-words font-medium text-slate-800">{{ $user_doctor->email }}</dd>
                    </div>
                    <div class="flex items-start gap-3">
                        <dt class="w-20 shrink-0 font-medium text-slate-500">Telefono</dt>
                        <dd class="font-medium text-slate-800">{{ $user_doctor->doctorDetail->phone ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </article>
        @empty
        <div
            class="col-span-full rounded-2xl border border-dashed border-slate-300 px-6 py-12 text-center text-slate-500">
            <p>Nessun dottore disponibile.</p>
        </div>
        @endforelse
    </div>

    <div class="fixed bottom-0 right-5">
        <x-flash-message type="success" />
        <x-flash-message type="error" />
    </div>
</div>