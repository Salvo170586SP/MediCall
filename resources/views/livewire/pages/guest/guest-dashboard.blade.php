<div>
    <section
        class="max-w-7xl mx-auto mb-5 border border-emerald-200 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-8 lg:px-8">
        <div class="flex items-start gap-4">
            <div class="mt-1 flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                <svg class="h-6 w-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-bold text-slate-900">Benvenuto, {{ auth()->user()->name }}</h2>
                <p class="mt-1 text-slate-600 text-xs lg:text-base">La tua area personale: consulta i tuoi appuntamenti, cartelle
                    cliniche e comunicazioni direttamente dalla home di MediCall.</p>
            </div>
            <a href=""
                class="inline-flex gap-2 px-4 py-2 rounded-full border border-blue-200 bg-blue-50 hover:bg-blue-100 font-medium text-blue-700">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                </svg>
                Modifica il mio profilo</a>
        </div>

        <div class="flex items-start gap-4 border-t border-gray-200 pt-5 mt-5">

            <div class="text-sm">
                <p class="mt-1 text-slate-600">Utente: <span class="font-bold">#{{ auth()->user()->guestDetail->code_item }}</span> </p>
                <p class="mt-1 text-slate-600">Città e data di nascita: <span class="font-bold">{{ auth()->user()->guestDetail->birth_city.',
                    '.auth()->user()->guestDetail->formatDate(auth()->user()->guestDetail->birth_date) }}</span></p>
                <p class="mt-1 text-slate-600">Cod Fiscale: <span class="font-bold">{{ auth()->user()->guestDetail->tax_id_code }}</span></p>
                <p class="mt-1 text-slate-600">Telefono: <span class="font-bold">{{ auth()->user()->guestDetail->phone }}</span></p>
            </div>

        </div>
    </section>

    <div class="h-full max-w-7xl mx-auto flex flex-col gap-3 lg:grid grid-cols-3 gap-3">
        <section
            class="h-45 relative border border-gray-200 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50  px-6 py-8 lg:px-8">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-sky-100 border border-gray-200 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 text-sky-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <h2 class="ms-3 text-xl font-bold text-slate-700">Le tue allergie</h2>
            </div>
            <div class="absolute bottom-8 right-8 space-x-2">
                <a href=""
                    class="inline-flex gap-2 px-3 py-2 rounded-full border text-sm border-blue-200 bg-blue-50 hover:bg-blue-100 font-medium text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Guarda
                </a>
                <a href=""
                    class="inline-flex gap-2 px-3 py-2 rounded-full border text-sm border-blue-200 bg-blue-50 hover:bg-blue-100 font-medium text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Aggiungi
                </a>
            </div>
        </section>

        <section
            class="h-45 relative border border-gray-200 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50  px-6 py-8 lg:px-8">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-sky-100 border border-gray-200 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 text-sky-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                </div>
                <h2 class="ms-3 text-xl font-bold text-slate-700">La tua storia clinica</h2>
            </div>
            <div class="absolute bottom-8 right-8 space-x-2">
                <a href=""
                    class="inline-flex gap-2 px-3 py-2 rounded-full border text-sm border-blue-200 bg-blue-50 hover:bg-blue-100 font-medium text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Guarda
                </a>
                <a href=""
                    class="inline-flex gap-2 px-3 py-2 rounded-full border text-sm border-blue-200 bg-blue-50 hover:bg-blue-100 font-medium text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Aggiungi
                </a>

            </div>
        </section>


        <section
            class="h-45 relative border border-gray-200 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50  px-6 py-8 lg:px-8">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-sky-100 border border-gray-200 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 text-sky-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                    </svg>
                </div>
                <h2 class="ms-3 text-xl font-bold text-slate-700">I tuoi appuntamenti</h2>
            </div>
            <div class="absolute bottom-8 right-8 space-x-2">
                <a href=""
                    class="inline-flex gap-2 px-3 py-2 rounded-full border text-sm border-blue-200 bg-blue-50 hover:bg-blue-100 font-medium text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Prenota
                </a>
            </div>
        </section>

        <section
            class="h-45 relative border border-gray-200 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-8 lg:px-8">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-sky-100 border border-gray-200 rounded-full flex items-center justify-center"">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6  text-sky-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </div>
                <h2 class="ms-3 text-xl font-bold text-slate-900">I tuoi parametri</h2>
            </div>
            <div class="absolute bottom-8 right-8 space-x-2">
                <a href=""
                    class="inline-flex gap-2 px-3 py-2 rounded-full text-sm  border border-blue-200 bg-blue-50 hover:bg-blue-100 font-medium text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Aggiungi
                </a>
                <a href=""
                    class="inline-flex gap-2 px-3 py-2 text-sm  rounded-full border border-blue-200 bg-blue-50 hover:bg-blue-100 font-medium text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Storico</a>
            </div>
        </section>

        <section
            class="h-45 relative border border-gray-200 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-8 lg:px-8">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-sky-100 border border-gray-200 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6 text-sky-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25M9 16.5v.75m3-3v3M15 12v5.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <h2 class="ms-3 text-xl font-bold text-slate-900">La mia cartella clinica</h2>
            </div>
            <div class="absolute bottom-8 right-8 space-x-2">
                <a href=""
                    class="inline-flex gap-2 px-3 py-2 text-sm  rounded-full border border-blue-200 bg-blue-50 hover:bg-blue-100 font-medium text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Aggiungi
                </a>
                <a href=""
                    class="inline-flex gap-2 px-3 py-2 text-sm rounded-full border border-blue-200 bg-blue-50 hover:bg-blue-100 font-medium text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    Storico</a>
            </div>
        </section>

        <section
            class="h-45 relative border border-gray-200 rounded-xl bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-8 lg:px-8">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-sky-100 border border-gray-200 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="size-6  text-sky-600">
                        <path fill-rule="evenodd"
                            d="M2.625 6.75a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875 0A.75.75 0 0 1 8.25 6h12a.75.75 0 0 1 0 1.5h-12a.75.75 0 0 1-.75-.75ZM2.625 12a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0ZM7.5 12a.75.75 0 0 1 .75-.75h12a.75.75 0 0 1 0 1.5h-12A.75.75 0 0 1 7.5 12Zm-4.875 5.25a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Zm4.875 0a.75.75 0 0 1 .75-.75h12a.75.75 0 0 1 0 1.5h-12a.75.75 0 0 1-.75-.75Z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <h2 class="ms-3 text-xl font-bold text-slate-900">Lista medici</h2>
            </div>
            <div class="absolute bottom-8 right-8 space-x-2">
                <a href=""
                    class="inline-flex gap-2 px-3 py-2 text-sm  rounded-full border border-blue-200 bg-blue-50 hover:bg-blue-100 font-medium text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>

                    Trova nuovo
                    medico</a>
            </div>
        </section>

    </div>
</div>