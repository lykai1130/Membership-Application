<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\RewardAchiever;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RewardAchieverController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'member_id' => ['nullable', 'integer', 'exists:members,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        if (
            !empty($filters['start_date']) &&
            !empty($filters['end_date']) &&
            $filters['start_date'] > $filters['end_date']
        ) {
            return back()->withErrors(['date' => 'Start date must be before or equal to end date.']);
        }

        $rows = $this->buildBaseQuery($filters)
            ->orderByDesc('achieved_at')
            ->orderByDesc('id')
            ->get();

        $members = Member::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('reward-report', [
            'rows' => $rows,
            'members' => $members,
            'filters' => $filters,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->validate([
            'member_id' => ['nullable', 'integer', 'exists:members,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'format' => ['nullable', 'in:csv,excel'],
        ]);

        if (
            !empty($filters['start_date']) &&
            !empty($filters['end_date']) &&
            $filters['start_date'] > $filters['end_date']
        ) {
            abort(422, 'Start date must be before or equal to end date.');
        }

        $format = $filters['format'] ?? 'csv';
        $rows = $this->buildBaseQuery($filters)
            ->orderByDesc('achieved_at')
            ->orderByDesc('id')
            ->get();

        $filename = 'reward-report-' . now()->format('YmdHis') . ($format === 'excel' ? '.xls' : '.csv');
        $headers = [
            'Content-Type' => $format === 'excel'
                ? 'application/vnd.ms-excel; charset=UTF-8'
                : 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->streamDownload(function () use ($rows, $format) {
            $output = fopen('php://output', 'wb');
            if ($output === false) {
                return;
            }

            if ($format !== 'excel') {
                fwrite($output, "\xEF\xBB\xBF");
            }

            $header = [
                'Achieved Date',
                'Member Name',
                'Member Email',
                'Promotion',
                'Promotion Start',
                'Promotion End',
                'Referral Count',
                'Reward Value (USD)',
            ];

            fputcsv($output, $header);

            foreach ($rows as $row) {
                fputcsv($output, [
                    optional($row->achieved_at)->format('Y-m-d') ?? '',
                    $row->member?->name ?? $row->member_name_snapshot ?? '',
                    $row->member?->email ?? $row->member_email_snapshot ?? '',
                    $row->reward?->promotion?->name ?? '',
                    optional($row->reward?->promotion?->start_date)->format('Y-m-d') ?? '',
                    optional($row->reward?->promotion?->end_date)->format('Y-m-d') ?? '',
                    $row->reward?->referral_count ?? '',
                    $row->reward?->reward_value ?? '',
                ]);
            }

            fclose($output);
        }, $filename, $headers);
    }

    private function buildBaseQuery(array $filters)
    {
        return RewardAchiever::query()
            ->with([
                'member:id,name,email,referral_code',
                'reward:id,promotion_id,referral_count,reward_value',
                'reward.promotion:id,name,start_date,end_date',
            ])
            ->when(!empty($filters['member_id']), function ($query) use ($filters) {
                $query->where('member_id', (int) $filters['member_id']);
            })
            ->when(!empty($filters['start_date']), function ($query) use ($filters) {
                $query->whereDate('achieved_at', '>=', $filters['start_date']);
            })
            ->when(!empty($filters['end_date']), function ($query) use ($filters) {
                $query->whereDate('achieved_at', '<=', $filters['end_date']);
            });
    }
}
