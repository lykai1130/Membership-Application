<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Promotion;
use App\Models\Reward;
use App\Models\RewardAchiever;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class RewardAchieverService
{
    public function evaluateMemberForActivePromotions(int $memberId): void
    {
        $member = Member::query()->find($memberId);
        if (!$member) {
            return;
        }

        $runDate = now()->toDateString();
        $activePromotions = $this->activePromotionsForDate($runDate);
        if ($activePromotions->isEmpty()) {
            return;
        }

        $this->processMemberRewards($member, $activePromotions, $runDate);
    }

    public function processAllMembersForDate(?string $runDate = null): array
    {
        $resolvedDate = $this->resolveRunDate($runDate);
        $activePromotions = $this->activePromotionsForDate($resolvedDate);

        $membersProcessed = 0;
        $rewardsInserted = 0;

        if ($activePromotions->isEmpty()) {
            return [
                'run_date' => $resolvedDate,
                'active_promotions' => 0,
                'members_processed' => 0,
                'rewards_inserted' => 0,
            ];
        }

        Member::query()
            ->select(['id', 'name', 'email', 'referral_code'])
            ->orderBy('id')
            ->chunkById(500, function ($members) use (
                $activePromotions,
                $resolvedDate,
                &$membersProcessed,
                &$rewardsInserted
            ) {
                foreach ($members as $member) {
                    $membersProcessed++;
                    $rewardsInserted += $this->processMemberRewards($member, $activePromotions, $resolvedDate);
                }
            });

        return [
            'run_date' => $resolvedDate,
            'active_promotions' => $activePromotions->count(),
            'members_processed' => $membersProcessed,
            'rewards_inserted' => $rewardsInserted,
        ];
    }

    private function resolveRunDate(?string $runDate): string
    {
        if ($runDate === null || trim($runDate) === '') {
            return now()->toDateString();
        }

        $resolvedDate = Carbon::createFromFormat('Y-m-d', $runDate);
        if (!$resolvedDate || $resolvedDate->format('Y-m-d') !== $runDate) {
            throw new InvalidArgumentException('The --date option must use format YYYY-MM-DD.');
        }

        return $resolvedDate->toDateString();
    }

    private function activePromotionsForDate(string $runDate): Collection
    {
        return Promotion::query()
            ->where('status', 'A')
            ->whereDate('start_date', '<=', $runDate)
            ->whereDate('end_date', '>=', $runDate)
            ->orderBy('id')
            ->get(['id', 'start_date', 'end_date']);
    }

    private function processMemberRewards(Member $member, Collection $activePromotions, string $runDate): int
    {
        $createdCount = 0;

        foreach ($activePromotions as $promotion) {
            $referralCount = Member::query()
                ->where('referral_id', $member->id)
                ->whereDate('created_at', '>=', $promotion->start_date)
                ->whereDate('created_at', '<=', $promotion->end_date)
                ->count();

            $milestones = $this->eligibleMilestones($referralCount);
            foreach ($milestones as $milestone) {
                $reward = Reward::resolveForPromotion($promotion->id, $milestone);
                if (!$reward) {
                    continue;
                }

                $rewardAchiever = RewardAchiever::firstOrCreate(
                    [
                        'member_id' => $member->id,
                        'reward_id' => $reward->id,
                    ],
                    [
                        'achieved_at' => $runDate,
                        'member_name_snapshot' => $member->name,
                        'member_email_snapshot' => $member->email,
                        'member_referral_code_snapshot' => $member->referral_code,
                    ]
                );

                if ($rewardAchiever->wasRecentlyCreated) {
                    $createdCount++;
                }
            }
        }

        return $createdCount;
    }

    private function eligibleMilestones(int $referralCount): array
    {
        if ($referralCount < 10) {
            return [];
        }

        $milestones = [];
        foreach ([10, 50, 100] as $fixedTier) {
            if ($referralCount >= $fixedTier) {
                $milestones[] = $fixedTier;
            }
        }

        if ($referralCount > 100) {
            for ($tier = 110; $tier <= $referralCount; $tier += 10) {
                $milestones[] = $tier;
            }
        }

        return $milestones;
    }
}
