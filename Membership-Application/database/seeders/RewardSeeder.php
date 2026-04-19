<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\Reward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promotion = Promotion::where('name', 'April Promotion')->first();
        if (!$promotion) {
            return;
        }

        $fixedTiers = [
            10 => 100,
            50 => 500,
            100 => 1000,
        ];

        foreach ($fixedTiers as $referralCount => $rewardValue) {
            Reward::updateOrCreate(
                [
                    'promotion_id' => $promotion->id,
                    'referral_count' => $referralCount,
                ],
                [
                    'reward_value' => $rewardValue,
                ]
            );
        }

        // Tier 4 is uncapped and resolved dynamically:
        // each 10 referrals after 100 earns USD 150 (110, 120, 130, ...).
    }
}
