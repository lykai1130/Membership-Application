<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member List</title>
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
            width: min(1100px, 100%);
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

        .table-wrap {
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
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

        tbody tr {
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        tbody tr:hover {
            background: #f8fbff;
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
    <div class="wrapper">
        <div class="header">
            <div>
                <h1>Member List</h1>
                <p class="muted">Click a row to view and edit member details.</p>
            </div>
            <a class="btn" href="{{ url('/') }}">Back To Menu</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Referral Code</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr data-href="{{ url('/member-list/' . $member->id . '/edit') }}">
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->referral_code }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty" colspan="3">No members found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.querySelectorAll('tbody tr[data-href]').forEach((row) => {
            row.addEventListener('click', () => {
                window.location.href = row.dataset.href;
            });
        });
    </script>
</body>

</html>
