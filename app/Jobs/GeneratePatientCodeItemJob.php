<?php

namespace App\Jobs;

use App\Models\GuestDetail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use RuntimeException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class GeneratePatientCodeItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $patientId) {}

    public function uniqueId(): string
    {
        return 'patient-code-item-' . $this->patientId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $guestDetail = GuestDetail::query()
            ->where('guest_id', $this->patientId)
            ->first();

        if ($guestDetail === null || filled($guestDetail->code_item)) {
            return;
        }

        $nextNumber = $this->resolveNextCodeNumber();

        foreach (range(0, 49) as $offset) {
            $candidate = $this->formatCodeItem($nextNumber + $offset);

            try {
                $updated = DB::transaction(function () use ($guestDetail, $candidate): bool {
                    $guestDetail->refresh();

                    if (filled($guestDetail->code_item)) {
                        return true;
                    }

                    return (bool) $guestDetail->newQuery()
                        ->where('id', $guestDetail->id)
                        ->whereNull('code_item')
                        ->update(['code_item' => $candidate]);
                });
            } catch (QueryException $exception) {
                if ($exception->getCode() !== '23000') {
                    throw $exception;
                }

                continue;
            }

            if ($updated) {
                return; 
            }
        }

        throw new RuntimeException("Unable to generate a unique code_item for patient {$this->patientId}.");
    }

    protected function resolveNextCodeNumber(): int
    {
        $codes = GuestDetail::query()
            ->whereNotNull('code_item')
            ->pluck('code_item')
            ->filter(fn(string $code): bool => preg_match('/^G-\d{6}$/', $code) === 1)
            ->map(fn(string $code): int => (int) substr($code, 4));

        return $codes->isEmpty() ? 1 : $codes->max() + 1;
    }

    protected function formatCodeItem(int $number): string
    {
        return sprintf('G-%06d', $number);
    }
}
