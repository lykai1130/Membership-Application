<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $firstMember = Member::updateOrCreate(
            ['email' => 'member1@example.com'],
            [
                'name' => 'Member 1',
                'phone' => '0100000001',
                'dob' => '1990-01-01',
                'gender' => 'M',
                'referral_id' => null,
                'referral_code' => 'REF001',
            ]
        );

        for ($i = 2; $i <= 11; $i++) {
            Member::updateOrCreate(
                ['email' => 'member' . $i . '@example.com'],
                [
                    'name' => 'Member ' . $i,
                    'phone' => '01000000' . str_pad((string) $i, 2, '0', STR_PAD_LEFT),
                    'dob' => '1990-01-' . str_pad((string) min($i, 28), 2, '0', STR_PAD_LEFT),
                    'gender' => $i % 2 === 0 ? 'F' : 'M',
                    'referral_id' => $firstMember->id,
                    'referral_code' => 'REF' . str_pad((string) $i, 3, '0', STR_PAD_LEFT),
                ]
            );
        }
    }
}
