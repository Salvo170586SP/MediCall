<div class="mx-auto max-w-7xl">

    <button wire:navigate href="/guest/dashboard"
        class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-zinc-100 px-4 py-3 text-sm font-bold text-slate-700 transition-colors hover:bg-sky-100 hover:text-sky-800">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
        </svg>
    </button>

    <section
        class="mx-auto my-5 max-w-7xl rounded-xl border border-emerald-200 bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-8 lg:px-8">
        <div class="flex items-start gap-4">
            <div class="mt-1 flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 text-emerald-700">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-900"> {{ __('Cerca il tuo medico') }}</h1>
                <p class="mt-1 text-sm text-slate-600"> {{ __('*Clicca sulle card per ulteriori dettagli') }}</p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl rounded-xl border border-slate-200 bg-white p-5 sm:p-6">

        <div class="flex items-center gap-5 mb-6 items-center">
            <x-search-input inputClass="bg-gray-50" id="search" model="search"
                placeholder="Cerca per nome o email..." />
            <x-search-input inputClass="bg-gray-50" label="Cerca per città" id="search_city" model="search_city"
                placeholder="Cerca per città" />

            <label for="is_active" class="block text-sm font-semibold text-slate-900 flex items-center gap-2">
                Filtra per disponibilità
                <input type="checkbox" id="is_active" wire:model.live="is_active" />
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
                                <h3 class="mt-1 truncate text-lg font-semibold text-slate-900">{{ $user_doctor->name }}
                                </h3>
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
                            <dd class="min-w-0 break-words font-medium text-slate-800">{{
                                $user_doctor->doctorDetail->city
                                }}</dd>
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

    </section>
</div>