<?php

namespace Database\Seeders;

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
        Reward::updateOrCreate(
            ['promotion_id' => 1, 'referral_count' => 10],
            [
                'reward_value' => 100,
            ]
        );

        Reward::updateOrCreate(
            ['promotion_id' => 1, 'referral_count' => 50],
            [
                'reward_value' => 500,
            ]
        );

        Reward::updateOrCreate(
            ['promotion_id' => 1, 'referral_count' => 100],
            [
                'reward_value' => 1000,
            ]
        );
    }
}
