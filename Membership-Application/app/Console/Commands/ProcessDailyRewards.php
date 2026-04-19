<?php

namespace App\Console\Commands;

use App\Services\RewardAchieverService;
use Illuminate\Console\Command;
use InvalidArgumentException;

class ProcessDailyRewards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rewards:process-daily {--date= : Run date in YYYY-MM-DD format}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process daily rewards for all members against active promotions';

    /**
     * Execute the console command.
     */
    public function handle(RewardAchieverService $rewardAchieverService): int
    {
        try {
            $stats = $rewardAchieverService->processAllMembersForDate($this->option('date'));
        } catch (InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Daily reward processing completed.');
        $this->line('Run Date: ' . $stats['run_date']);
        $this->line('Active Promotions: ' . $stats['active_promotions']);
        $this->line('Members Processed: ' . $stats['members_processed']);
        $this->line('Rewards Inserted: ' . $stats['rewards_inserted']);

        return self::SUCCESS;
    }
}
