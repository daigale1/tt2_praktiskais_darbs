<!DOCTYPE html>
<html lang="lv" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — Neighbors Helping Neighbors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:var(--pg); padding:24px; }
        .auth-card { background:var(--surf); border:1px solid var(--border); border-radius:12px; padding:32px; width:100%; max-width:400px; }
        .auth-logo { font-family:'Lora',serif; font-size:20px; font-weight:600; color:var(--tx); text-align:center; margin-bottom:6px; }
        .auth-sub  { font-size:13px; color:var(--tx3); text-align:center; margin-bottom:24px; }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="auth-logo">Neighbors Helping Neighbors</div>
    <div class="auth-sub">Create your account</div>

    @if($errors->any())
        <div style="background:var(--rose-lightest,#f5e8e4);border:1px solid var(--rose-deep,#b88070);border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:13px;color:var(--rose-text,#8c5848);">
            <ul style="margin:0;padding-left:16px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Full name</label>
            <input type="text" id="name" name="name"
                   value="{{ old('name') }}"
                   placeholder="e.g. Māris Kalniņš"
                   required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="you@example.com"
                   required>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            <div class="form-group">
                <label for="location">
                    Location
                    <span style="font-size:11px;color:var(--tx3);font-weight:400;">(optional)</span>
                </label>
                <input type="text" id="location" name="location"
                       value="{{ old('location') }}"
                       placeholder="e.g. Rīgas centrs">
            </div>
            <div class="form-group">
                <label for="age">
                    Age
                    <span style="font-size:11px;color:var(--tx3);font-weight:400;">(optional)</span>
                </label>
                <input type="number" id="age" name="age"
                       value="{{ old('age') }}"
                       placeholder="e.g. 28"
                       min="16" max="120">
            </div>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="Min. 8 characters"
                   required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   placeholder="Repeat password"
                   required>
        </div>

        <button type="submit" class="btn-offer" style="width:100%;padding:11px 0;margin-top:4px;">
            Create account
        </button>

        <p style="text-align:center;font-size:13px;color:var(--tx3);margin-top:16px;">
            Already have an account?
            <a href="{{ route('login') }}" style="color:var(--sage-mid);text-decoration:none;font-weight:500;">Sign in</a>
        </p>
    </form>
</div>

</body>
</html>
