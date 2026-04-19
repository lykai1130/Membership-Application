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

        .btn-clear {
            border-color: #9ca3af;
            background: #fff;
            color: #374151;
        }

        .btn-clear:hover {
            background: #f3f4f6;
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

        .btn-danger {
            border-color: #b91c1c;
            background: #dc2626;
            color: #fff;
            cursor: pointer;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-small {
            padding: 6px 10px;
            font-size: 0.82rem;
            border-radius: 8px;
        }

        .btn-disabled {
            border-color: #d1d5db;
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
            pointer-events: none;
        }

        .filters {
            margin: 0 0 14px;
            padding: 12px;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            background: #f8fbff;
        }

        .search-row {
            margin-bottom: 10px;
        }

        .search-input {
            width: min(560px, 100%);
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 0.9rem;
        }

        .filter-actions {
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

        .action-cell {
            width: 120px;
        }

        .action-form {
            display: inline-block;
            margin: 0;
        }

        .empty {
            padding: 20px 12px;
            color: var(--muted);
        }

        .flash-success {
            margin: 0 0 14px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #86efac;
            background: #f0fdf4;
            color: #166534;
            font-size: 0.9rem;
        }

        .pagination-wrap {
            margin-top: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .pagination-summary {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .pagination-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .page-indicator {
            font-size: 0.88rem;
            color: #374151;
            font-weight: 600;
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

        @if (session('success'))
            <div class="flash-success">{{ session('success') }}</div>
        @endif

        <form class="filters" method="GET" action="{{ url('/member-list') }}">
            <div class="search-row">
                <input id="search" name="search" class="search-input" type="text" value="{{ $search ?? '' }}"
                    placeholder="Search member name, email, or referral code">
            </div>
            <div class="filter-actions">
                <button class="btn btn-small" type="submit">Search</button>
                <button class="btn btn-small btn-export" type="submit" formaction="{{ url('/member-list/export') }}"
                    formmethod="GET" name="format" value="csv">Export CSV</button>
                <button class="btn btn-small btn-export-excel" type="submit"
                    formaction="{{ url('/member-list/export') }}" formmethod="GET" name="format"
                    value="excel">Export Excel</button>
                <a class="btn btn-small btn-clear" href="{{ url('/member-list') }}">Clear</a>
            </div>
        </form>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Referral Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr data-href="{{ url('/member-list/' . $member->id . '/edit') }}">
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>{{ $member->referral_code }}</td>
                            <td class="action-cell">
                                <form class="action-form" method="POST" action="{{ url('/member-list/' . $member->id) }}"
                                    onsubmit="return confirm('Remove this member? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-small">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty" colspan="4">No members found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($members->hasPages())
            <div class="pagination-wrap">
                <div class="pagination-summary">
                    Showing {{ $members->firstItem() }} to {{ $members->lastItem() }} of {{ $members->total() }} members
                </div>
                <div class="pagination-actions">
                    @if ($members->onFirstPage())
                        <span class="btn btn-small btn-disabled">Previous</span>
                    @else
                        <a class="btn btn-small" href="{{ $members->previousPageUrl() }}">Previous</a>
                    @endif

                    <span class="page-indicator">Page {{ $members->currentPage() }} of {{ $members->lastPage() }}</span>

                    @if ($members->hasMorePages())
                        <a class="btn btn-small" href="{{ $members->nextPageUrl() }}">Next</a>
                    @else
                        <span class="btn btn-small btn-disabled">Next</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
    <script>
        document.querySelectorAll('tbody tr[data-href]').forEach((row) => {
            row.addEventListener('click', (event) => {
                if (event.target.closest('form, button, a, input, select, textarea, label')) {
                    return;
                }

                window.location.href = row.dataset.href;
            });
        });
    </script>
</body>

</html>
