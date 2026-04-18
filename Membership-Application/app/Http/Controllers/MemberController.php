<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index() {
        $members = Member::query()
            ->leftJoin('members as ref_members', 'members.referral_id', '=', 'ref_members.id')
            ->select([
                'members.*',
                DB::raw('ref_members.name as referral_by_name'),
            ])
            ->orderByDesc('members.created_at')
            ->get();

        return view('member-list', compact('members'));
    }

    public function save(Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('members', 'email')],
            'phone' => ['required', 'string', 'max:30'],
            'dob' => ['required', 'date'],
            'gender' => ['required', 'string'],
            'referral_code' => ['nullable', 'string', 'size:6'],
        ]);

        $normalizedGender = strtoupper(trim($validated['gender']));
        if ($normalizedGender === 'MALE') {
            $normalizedGender = 'M';
        }
        if ($normalizedGender === 'FEMALE') {
            $normalizedGender = 'F';
        }

        if (!in_array($normalizedGender, ['M', 'F'], true)) {
            return back()
                ->withInput()
                ->withErrors(['gender' => 'Gender must be Male or Female.']);
        }

        $referralMemberId = null;
        if (!empty($validated['referral_code'])) {
            $referralCode = strtoupper(trim($validated['referral_code']));
            $referrer = Member::where('referral_code', $referralCode)->first();

            if (!$referrer) {
                return back()
                    ->withInput()
                    ->withErrors(['referral_code' => 'Invalide Referral Code']);
            }

            $referralMemberId = $referrer->id;
        }

        $generatedReferralCode = $this->generateReferralCode();

        Member::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'dob' => $validated['dob'],
            'gender' => $normalizedGender,
            'referral_id' => $referralMemberId,
            'referral_code' => $generatedReferralCode,
        ]);

        return back()->with('success', 'Member registered successfully.');
    }

    public function update() {}

    public function delete() {}

    private function generateReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (Member::where('referral_code', $code)->exists());

        return $code;
    }
}
