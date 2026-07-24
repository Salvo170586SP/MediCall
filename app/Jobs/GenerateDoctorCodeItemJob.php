<?php

namespace App\Jobs;

use App\Models\DoctorDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Illuminate\Database\QueryException;

class GenerateDoctorCodeItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $doctorId) {}

    public function uniqueId(): string
    {
        return 'doctor-code-item-' . $this->doctorId;
    }

    public function handle(): void
    {
        $doctorDetail = DoctorDetail::query()
            ->where('doctor_id', $this->doctorId)
            ->first();

        if ($doctorDetail === null || filled($doctorDetail->code_item)) {
            return;
        }

        $nextNumber = $this->resolveNextCodeNumber();

        foreach (range(0, 49) as $offset) {
            $candidate = $this->formatCodeItem($nextNumber + $offset);

            try {
                $updated = DB::transaction(function () use ($doctorDetail, $candidate): bool {
                    $doctorDetail->refresh();

                    if (filled($doctorDetail->code_item)) {
                        return true;
                    }

                    return (bool) $doctorDetail->newQuery()
                        ->where('id', $doctorDetail->id)
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
                return; // ✅ successo, esci subito dal job
            }
        }

        throw new RuntimeException("Unable to generate a unique code_item for doctor {$this->doctorId}.");
    }

    protected function resolveNextCodeNumber(): int
    {
        $codes = DoctorDetail::query()
            ->whereNotNull('code_item')
            ->pluck('code_item')
            ->filter(fn(string $code): bool => preg_match('/^D-\d{6}$/', $code) === 1)
            ->map(fn(string $code): int => (int) substr($code, 4));

        return $codes->isEmpty() ? 1 : $codes->max() + 1;
    }

    protected function formatCodeItem(int $number): string
    {
        return sprintf('D-%06d', $number);
    }
}
