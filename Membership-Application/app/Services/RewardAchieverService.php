<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Promotion;
use App\Models\Reward;
use App\Models\RewardAchiever;

class RewardAchieverService
{
    public function evaluateMemberForActivePromotions(int $memberId): void
    {
        $member = Member::find($memberId);
        if (!$member) {
            return;
        }

        $today = now()->toDateString();
        $activePromotions = Promotion::query()
            ->where('status', 'A')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();

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

                RewardAchiever::firstOrCreate(
                    [
                        'member_id' => $member->id,
                        'reward_id' => $reward->id,
                    ],
                    [
                        'achieved_at' => $today,
                        'member_name_snapshot' => $member->name,
                        'member_email_snapshot' => $member->email,
                        'member_referral_code_snapshot' => $member->referral_code,
                    ]
                );
            }
        }
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
