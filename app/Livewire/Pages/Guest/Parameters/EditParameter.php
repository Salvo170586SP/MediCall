<?php

namespace App\Livewire\Pages\Guest\Parameters;

use App\Models\HealthParameter;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditParameter extends Component
{
    public HealthParameter $parameter;

    public string $type = '';
    public string $value = '';
    public string $unit = '';
    public string $measuredAt = '';
    public string $source = '';
    public string $notes = '';

    /**
     * @var array<string, array{label: string, unit: string, min: float, max: float}>
     */
    public array $parameterTypes = [
        'peso' => ['label' => 'Peso', 'unit' => 'kg', 'min' => 0.1, 'max' => 500],
        'weight' => ['label' => 'Peso', 'unit' => 'kg', 'min' => 0.1, 'max' => 500],
        'saturazione' => ['label' => 'Saturazione', 'unit' => '%', 'min' => 0, 'max' => 100],
        'oxygen_saturation' => ['label' => 'Saturazione', 'unit' => '%', 'min' => 0, 'max' => 100],
        'frequenza cardiaca' => ['label' => 'Frequenza cardiaca', 'unit' => 'bpm', 'min' => 20, 'max' => 250],
        'heart_rate' => ['label' => 'Frequenza cardiaca', 'unit' => 'bpm', 'min' => 20, 'max' => 250],
        'temperatura corporea' => ['label' => 'Temperatura corporea', 'unit' => '°C', 'min' => 25, 'max' => 45],
        'body_temperature' => ['label' => 'Temperatura corporea', 'unit' => '°C', 'min' => 25, 'max' => 45],
        'pressione sanguigna' => ['label' => 'Pressione sanguigna sistolica', 'unit' => 'mmHg', 'min' => 50, 'max' => 300],
        'blood_pressure' => ['label' => 'Pressione sanguigna sistolica', 'unit' => 'mmHg', 'min' => 50, 'max' => 300],
    ];

    public function mount(HealthParameter $parameter): void
    {
        /** @var User $authenticatedUser */
        $authenticatedUser = Auth::user();
        $this->parameter = $authenticatedUser->healthParameters()->findOrFail($parameter->id);
        $this->type = $this->parameter->type;
        $this->value = (string) $this->parameter->value;
        $this->unit = $this->parameter->unit;
        $this->measuredAt = $this->parameter->measured_at->format('Y-m-d\\TH:i');
        $this->source = $this->parameter->source;
        $this->notes = $this->parameter->notes ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate($this->rules());

        $this->parameter->update([
            'value' => $validated['value'],
            'measured_at' => $validated['measuredAt'],
            'source' => $validated['source'],
            'notes' => $validated['notes'] ?? null,
        ]);

        session()->flash('success', 'Parametro modificato con successo.');
        $this->redirect(route('guest.parameters'), navigate: true);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        $limits = $this->parameterTypes[$this->type] ?? null;

        return [
            'value' => [
                'required',
                'numeric',
                ...($limits ? ['between:'.$limits['min'].','.$limits['max']] : []),
            ],
            'measuredAt' => ['required', 'date', 'before_or_equal:now'],
            'source' => ['required', 'in:wearable,iot,device,manual'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function render(): View
    {
        return view('livewire.pages.guest.parameters.edit-parameter');
    }
}
