<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\AddressType;
use App\Models\Member;
use App\Models\Documents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function registration()
    {
        $addressTypes = AddressType::query()
            ->where('status', 'A')
            ->orderBy('id')
            ->limit(2)
            ->get();

        return view('registration', compact('addressTypes'));
    }

    public function index() {
        $members = Member::query()
            ->select([
                'members.id',
                'members.name',
                'members.email',
                'members.referral_code',
            ])
            ->orderByDesc('members.created_at')
            ->get();

        return view('member-list', compact('members'));
    }

    public function edit(Member $member)
    {
        return view('member-edit', compact('member'));
    }

    public function save(Request $request) {
        $addressTypes = AddressType::query()
            ->where('status', 'A')
            ->orderBy('id')
            ->limit(2)
            ->get();

        $addressTypeIds = $addressTypes->pluck('id')->all();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('members', 'email')],
            'phone' => ['required', 'string', 'max:30'],
            'dob' => ['required', 'date'],
            'gender' => ['required', 'string'],
            'referral_code' => ['nullable', 'string', 'size:6'],
            'addresses' => ['required', 'array', 'min:1', 'max:2'],
            'addresses.*.address_type_id' => ['required', Rule::in($addressTypeIds)],
            'addresses.*.line1' => ['required', 'string', 'max:255'],
            'addresses.*.line2' => ['nullable', 'string', 'max:255'],
            'addresses.*.city' => ['required', 'string', 'max:100'],
            'addresses.*.state' => ['required', 'string', 'max:100'],
            'addresses.*.postal_code' => ['required', 'string', 'max:20'],
            'addresses.*.country' => ['required', 'string', 'max:100'],
        ]);

        $normalizedGender = $this->normalizeGender($validated['gender']);
        if ($normalizedGender === null) {
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

        // Ensure each selected address type appears only once.
        $uniqueAddressTypeIds = collect($validated['addresses'])
            ->pluck('address_type_id')
            ->unique()
            ->values();

        if ($uniqueAddressTypeIds->count() !== count($validated['addresses'])) {
            return back()
                ->withInput()
                ->withErrors(['addresses' => 'Each address type can only be entered once.']);
        }

        DB::transaction(function () use ($request, $validated, $normalizedGender, $referralMemberId, $generatedReferralCode) {
            $member = Member::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'dob' => $validated['dob'],
                'gender' => $normalizedGender,
                'referral_id' => $referralMemberId,
                'referral_code' => $generatedReferralCode,
            ]);

            $createdAddresses = [];
            foreach ($validated['addresses'] as $addressInput) {
                $createdAddresses[] = Address::create([
                    'member_id' => $member->id,
                    'address_type_id' => $addressInput['address_type_id'],
                    'line1' => $addressInput['line1'],
                    'line2' => $addressInput['line2'] ?? null,
                    'city' => $addressInput['city'],
                    'state' => $addressInput['state'],
                    'postal_code' => $addressInput['postal_code'],
                    'country' => $addressInput['country'],
                ]);
            }

            $this->storeUploadedDocument($request, $member, 'avatar_image', 'Avatar Image');

            // Attach proof-of-address to an address documentable when available.
            $proofDocumentable = $createdAddresses[0] ?? $member;
            $this->storeUploadedDocument($request, $proofDocumentable, 'proof_of_address', 'Proof Of Address');
        });

        return back()->with('success', 'Member registered successfully.');
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('members', 'email')->ignore($member->id)],
            'phone' => ['required', 'string', 'max:30'],
            'dob' => ['required', 'date'],
            'gender' => ['required', 'string'],
        ]);

        $normalizedGender = $this->normalizeGender($validated['gender']);
        if ($normalizedGender === null) {
            return back()
                ->withInput()
                ->withErrors(['gender' => 'Gender must be Male or Female.']);
        }

        $member->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'dob' => $validated['dob'],
            'gender' => $normalizedGender,
        ]);

        return back()->with('success', 'Member updated successfully.');
    }

    public function delete() {}

    private function generateReferralCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (Member::where('referral_code', $code)->exists());

        return $code;
    }

    private function normalizeGender(string $gender): ?string
    {
        $normalizedGender = strtoupper(trim($gender));
        if ($normalizedGender === 'MALE') {
            return 'M';
        }
        if ($normalizedGender === 'FEMALE') {
            return 'F';
        }

        return in_array($normalizedGender, ['M', 'F'], true) ? $normalizedGender : null;
    }

    private function storeUploadedDocument(Request $request, Model $documentable, string $fieldName, string $documentName): void
    {
        if (!$request->hasFile($fieldName)) {
            return;
        }

        $file = $request->file($fieldName);
        if (!$file || !$file->isValid()) {
            return;
        }

        $extension = $file->getClientOriginalExtension();
        $safeDocumentLabel = Str::of($documentName)->lower()->replace(' ', '-');
        $storedFileName = $safeDocumentLabel . '-' . now()->format('YmdHis') . '-' . Str::random(6);
        if (!empty($extension)) {
            $storedFileName .= '.' . $extension;
        }

        $documentFolder = Str::plural(Str::kebab(class_basename($documentable))) . '/' . $documentable->getKey();
        $storedPath = $file->storeAs(
            'documents/' . $documentFolder,
            (string) $storedFileName,
            'public'
        );

        Documents::create([
            'documentable_id' => $documentable->getKey(),
            'documentable_type' => $documentable::class,
            'document_name' => $documentName,
            'document_path' => $storedPath,
        ]);
    }
}
