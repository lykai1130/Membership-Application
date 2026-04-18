<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Membership Menu</title>
    <style>
        :root {
            --bg: #f4f6f8;
            --card: #ffffff;
            --primary: #1565c0;
            --primary-dark: #0d47a1;
            --text: #1f2937;
            --muted: #6b7280;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(140deg, #eef2f7 0%, #d9e6f7 100%);
            color: var(--text);
        }

        .menu-card {
            width: min(92vw, 460px);
            background: var(--card);
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 18px 40px rgba(17, 24, 39, 0.15);
        }

        .menu-title {
            margin: 0 0 6px;
            font-size: 1.4rem;
            font-weight: 700;
        }

        .menu-subtitle {
            margin: 0 0 20px;
            font-size: 0.95rem;
            color: var(--muted);
        }

        .button-grid {
            display: grid;
            gap: 12px;
        }

        .menu-button {
            display: inline-block;
            width: 100%;
            border: none;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            color: #fff;
            background: var(--primary);
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .menu-button:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .menu-button:active {
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <div class="menu-card">
        <h1 class="menu-title">Membership Application</h1>
        <p class="menu-subtitle"></p>

        <div class="button-grid">
            <a class="menu-button" href="{{ url('/registration') }}">Registration</a>
            <a class="menu-button" href="{{ url('/member-list') }}">Member List</a>
            <a class="menu-button" href="{{ url('/reward-report') }}">Reward Report</a>
        </div>
    </div>
</body>

</html>
