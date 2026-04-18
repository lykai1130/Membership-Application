<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member Registration</title>
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

        input.is-invalid,
        select.is-invalid {
            border-color: var(--danger);
        }

        input.is-invalid:focus,
        select.is-invalid:focus {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.14);
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

        .btn-primary:disabled {
            background: #93c5fd;
            border-color: #93c5fd;
            cursor: not-allowed;
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
        <h1>Member Registration</h1>
        <p></p>

        @if (session('success'))
            <p style="margin:0 0 14px;color:#166534;font-weight:600;">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <p style="margin:0 0 14px;color:#b91c1c;font-weight:600;">{{ $errors->first() }}</p>
        @endif

        <form id="registration-form" method="POST" action="{{ url('/registration') }}">
            @csrf

            <div class="grid">
                <div class="field full">
                    <label for="name">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required>
                </div>

                <div class="field full">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="field">
                    <label for="phone">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required>
                </div>

                <div class="field">
                    <label for="dob">Date of Birth</label>
                    <input id="dob" type="date" name="dob" value="{{ old('dob') }}" required>
                </div>

                <div class="field">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="field">
                    <label for="referral_code">Referral Code (optional)</label>
                    <input id="referral_code" type="text" name="referral_code" maxlength="6" value="{{ old('referral_code') }}">
                </div>

            </div>

            <div class="actions">
                <button id="register-btn" class="btn btn-primary" type="submit">Register</button>
                <a class="btn btn-secondary" href="{{ url('/') }}">Back To Menu</a>
            </div>
        </form>
    </div>
    <script>
        const form = document.getElementById('registration-form');
        const registerButton = document.getElementById('register-btn');
        const requiredFields = [
            document.getElementById('name'),
            document.getElementById('email'),
            document.getElementById('phone'),
            document.getElementById('dob'),
            document.getElementById('gender')
        ];

        function isEmpty(field) {
            return field.value.trim() === '';
        }

        function validateField(field) {
            field.classList.toggle('is-invalid', isEmpty(field));
        }

        function updateRegisterButtonState() {
            const hasEmptyRequired = requiredFields.some(isEmpty);
            registerButton.disabled = hasEmptyRequired;
        }

        requiredFields.forEach((field) => {
            const eventName = field.tagName === 'SELECT' ? 'change' : 'input';
            field.addEventListener(eventName, () => {
                validateField(field);
                updateRegisterButtonState();
            });
            field.addEventListener('blur', () => validateField(field));
        });

        form.addEventListener('submit', (event) => {
            requiredFields.forEach(validateField);
            updateRegisterButtonState();

            if (registerButton.disabled) {
                event.preventDefault();
            }
        });

        updateRegisterButtonState();
    </script>
</body>
</html>
