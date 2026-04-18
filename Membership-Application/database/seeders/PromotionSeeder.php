<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Seed the application's address types.
     */
    public function run(): void
    {
        Promotion::updateOrCreate(
            ['name' => 'April Promotion'],
            [
                'start_date' => '2026-04-01',
                'end_date' => '2026-04-30',
                'status' => 'A',
            ]
        );
    }
}
