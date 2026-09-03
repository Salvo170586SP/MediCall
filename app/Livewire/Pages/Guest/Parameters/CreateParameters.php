<?php

namespace App\Livewire\Pages\Guest\Parameters;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateParameters extends Component
{
    /** @var array<string, string> */
    public array $selectedParameters = [];

    /** @var array<string, string> */
    public array $values = [];

    /** @var array<string, string> */
    public array $measuredAt = [];

    /** @var array<string, string> */
    public array $source = [];

    /** @var array<string, string> */
    public array $notes = [];

    /**
     * @var array<string, array{label: string, unit: string, min: float, max: float}>
     */
    public array $availableParameters = [
        'peso' => [
            'label' => 'Peso',
            'unit' => 'kg',
            'min' => 0.1,
            'max' => 500,
        ],
        'saturazione' => [
            'label' => 'Saturazione',
            'unit' => '%',
            'min' => 0,
            'max' => 100,
        ],
        'frequenza cardiaca' => [
            'label' => 'Frequenza cardiaca',
            'unit' => 'bpm',
            'min' => 20,
            'max' => 250,
        ],
        'temperatura corporea' => [
            'label' => 'Temperatura corporea',
            'unit' => '°C',
            'min' => 25,
            'max' => 45,
        ],
        'pressione sanguigna' => [
            'label' => 'Pressione sanguigna sistolica',
            'unit' => 'mmHg',
            'min' => 50,
            'max' => 300,
        ],
    ];

    public function mount(): void
    {
        foreach (array_keys($this->availableParameters) as $type) {
            $this->measuredAt[$type] = now()->format('Y-m-d\\TH:i');
            $this->source[$type] = 'manual';
        }
    }

    public function save(): void
    {
        if (! collect($this->selectedParameters)->contains(fn ($selected): bool => $selected === '1' || $selected === true)) {
            $this->addError('selectedParameters', 'Seleziona almeno un parametro.');

            return;
        }

        $this->validate($this->rules());

        /** @var User $user */
        $user = Auth::user();

        foreach ($this->selectedParameters as $type => $selected) {
            if ($selected !== '1' && $selected !== true) {
                continue;
            }

            $parameter = $this->availableParameters[$type];

            $user->healthParameters()->create([
                'type' => $type,
                'value' => $this->values[$type],
                'unit' => $parameter['unit'],
                'measured_at' => $this->measuredAt[$type],
                'source' => $this->source[$type],
                'notes' => $this->notes[$type] ?? null,
            ]);
        }

        session()->flash('success', 'Parametri registrati con successo.');
        $this->redirect(route('guest.parameters'), navigate: true);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        $rules = [
            'selectedParameters' => ['required', 'array:peso,saturazione,frequenza cardiaca,temperatura corporea,pressione sanguigna', 'min:1'],
            'selectedParameters.*' => ['boolean'],
            'measuredAt' => ['required', 'array'],
            'measuredAt.*' => ['nullable', 'date', 'before_or_equal:now'],
            'source' => ['required', 'array'],
            'source.*' => ['nullable', 'in:wearable,iot,device,manual'],
            'notes' => ['array'],
            'notes.*' => ['nullable', 'string', 'max:1000'],
        ];

        foreach ($this->availableParameters as $type => $parameter) {
            $rules["values.$type"] = [
                'required_if:selectedParameters.'.$type.',1,true',
                'numeric',
                'between:'.$parameter['min'].','.$parameter['max'],
            ];
            $rules["measuredAt.$type"] = [
                'required_if:selectedParameters.'.$type.',1,true',
                'date',
                'before_or_equal:now',
            ];
            $rules["source.$type"] = [
                'required_if:selectedParameters.'.$type.',1,true',
                'in:wearable,iot,device,manual',
            ];
        }

        return $rules;
    }

    public function render(): View
    {
        return view('livewire.pages.guest.parameters.create-parameters');
    }
}
