<?php

namespace Database\Seeders;

use App\Models\Promotion;
use App\Models\Reward;
use App\Models\RewardAchiever;
use Illuminate\Database\Seeder;

class RewardAchieverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RewardAchiever::query()->firstOrCreate(
            [
                'member_id' => 1,
                'reward_id' => 1,
            ],
            [
                'achieved_at' => now()->toDateString(),
                'member_name_snapshot' => 'Member 1',
                'member_email_snapshot' => 'member1@example.com',
                'member_referral_code_snapshot' => 'REF001',
            ]
        );
    }
}
