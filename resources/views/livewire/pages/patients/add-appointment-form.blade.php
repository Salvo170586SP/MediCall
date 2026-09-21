<div>
	<x-modal title="Aggiungi appuntamento" confirm-label="addAppointments" :close-on-click="false"
		reset-method="resetForm" close-on-event="appointment_created"
		confirm-class="max-w-200 border-red-200 bg-red-50 hover:bg-red-100 text-red-800">
		<x-slot:trigger>
			<button type="button" title="Aggiungi appuntamento"
				class="inline-flex items-center gap-1.5 rounded-lg bg-sky-100 px-4 py-3 text-sm font-bold text-sky-900 transition-colors hover:bg-sky-200">
				<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
				</svg>
				Aggiungi appuntamento
			</button>
		</x-slot:trigger>

		<x-slot:body>
			<div class="mt-10 flex gap-5">
				<div class="w-[500px] space-y-3">
					<div class="relative text-start">
						<label class="mb-1 block text-sm font-medium text-slate-700">Dottore</label>

						@if ($doctor_id)
						<div class="flex items-center justify-between rounded-lg border border-slate-300 bg-slate-50 px-3 py-2">
							<span class="text-sm text-slate-800">{{ $doctorSearch }}</span>
							<button type="button" wire:click="clearDoctor" class="text-xs text-indigo-600 hover:underline">
								Cambia
							</button>
						</div>
						@else
						<input type="text" wire:model.live.debounce.300ms="doctorSearch"
							placeholder="Cerca per nome o email..." autocomplete="off"
							class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" />

						@if ($showDoctorDropdown)
						<div class="absolute z-10 mt-1 w-full rounded-lg border border-slate-200 bg-white shadow-lg">
							@forelse ($this->filteredDoctors as $key => $doctor)
							<button wire:key="doctor-{{$key}}" type="button" wire:click="selectDoctor({{ $doctor->id }})"
								class="flex w-full flex-col items-start px-3 py-2 text-left text-sm hover:bg-indigo-50">
								<span class="font-medium text-slate-800">{{ $doctor->name }}</span>
								<span class="text-xs text-slate-500">{{ $doctor->email }}</span>
							</button>
							@empty
							<div class="px-3 py-2 text-sm text-slate-500">Nessun dottore trovato.</div>
							@endforelse
						</div>
						@endif
						@endif
						@error('doctor_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
					</div>

					<div class="text-start">
						<label class="mb-1 block text-sm font-medium text-slate-700">Data</label>
						@if (! $doctor_id)
						<p class="text-xs text-slate-500">Seleziona prima un dottore per visualizzare la disponibilità.</p>
						@else
						<div class="rounded-lg border border-slate-200 p-3">
							<div class="mb-3 flex items-center justify-between">
								<button type="button" wire:click="previousMonth" class="rounded-md p-1.5 text-slate-500 hover:bg-slate-100">
									<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
								</button>
								<span class="text-sm font-medium capitalize text-slate-800">
									{{ \Carbon\Carbon::parse($calendarMonth . '-01')->locale('it')->translatedFormat('F Y') }}
								</span>
								<button type="button" wire:click="nextMonth" class="rounded-md p-1.5 text-slate-500 hover:bg-slate-100">
									<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
								</button>
							</div>
							<div class="mb-1 grid grid-cols-7 text-center text-xs font-medium text-slate-400">
								<span>Lun</span><span>Mar</span><span>Mer</span><span>Gio</span><span>Ven</span><span>Sab</span><span>Dom</span>
							</div>
							<div class="grid grid-cols-7 gap-1">
								@foreach ($this->calendarDays as $d)
								<button type="button" wire:key="cal-{{ $d['date'] }}" @if ($d['isAvailable']) wire:click="selectDate('{{ $d['date'] }}')" @endif @disabled(! $d['isAvailable'])
									class="relative h-14 aspect-square rounded-md text-sm transition
									{{ ! $d['inMonth'] ? 'text-slate-300' : '' }}
									{{ $d['inMonth'] && ! $d['isAvailable'] ? 'cursor-not-allowed text-slate-300' : '' }}
									{{ $d['isAvailable'] && ! $d['isSelected'] ? 'text-slate-800 hover:bg-indigo-50' : '' }}
									{{ $d['isSelected'] ? 'bg-indigo-600 font-semibold text-white' : '' }}
									{{ $d['isToday'] && ! $d['isSelected'] ? 'ring-1 ring-indigo-300' : '' }}">
									{{ $d['day'] }}
									@if ($d['isAvailable'] && ! $d['isSelected'])
									<span class="absolute bottom-0.5 left-1/2 h-1 w-1 -translate-x-1/2 rounded-full bg-indigo-500"></span>
									@endif
								</button>
								@endforeach
							</div>
							<div class="mt-3 flex items-center gap-1.5 text-xs text-slate-400">
								<span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
								Giorno disponibile
							</div>
						</div>
						@endif
						@error('scheduled_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
					</div>
				</div>

				<div class="w-[500px] space-y-3">
					<div class="h-15 text-start">
						<label class="mb-1 block text-sm font-medium text-slate-700">Orario</label>
						@if (blank($scheduled_date))
						<p class="text-xs text-slate-500">Seleziona prima una data dal calendario.</p>
						@elseif (empty($this->availableSlots))
						<p class="text-xs text-amber-600">Nessuno slot disponibile per il giorno selezionato.</p>
						@else
						<select wire:model="scheduled_time" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
							<option value="">Seleziona un orario</option>
							@foreach ($this->availableSlots as $key => $slot)
							<option wire:key="scheduled_time-{{$key}}" value="{{ $slot['value'] }}">{{ $slot['label'] }}</option>
							@endforeach
						</select>
						@endif
						@error('scheduled_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
					</div>

					<div class="text-start">
						<label class="mb-1 block text-sm font-medium text-slate-700">Tipo</label>
						<select wire:model="type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
							<option value="">Seleziona un tipo</option>
							@foreach ($types as $key => $data)
							<option wire:key="type-{{$key}}" value="{{$data['type']}}">{{ $data['label'] ?? $key }}</option>
							@endforeach
						</select>
						@error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
					</div>

					<div class="text-start">
						<label class="mb-1 block text-sm font-medium text-slate-700">Stato</label>
						<select wire:model="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
							<option value="">Seleziona uno stato</option>
							@foreach ($statuses as $key => $data)
							<option wire:key="status-{{$key}}" value="{{$data['status']}}">{{ $data['label'] ?? $key }}</option>
							@endforeach
						</select>
						@error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
					</div>

					<div class="text-start">
						<label class="mb-1 block text-sm font-medium text-slate-700">Motivo (opzionale)</label>
						<input type="text" wire:model="reason" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" />
					</div>

					<div class="text-start">
						<label class="mb-1 block text-sm font-medium text-slate-700">Note (opzionale)</label>
						<textarea wire:model="notes" rows="12" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
					</div>
				</div>
			</div>
		</x-slot:body>

		<x-slot:action>
			<button type="button" wire:loading.attr="disabled" wire:click="save" class="rounded-lg bg-sky-600 px-4 py-2 text-sm font-medium text-white hover:bg-sky-500 disabled:opacity-60">
				<span wire:loading.remove wire:target="save">Salva appuntamento</span>
				<span wire:loading wire:target="save">Salvataggio...</span>
			</button>
		</x-slot:action>
	</x-modal>
</div>