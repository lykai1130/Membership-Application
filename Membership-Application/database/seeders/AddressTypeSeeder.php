<?php

namespace Database\Seeders;

use App\Models\AddressType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressTypeSeeder extends Seeder
{
    /**
     * Seed the application's address types.
     */
    public function run(): void
    {
        AddressType::updateOrCreate(
            ['type' => 'Residential Address'],
            ['status' => 'A']
        );

        AddressType::updateOrCreate(
            ['type' => 'Correspondence Address'],
            ['status' => 'A']
        );
    }
}
