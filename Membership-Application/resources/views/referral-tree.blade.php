<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Referral Tree</title>
    <style>
        :root {
            --bg-top: #f8fafc;
            --bg-bottom: #dbeafe;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --line: #d1d5db;
            --primary: #1d4ed8;
            --primary-dark: #1e40af;
            --secondary: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(140deg, var(--bg-top), var(--bg-bottom));
            color: var(--text);
            padding: 24px;
        }

        .card {
            width: min(1000px, 100%);
            margin: 0 auto;
            background: var(--card);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        h1 {
            margin: 0 0 6px;
            font-size: 1.4rem;
        }

        p {
            margin: 0;
            color: var(--muted);
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border: 1px solid var(--primary);
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            background: var(--secondary);
            color: var(--primary);
        }

        .btn:hover {
            background: #eff6ff;
        }

        .table-wrap {
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
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
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div>
                <h1>Referral Tree</h1>
                <p>Current member: <strong>{{ $member->name }}</strong> (ID: {{ $member->id }})</p>
            </div>
            <div class="actions">
                <a class="btn" href="{{ url('/member-list/' . $member->id . '/edit') }}">Back To Member</a>
                <a class="btn" href="{{ url('/member-list') }}">Back To List</a>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Referral Code</th>
                        <th>Referred By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($treeRows as $row)
                        <tr>
                            <td>Level {{ $row['level'] }}</td>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['email'] }}</td>
                            <td>{{ $row['referral_code'] }}</td>
                            <td>{{ $row['referred_by'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty" colspan="5">No referral hierarchy found for this member.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
