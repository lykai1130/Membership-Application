<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Promotion Setup</title>
    <style>
        :root {
            --bg: #eef4ff;
            --card: #ffffff;
            --text: #111827;
            --muted: #6b7280;
            --line: #d1d5db;
            --primary: #1d4ed8;
            --primary-dark: #1e40af;
            --danger-bg: #fef2f2;
            --danger-text: #991b1b;
            --danger-line: #fecaca;
            --success-bg: #f0fdf4;
            --success-text: #166534;
            --success-line: #86efac;
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
            width: min(780px, 100%);
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
            cursor: pointer;
        }

        .btn:hover {
            background: var(--primary-dark);
        }

        .notice {
            margin: 0 0 14px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 0.9rem;
        }

        .notice-success {
            border: 1px solid var(--success-line);
            background: var(--success-bg);
            color: var(--success-text);
        }

        .notice-error {
            border: 1px solid var(--danger-line);
            background: var(--danger-bg);
            color: var(--danger-text);
        }

        .form-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
        }

        .grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(2, minmax(180px, 1fr));
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field-wide {
            grid-column: 1 / -1;
        }

        .field label {
            font-size: 0.84rem;
            color: #374151;
            font-weight: 600;
        }

        .field input,
        .field select {
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 0.92rem;
        }

        .readonly {
            background: #f9fafb;
            color: #4b5563;
        }

        .actions {
            margin-top: 14px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .empty {
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 20px 14px;
            color: var(--muted);
            text-align: center;
        }

        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <div>
                <h1>Promotion Setup</h1>
                <p></p>
            </div>
            <a class="btn" href="{{ url('/') }}">Back To Menu</a>
        </div>

        @if (session('success'))
            <div class="notice notice-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="notice notice-error">{{ $errors->first() }}</div>
        @endif

        @if ($promotion)
            <form class="form-card" method="POST" action="{{ url('/promotion/' . $promotion->id) }}">
                @csrf
                @method('PUT')

                <div class="grid">
                    <div class="field field-wide">
                        <label>Promotion Name</label>
                        <input class="readonly" type="text" value="{{ $promotion->name }}" readonly>
                    </div>

                    <div class="field">
                        <label for="start_date">Start Date</label>
                        <input id="start_date" name="start_date" type="date"
                            value="{{ old('start_date', optional($promotion->start_date)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="field">
                        <label for="end_date">End Date</label>
                        <input id="end_date" name="end_date" type="date"
                            value="{{ old('end_date', optional($promotion->end_date)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="field field-wide">
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
                            <option value="A" {{ old('status', $promotion->status) === 'A' ? 'selected' : '' }}>Active
                            </option>
                            <option value="I" {{ old('status', $promotion->status) === 'I' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>
                </div>

                <div class="actions">
                    <button class="btn" type="submit">Save Promotion</button>
                </div>
            </form>
        @else
            <div class="empty">No promotion is available to edit.</div>
        @endif
    </div>
</body>

</html>
