<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\Promotion;
use App\Models\Reward;
use App\Models\RewardAchiever;
use App\Services\RewardAchieverService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProcessDailyRewardsCommandTest extends TestCase
{
    use RefreshDatabase;

    private int $memberSequence = 1;

    public function test_command_creates_rewards_for_active_promotions(): void
    {
        $promotion = Promotion::query()->create([
            'name' => 'April Promo',
            'start_date' => '2026-04-01',
            'end_date' => '2026-04-30',
            'status' => 'A',
        ]);

        $referrer = $this->createMember(overrides: [
            'name' => 'Referrer One',
            'email' => 'referrer-one@example.test',
            'referral_code' => 'REF001',
        ]);

        $this->createReferredMembers($referrer, 10, '2026-04-10 10:00:00');

        $this->artisan('rewards:process-daily', ['--date' => '2026-04-20'])
            ->assertExitCode(0);

        $reward = Reward::query()
            ->where('promotion_id', $promotion->id)
            ->where('referral_count', 10)
            ->first();

        $this->assertNotNull($reward);
        $this->assertSame(100, $reward->reward_value);
        $achiever = RewardAchiever::query()
            ->where('member_id', $referrer->id)
            ->where('reward_id', $reward->id)
            ->first();

        $this->assertNotNull($achiever);
        $this->assertSame('2026-04-20', $achiever->achieved_at?->toDateString());
        $this->assertSame('Referrer One', $achiever->member_name_snapshot);
        $this->assertSame('referrer-one@example.test', $achiever->member_email_snapshot);
        $this->assertSame('REF001', $achiever->member_referral_code_snapshot);
    }

    public function test_command_skips_inactive_or_out_of_window_promotions(): void
    {
        Promotion::query()->create([
            'name' => 'Inactive Promotion',
            'start_date' => '2026-04-01',
            'end_date' => '2026-04-30',
            'status' => 'I',
        ]);

        Promotion::query()->create([
            'name' => 'Future Promotion',
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-31',
            'status' => 'A',
        ]);

        $referrer = $this->createMember();
        $this->createReferredMembers($referrer, 20, '2026-04-10 11:00:00');

        $this->artisan('rewards:process-daily', ['--date' => '2026-04-20'])
            ->assertExitCode(0);

        $this->assertDatabaseCount('reward', 0);
        $this->assertDatabaseCount('reward_achievers', 0);
    }

    public function test_command_is_idempotent_across_repeated_runs(): void
    {
        Promotion::query()->create([
            'name' => 'Idempotent Promotion',
            'start_date' => '2026-04-01',
            'end_date' => '2026-04-30',
            'status' => 'A',
        ]);

        $referrer = $this->createMember();
        $this->createReferredMembers($referrer, 50, '2026-04-12 09:30:00');

        $this->artisan('rewards:process-daily', ['--date' => '2026-04-20'])
            ->assertExitCode(0);
        $this->artisan('rewards:process-daily', ['--date' => '2026-04-20'])
            ->assertExitCode(0);

        $this->assertSame(2, RewardAchiever::query()->where('member_id', $referrer->id)->count());
        $this->assertDatabaseCount('reward_achievers', 2);
    }

    public function test_command_honors_explicit_run_date(): void
    {
        Promotion::query()->create([
            'name' => 'May Promotion',
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-31',
            'status' => 'A',
        ]);

        $referrer = $this->createMember();
        $this->createReferredMembers($referrer, 10, '2026-05-10 14:00:00');

        $this->artisan('rewards:process-daily', ['--date' => '2026-04-20'])
            ->assertExitCode(0);
        $this->assertDatabaseCount('reward_achievers', 0);

        $this->artisan('rewards:process-daily', ['--date' => '2026-05-20'])
            ->assertExitCode(0);
        $this->assertDatabaseCount('reward_achievers', 1);
    }

    public function test_realtime_and_daily_processing_coexist_without_duplicates(): void
    {
        Promotion::query()->create([
            'name' => 'Realtime Promotion',
            'start_date' => '2026-04-01',
            'end_date' => '2026-04-30',
            'status' => 'A',
        ]);

        $referrer = $this->createMember();
        $this->createReferredMembers($referrer, 10, '2026-04-08 13:00:00');

        Carbon::setTestNow('2026-04-20 09:00:00');
        app(RewardAchieverService::class)->evaluateMemberForActivePromotions($referrer->id);
        Carbon::setTestNow();

        $this->assertDatabaseCount('reward_achievers', 1);

        $this->artisan('rewards:process-daily', ['--date' => '2026-04-20'])
            ->assertExitCode(0);

        $this->assertDatabaseCount('reward_achievers', 1);
    }

    public function test_scheduler_registers_daily_rewards_command_at_0010(): void
    {
        Artisan::call('schedule:list');
        $output = Artisan::output();

        $this->assertStringContainsString('rewards:process-daily', $output);
        $this->assertMatchesRegularExpression('/10\s+0\s+\*\s+\*\s+\*/', $output);
    }

    private function createReferredMembers(Member $referrer, int $count, string $createdAt): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->createMember(referrer: $referrer, createdAt: $createdAt);
        }
    }

    private function createMember(
        ?Member $referrer = null,
        ?string $createdAt = null,
        array $overrides = []
    ): Member {
        $sequence = $this->memberSequence++;
        $defaults = [
            'name' => 'Member ' . $sequence,
            'email' => 'member' . $sequence . '@example.test',
            'phone' => '01234' . str_pad((string) $sequence, 5, '0', STR_PAD_LEFT),
            'dob' => '1990-01-01',
            'gender' => 'M',
            'referral_id' => $referrer?->id,
            'referral_code' => 'R' . str_pad((string) $sequence, 5, '0', STR_PAD_LEFT),
        ];

        if ($createdAt !== null) {
            Carbon::setTestNow($createdAt);
        }

        try {
            return Member::query()->create(array_merge($defaults, $overrides));
        } finally {
            if ($createdAt !== null) {
                Carbon::setTestNow();
            }
        }
    }
}
