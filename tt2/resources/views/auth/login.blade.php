<!DOCTYPE html>
<html lang="lv" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — Neighbors Helping Neighbors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { display:flex; align-items:center; justify-content:center; min-height:100vh; background:var(--pg); }
        .auth-card { background:var(--surf); border:1px solid var(--border); border-radius:12px; padding:32px; width:100%; max-width:380px; }
        .auth-logo { font-family:'Lora',serif; font-size:20px; font-weight:600; color:var(--tx); text-align:center; margin-bottom:6px; }
        .auth-sub  { font-size:13px; color:var(--tx3); text-align:center; margin-bottom:24px; }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="auth-logo">Neighbors Helping Neighbors</div>
    <div class="auth-sub">Sign in to your account</div>

    @if($errors->any())
        <div style="background:var(--rose-lightest,#f5e8e4);border:1px solid var(--rose-deep,#b88070);border-radius:8px;padding:10px 14px;margin-bottom:16px;font-size:13px;color:var(--rose-text,#8c5848);">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}"
                   placeholder="you@example.com"
                   required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="••••••••"
                   required>
        </div>

        <div style="display:flex;align-items:center;gap:6px;margin-bottom:18px;">
            <input type="checkbox" id="remember" name="remember" style="width:auto;">
            <label for="remember" style="font-size:13px;color:var(--tx2);font-weight:400;cursor:pointer;margin:0;">Remember me</label>
        </div>

        <button type="submit" class="btn-offer" style="width:100%;padding:11px 0;">
            Sign in
        </button>

        <p style="text-align:center;font-size:13px;color:var(--tx3);margin-top:16px;">
            No account?
            <a href="{{ route('register') }}" style="color:var(--sage-mid);text-decoration:none;font-weight:500;">Register here</a>
        </p>
    </form>
</div>

</body>
</html>
