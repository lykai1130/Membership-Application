<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reward Report</title>
    <style>
        :root {
            --bg: #eef4ff;
            --card: #ffffff;
            --line: #d1d5db;
            --text: #111827;
            --muted: #6b7280;
            --primary: #1d4ed8;
            --primary-dark: #1e40af;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(145deg, #f8fbff, var(--bg));
            color: var(--text);
            padding: 24px;
        }

        .wrapper {
            width: min(1200px, 100%);
            margin: 0 auto;
            background: var(--card);
            border-radius: 16px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
            padding: 22px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        h1 {
            margin: 0;
            font-size: 1.35rem;
        }

        .muted {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            border: 1px solid var(--primary);
            color: #fff;
            background: var(--primary);
            border-radius: 10px;
            padding: 9px 12px;
            font-weight: 600;
            font-size: 0.92rem;
        }

        .btn:hover {
            background: var(--primary-dark);
        }

        .btn-outline {
            background: #fff;
            color: var(--primary);
        }

        .btn-outline:hover {
            background: #eff6ff;
        }

        .btn-export {
            border-color: #0f766e;
            background: #0d9488;
            color: #fff;
        }

        .btn-export:hover {
            background: #0f766e;
        }

        .btn-export-excel {
            border-color: #047857;
            background: #059669;
            color: #fff;
        }

        .btn-export-excel:hover {
            background: #047857;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin: 16px 0;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        label {
            font-size: 0.85rem;
            color: var(--muted);
            font-weight: 600;
        }

        select,
        input[type="date"] {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 9px 10px;
            font-size: 0.92rem;
            width: 100%;
            background: #fff;
        }

        .actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .button-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .table-wrap {
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 980px;
        }

        th,
        td {
            border-bottom: 1px solid var(--line);
            padding: 10px 12px;
            text-align: left;
            font-size: 0.92rem;
            vertical-align: top;
        }

        th {
            background: #eff6ff;
            font-size: 0.88rem;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .empty {
            padding: 20px 12px;
            color: var(--muted);
        }

        .error {
            margin-bottom: 8px;
            padding: 10px 12px;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #991b1b;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        @media (max-width: 900px) {
            .filters {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 640px) {
            .filters {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <div>
                <h1>Reward Report</h1>
                <p></p>
            </div>
            <a class="btn btn-outline" href="{{ url('/') }}">Back To Menu</a>
        </div>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="GET" action="{{ url('/reward-report') }}">
            <div class="filters">
                <div class="field">
                    <label for="member_id">Member</label>
                    <select id="member_id" name="member_id">
                        <option value="">All Members</option>
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}" @selected(($filters['member_id'] ?? null) == $member->id)>
                                {{ $member->name }} ({{ $member->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="start_date">Start Date</label>
                    <input id="start_date" type="date" name="start_date" value="{{ $filters['start_date'] ?? '' }}">
                </div>

                <div class="field">
                    <label for="end_date">End Date</label>
                    <input id="end_date" type="date" name="end_date" value="{{ $filters['end_date'] ?? '' }}">
                </div>

                <div class="field">
                    <label>&nbsp;</label>
                    <button class="btn" type="submit">Apply Filter</button>
                </div>
            </div>
        </form>

        <div class="actions">
            <div class="button-group">
                <a class="btn btn-outline" href="{{ url('/reward-report') }}">Reset</a>
                <a class="btn btn-export" href="{{ url('/reward-report/export?' . http_build_query(array_merge($filters, ['format' => 'csv']))) }}">
                    Export CSV
                </a>
                <a class="btn btn-export-excel" href="{{ url('/reward-report/export?' . http_build_query(array_merge($filters, ['format' => 'excel']))) }}">
                    Export Excel
                </a>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Achieved Date</th>
                        <th>Member Name</th>
                        <th>Promotion</th>
                        <th>Referral Count</th>
                        <th>Reward Value</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td>{{ optional($row->achieved_at)->format('Y-m-d') ?? '-' }}</td>
                            <td>{{ $row->member?->name ?? $row->member_name_snapshot ?? '-' }}</td>
                            <td>{{ $row->reward?->promotion?->name ?? '-' }}</td>
                            <td>{{ $row->reward?->referral_count ?? '-' }}</td>
                            <td>{{ isset($row->reward?->reward_value) ? 'USD ' . $row->reward->reward_value : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty" colspan="5">No rewards found for selected filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
