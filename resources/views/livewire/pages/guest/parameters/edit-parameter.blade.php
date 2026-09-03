<div>
	<div class="mx-auto max-w-3xl">
		<div class="mb-5 flex justify-end">
			<a wire:navigate href="{{ route('guest.parameters') }}"
				class="inline-flex items-center gap-1.5 rounded-xl bg-zinc-100 px-4 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-zinc-200">
				<span aria-hidden="true">&#8592;</span>
				Indietro
			</a>
		</div>

		<section class="mb-6 rounded-xl border border-blue-200 bg-gradient-to-r from-blue-50 to-sky-50 px-6 py-8 lg:px-8">
			<p class="text-sm font-semibold uppercase tracking-wider text-blue-700">Modifica misurazione</p>
			<h1 class="mt-2 text-2xl font-bold text-slate-950">Aggiorna {{ $parameterTypes[$type]['label'] ?? str_replace('_', ' ', $type) }}</h1>
			<p class="mt-2 text-sm text-slate-600">Correggi il valore o i dettagli della misurazione.</p>
		</section>

		<form wire:submit="save" class="space-y-6">
			<section class="rounded-xl border border-slate-200 bg-white p-6">
				<div class="grid gap-5 sm:grid-cols-2">
					<div>
						<label for="type" class="mb-2 block text-xs font-semibold text-slate-700">Parametro</label>
						<input id="type" type="text" value="{{ $parameterTypes[$type]['label'] ?? str_replace('_', ' ', $type) }}" disabled
							class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm capitalize text-slate-600">
					</div>
					<div>
						<label for="value" class="mb-2 block text-xs font-semibold text-slate-700">Valore</label>
						<div class="flex items-center gap-3">
							<input id="value" type="text" inputmode="decimal" wire:model.blur="value"
								class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('value') border-red-500 @enderror">
							<span class="text-sm font-semibold text-slate-500">{{ $unit }}</span>
						</div>
						@error('value') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
					</div>
					<div>
						<label for="measuredAt" class="mb-2 block text-xs font-semibold text-slate-700">Data e ora</label>
						<input id="measuredAt" type="datetime-local" wire:model.blur="measuredAt"
							class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('measuredAt') border-red-500 @enderror">
						@error('measuredAt') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
					</div>
					<div>
						<label for="source" class="mb-2 block text-xs font-semibold text-slate-700">Origine</label>
						<select id="source" wire:model="source"
							class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('source') border-red-500 @enderror">
							<option value="manual">Manuale</option>
							<option value="wearable">Wearable</option>
							<option value="iot">IoT</option>
							<option value="device">Device</option>
						</select>
						@error('source') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
					</div>
				</div>

				<div class="mt-5">
					<label for="notes" class="mb-2 block text-xs font-semibold text-slate-700">Note <span class="font-normal text-slate-500">(facoltative)</span></label>
					<textarea id="notes" wire:model.blur="notes" rows="4" maxlength="1000"
						class="w-full rounded-xl border border-slate-200 px-4 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 @error('notes') border-red-500 @enderror"></textarea>
					@error('notes') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
				</div>
			</section>

			<div class="flex justify-end gap-3">
				<a wire:navigate href="{{ route('guest.parameters') }}"
					class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-50">Annulla</a>
				<button type="submit" wire:loading.attr="disabled"
					class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-blue-700 disabled:cursor-wait disabled:opacity-60">
					<span wire:loading.remove>Salva modifiche</span>
					<span wire:loading>Salvataggio...</span>
				</button>
			</div>
		</form>
	</div>
</div>