<?php

namespace App\Console\Commands;

use App\Models\Sangh;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class UpdateSanghStatus extends Command
{
    protected $signature = 'sangh:update-status
                            {--date= : Simulate a specific date (YYYY-MM-DD) instead of today}
                            {--dry-run : Show what would change without saving}';

    protected $description = 'Auto-update Sangh status based on date. Use --date=YYYY-MM-DD and --dry-run to test.';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $today  = $this->option('date')
            ? Carbon::parse($this->option('date'))->toDateString()
            : Carbon::today()->toDateString();

        $this->info($dryRun
            ? "DRY RUN — simulating date: {$today}"
            : "Running for date: {$today}"
        );

        $sanghs = Sangh::with('event')
            ->whereNotIn('status', ['completed'])
            ->get();

        if ($sanghs->isEmpty()) {
            $this->warn('No active Sanghs found.');
            return self::SUCCESS;
        }

        $updated = 0;

        /** @var Sangh $sangh */
        foreach ($sanghs as $sangh) {
            $startDate = $sangh->startDate()?->toDateString() ?? '—';
            $endDate   = $sangh->end_date?->toDateString()   ?? '—';
            $regFrom   = $sangh->registration_open_from?->toDateString()  ?? '—';
            $regUntil  = $sangh->registration_open_until?->toDateString() ?? '—';

            $this->line('');
            $this->line("  <fg=cyan>Sangh {$sangh->year}</> (ID: {$sangh->id})");
            $this->line("    Reg window : {$regFrom} → {$regUntil}");
            $this->line("    Walk dates : {$startDate} → {$endDate}");
            $this->line("    Current    : <fg=yellow>{$sangh->status}</>");

            $newStatus = $this->resolveStatus($sangh, $today);

            if (!$newStatus) {
                $this->line('    Result     : <fg=gray>no change (before registration window)</>');
                continue;
            }

            if ($newStatus === $sangh->status) {
                $this->line("    Result     : <fg=gray>no change (already {$sangh->status})</>");
                continue;
            }

            $this->line("    Result     : <fg=green>{$sangh->status} → {$newStatus}</>");

            if (!$dryRun) {
                $sangh->update(['status' => $newStatus]);
                $sangh->event?->update(['status' => $this->eventStatus($newStatus)]);
            }

            $updated++;
        }

        $this->line('');

        if ($dryRun) {
            $this->warn($updated > 0
                ? "{$updated} Sangh(s) would be updated (dry run — nothing saved)."
                : 'No changes would be made.'
            );
        } else {
            $this->info($updated > 0
                ? "{$updated} Sangh(s) updated."
                : 'No status changes needed.'
            );
        }

        return self::SUCCESS;
    }

    private function resolveStatus(Sangh $sangh, string $today): ?string
    {
        $startDate = $sangh->startDate()?->toDateString();   // event_date (day 1)
        $endDate   = $sangh->end_date?->toDateString();       // day 2 (end of walk)
        $regFrom   = $sangh->registration_open_from?->toDateString();
        $regUntil  = $sangh->registration_open_until?->toDateString();

        // Day after walk ends → completed
        if ($endDate && $today > $endDate) {
            return 'completed';
        }

        // Walk day 1 or day 2 → in_progress
        if ($startDate && $endDate && ($today === $startDate || $today === $endDate)) {
            return 'in_progress';
        }

        // Within registration window → registration_open
        if ($regFrom && $regUntil && $today >= $regFrom && $today <= $regUntil) {
            return 'registration_open';
        }

        // After registration closed but before walk starts → registration_closed
        if ($regUntil && $startDate && $today > $regUntil && $today < $startDate) {
            return 'registration_closed';
        }

        // Nothing to change yet (before registration opens)
        return null;
    }

    private function eventStatus(string $sanghStatus): string
    {
        return match ($sanghStatus) {
            'in_progress' => 'ongoing',
            'completed'   => 'completed',
            default       => 'upcoming',
        };
    }
}
