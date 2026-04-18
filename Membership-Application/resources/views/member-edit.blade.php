<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Member</title>
    <style>
        :root {
            --bg-top: #f1f5f9;
            --bg-bottom: #dbeafe;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --line: #d1d5db;
            --primary: #1d4ed8;
            --primary-dark: #1e40af;
            --secondary: #ffffff;
            --danger: #dc2626;
            --success: #166534;
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: min(100%, 700px);
            background: var(--card);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.14);
        }

        h1 {
            margin: 0 0 8px;
            font-size: 1.5rem;
        }

        p {
            margin: 0 0 20px;
            color: var(--muted);
        }

        .alert-success {
            margin: 0 0 14px;
            color: var(--success);
            font-weight: 600;
        }

        .alert-error {
            margin: 0 0 14px;
            color: var(--danger);
            font-weight: 600;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 0.9rem;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.95rem;
            background: #fff;
            color: var(--text);
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(29, 78, 216, 0.14);
        }

        input[readonly] {
            background: #f8fafc;
            color: #475569;
        }

        .actions {
            margin-top: 20px;
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
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
        }

        .btn-secondary {
            background: var(--secondary);
            color: var(--primary);
        }

        .btn-secondary:hover {
            background: #eff6ff;
        }

        @media (max-width: 640px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Edit Member Information</h1>
        <p></p>

        @if (session('success'))
            <p class="alert-success">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <p class="alert-error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ url('/member-list/' . $member->id) }}">
            @csrf
            @method('PUT')

            <div class="grid">
                <div class="field full">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $member->name) }}" required>
                </div>

                <div class="field full">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $member->email) }}" required>
                </div>

                <div class="field">
                    <label for="phone">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $member->phone) }}" required>
                </div>

                <div class="field">
                    <label for="dob">Date of Birth</label>
                    <input id="dob" type="date" name="dob" value="{{ old('dob', $member->dob) }}" required>
                </div>

                <div class="field">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="male" {{ old('gender', $member->gender) === 'male' || old('gender', $member->gender) === 'M' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $member->gender) === 'female' || old('gender', $member->gender) === 'F' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="field">
                    <label for="referral_code">Referral Code</label>
                    <input id="referral_code" type="text" value="{{ $member->referral_code }}" readonly>
                </div>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Update Member</button>
                <a class="btn btn-secondary" href="{{ url('/member-list') }}">Back To Member List</a>
            </div>
        </form>
    </div>
</body>
</html>
