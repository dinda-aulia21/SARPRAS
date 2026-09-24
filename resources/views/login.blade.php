<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Login Sistem Informasi Inventaris Yayasan Raudhah Syarifah.">
        <title>Login | Yayasan Raudhah Syarifah</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <main class="login-page">
            <section class="login-visual" aria-label="Informasi yayasan">
                <div class="login-copy">
                    <small>Selamat Datang di</small>
                    <h1>Sistem Informasi Inventaris<br>Sarana dan Prasarana<br><span>Yayasan Raudhah Syarifah</span></h1>
                    <p>Kelola data inventaris sarana dan prasarana dengan lebih mudah, cepat, dan terintegrasi.</p>
                </div>
            </section>

            <section class="login-panel" aria-labelledby="login-title">
                <div class="login-box">
                    <div class="login-logo">
                        <span class="brand-mark" aria-hidden="true">R</span>
                        <span>Yayasan Raudhah Syarifah</span>
                    </div>
                    <h2 id="login-title">Login</h2>
                    <p class="login-subtitle">Silakan masuk dengan akun Anda</p>
                    @if ($errors->any())
                        <p class="login-error">{{ $errors->first() }}</p>
                    @endif
                    <form class="login-form" action="{{ route('login.authenticate') }}" method="post">
                        @csrf
                        <label for="username">Username</label>
                        <div class="input-wrap">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 21a8 8 0 0 0-16 0M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg>
                            <input id="username" name="email" type="email" autocomplete="username" placeholder="Email" value="{{ old('email') }}" required>
                        </div>
                        <label for="password">Password</label>
                        <div class="input-wrap">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                            <input id="password" name="password" type="password" autocomplete="current-password" placeholder="Password" required>
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2"/></svg>
                        </div>
                        <button class="login-submit" type="submit">Login <span aria-hidden="true">→</span></button>
                    </form>
                    <a class="back-home" href="{{ url('/') }}">Kembali ke Beranda</a>
                </div>
                <p class="login-copyright">© 2026 Yayasan Raudhah Syarifah. All rights reserved.</p>
            </section>
        </main>
    </body>
</html>
