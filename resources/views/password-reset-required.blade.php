<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Ganti Password | Sarpras</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="password-reset-page">
        <main class="password-reset-box">
                <div class="login-brand">
                    <img class="brand-mark" src="{{ asset('images/logo-yayasan.png') }}" alt="">
                    <span>Yayasan Raudhah Syarifah</span>
                </div>
                <h1 id="required-password-title">Buat Password Baru</h1>
                <p class="login-subtitle">Password akun Anda telah direset oleh admin. Silakan buat password pribadi sebelum melanjutkan.</p>
                @if ($errors->any())
                    <p class="login-error" role="alert">{{ $errors->first() }}</p>
                @endif
                <form class="login-form" action="{{ route('password.force-update') }}" method="post">
                    @csrf
                    <label for="new-password">Password Baru</label>
                    <div class="input-wrap">
                        <input id="new-password" name="password" type="password" autocomplete="new-password" required>
                        <button class="password-visibility-toggle" type="button" data-password-visibility="new-password" aria-label="Tampilkan password" aria-pressed="false">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2"/><path class="password-eye-slash" d="m4 4 16 16"/></svg>
                        </button>
                    </div>
                    <label for="password-confirmation">Konfirmasi Password Baru</label>
                    <div class="input-wrap">
                        <input id="password-confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
                    </div>
                    <button class="login-submit" type="submit">Simpan Password Baru</button>
                </form>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="back-home" type="submit">Keluar</button>
                </form>
        </main>
    </body>
</html>
