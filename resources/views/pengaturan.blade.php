<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Pengaturan profil dan keamanan akun admin.">
        <title>Pengaturan | Sarpras</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-body">
        <div class="admin-layout">
            <aside class="admin-sidebar" id="admin-sidebar">
                <a class="admin-brand" href="{{ auth()->user()->role === 'kepala_yayasan' ? route('kepala-yayasan.dashboard') : (auth()->user()->role === 'kepala_sekolah' ? route('kepala-sekolah.dashboard') : route('admin.dashboard')) }}"><span class="brand-mark" aria-hidden="true">R</span><span>Yayasan Raudhah<br>Syarifah</span></a>
                <nav class="admin-nav" aria-label="Navigasi admin">
                    <a class="admin-nav-link" href="{{ auth()->user()->role === 'kepala_yayasan' ? route('kepala-yayasan.dashboard') : (auth()->user()->role === 'kepala_sekolah' ? route('kepala-sekolah.dashboard') : route('admin.dashboard')) }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 13 8-8 8 8v7H4z"/><path d="M9 20v-5h6v5"/></svg>Dashboard</a>
                    <a class="admin-nav-link" href="{{ route('admin.inventaris') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v13H4zM4 7l2-4h12l2 4M9 11h6M8 3v4M16 3v4"/></svg>Data Inventaris</a>
                    <a class="admin-nav-link" href="{{ route('admin.laporan') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3h14v18H5zM8 7h8M8 11h8M8 15h5"/></svg>Laporan</a>
                    <a class="admin-nav-link" href="{{ route('admin.pengumuman') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 11 18-5v12L3 13v-2Z"/><path d="m7 14 2 6h4l-3-7M21 10v4"/></svg>Pengumuman</a>
                    <a class="admin-nav-link active" href="{{ route('admin.pengaturan') }}" aria-current="page"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/><path d="m19 13 2-1-2-1-.4-1.1 1-1.8-2.7-2.7-1.8 1L12 6l-1-2-1 2-1.1.4-1.8-1L5.4 6.1l1 1.8L6 9l-2 1 2 1 .4 1.1-1 1.8 2.7 2.7 1.8-1L11 16l1 2 1-2 1.1-.4 1.8 1 2.7-2.7-1-1.8z"/></svg>Pengaturan</a>
                </nav>
                <div class="admin-sidebar-footer">
                    <div class="admin-profile"><span class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span><span><strong>{{ $user->name }}</strong><small>Administrator</small></span></div>
                    <form class="admin-logout-form" action="{{ route('logout') }}" method="post">@csrf<button class="admin-logout" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3M19 3h2v18h-2"/></svg>Keluar</button></form>
                </div>
            </aside>
            <main class="admin-main">
                <header class="admin-topbar">
                    <button class="sidebar-toggle" type="button" aria-controls="admin-sidebar" aria-expanded="false" aria-label="Buka navigasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
                    <div class="admin-search"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/></svg><input type="search" placeholder="Cari inventaris, kategori, atau lokasi..." aria-label="Cari inventaris"></div>
                    <div class="topbar-actions"><button class="icon-button notification-button" type="button" aria-label="Notifikasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><span></span></button><div class="date-label"><strong>{{ now()->translatedFormat('l, d F Y') }}</strong><small>{{ now()->format('H:i') }} WIB</small></div><button class="account-button" type="button"><span class="mini-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span><span>{{ $user->name }}</span><span class="chevron">⌄</span></button></div>
                </header>
                <div class="admin-content inventory-page-content settings-page-content">
                    <div class="page-heading"><div><p class="welcome-eyebrow">Akun dan keamanan</p><h1>Pengaturan</h1><p>Kelola profil akun dan keamanan sistem untuk menjaga keamanan data inventaris.</p></div></div>
                    @if (session('profile_success'))<div class="inventory-alert" role="status">{{ session('profile_success') }}</div>@endif
                    @if (session('password_success'))<div class="inventory-alert" role="status">{{ session('password_success') }}</div>@endif
                    @if ($errors->any())<div class="inventory-alert inventory-alert-error" role="alert">{{ $errors->first() }}</div>@endif
                    <div class="settings-layout">
                        <nav class="settings-nav" aria-label="Bagian pengaturan">
                            <a class="settings-nav-link is-active" href="#profil"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>Profil Admin</a>
                            <a class="settings-nav-link" href="#keamanan"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>Keamanan</a>
                        </nav>
                        <div class="settings-panels">
                            <section class="settings-panel" id="profil" aria-labelledby="profile-title">
                                <div class="settings-panel-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/></svg><span aria-hidden="true">✓</span></div>
                                <div class="settings-panel-content">
                                    <div class="settings-panel-heading"><h2 id="profile-title">Profil Admin</h2><p>Kelola informasi akun yang terdaftar di sistem.</p></div>
                                    <form class="settings-form" action="{{ route('admin.pengaturan.profil') }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="settings-form-grid">
                                            <label for="profile-name">Nama Lengkap<input id="profile-name" name="name" type="text" value="{{ old('name', $user->name) }}" autocomplete="name" required>@error('name')<small class="field-error">{{ $message }}</small>@enderror</label>
                                            <label for="profile-email">Email<input id="profile-email" name="email" type="email" value="{{ old('email', $user->email) }}" autocomplete="email" required>@error('email')<small class="field-error">{{ $message }}</small>@enderror</label>
                                            <label for="profile-role">Role<input id="profile-role" type="text" value="Administrator" disabled></label>
                                        </div>
                                        <button class="settings-submit-button" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h12l3 3v13H4V4zM8 4v6h8V4M8 20v-6h8v6"/></svg>Simpan Perubahan</button>
                                    </form>
                                </div>
                            </section>
                            <section class="settings-panel" id="keamanan" aria-labelledby="security-title">
                                <div class="settings-panel-icon settings-security-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3M12 14v3"/></svg></div>
                                <div class="settings-panel-content">
                                    <div class="settings-panel-heading"><h2 id="security-title">Ubah Password</h2><p>Gunakan password yang kuat untuk menjaga keamanan akun Anda.</p></div>
                                    <form class="settings-form settings-password-form" action="{{ route('admin.pengaturan.password') }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <label for="current-password">Password Lama<div class="password-input-wrap"><input id="current-password" name="current_password" type="password" autocomplete="current-password" placeholder="Masukkan password lama" required><button type="button" data-toggle-password="current-password" aria-label="Tampilkan password lama"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2"/></svg></button></div>@error('current_password')<small class="field-error">{{ $message }}</small>@enderror</label>
                                        <label for="new-password">Password Baru<div class="password-input-wrap"><input id="new-password" name="password" type="password" autocomplete="new-password" placeholder="Masukkan password baru" required><button type="button" data-toggle-password="new-password" aria-label="Tampilkan password baru"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2"/></svg></button></div>@error('password')<small class="field-error">{{ $message }}</small>@enderror</label>
                                        <label for="password-confirmation">Konfirmasi Password Baru<div class="password-input-wrap"><input id="password-confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Konfirmasi password baru" required><button type="button" data-toggle-password="password-confirmation" aria-label="Tampilkan konfirmasi password"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2"/></svg></button></div></label>
                                        <button class="settings-submit-button" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>Simpan Password</button>
                                    </form>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        <script>
            const toggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.querySelector('.admin-sidebar');
            toggle?.addEventListener('click', () => {
                const isOpen = sidebar.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', String(isOpen));
            });
            document.querySelectorAll('[data-toggle-password]').forEach((button) => {
                button.addEventListener('click', () => {
                    const input = document.getElementById(button.dataset.togglePassword);
                    const showPassword = input.type === 'password';
                    input.type = showPassword ? 'text' : 'password';
                    button.setAttribute('aria-label', `${showPassword ? 'Sembunyikan' : 'Tampilkan'} ${input.labels[0].textContent.trim()}`);
                });
            });
        </script>
    </body>
</html>