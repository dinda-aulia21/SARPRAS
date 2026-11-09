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
        @php
            $roleLabel = match ($user->role) {
                'admin' => 'Admin',
                'kepala_yayasan' => 'Kepala Yayasan',
                'kepala_sekolah' => 'Kepala Sekolah',
                default => 'Pengguna',
            };
        @endphp
        <div class="admin-layout">
            <aside class="admin-sidebar" id="admin-sidebar">
                <a class="admin-brand" href="{{ auth()->user()->role === 'kepala_yayasan' ? route('kepala-yayasan.dashboard') : (auth()->user()->role === 'kepala_sekolah' ? route('kepala-sekolah.dashboard') : route('admin.dashboard')) }}"><img class="brand-mark" src="{{ asset('images/logo-yayasan.png') }}" alt=""><span>Yayasan Raudhah<br>Syarifah</span></a>
                <nav class="admin-nav" aria-label="Navigasi admin">
                    <a class="admin-nav-link" href="{{ auth()->user()->role === 'kepala_yayasan' ? route('kepala-yayasan.dashboard') : (auth()->user()->role === 'kepala_sekolah' ? route('kepala-sekolah.dashboard') : route('admin.dashboard')) }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 13 8-8 8 8v7H4z"/><path d="M9 20v-5h6v5"/></svg>Dashboard</a>
                    <a class="admin-nav-link" href="{{ route('admin.inventaris') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v13H4zM4 7l2-4h12l2 4M9 11h6M8 3v4M16 3v4"/></svg>Data Inventaris</a>
                    <a class="admin-nav-link" href="{{ route('admin.laporan') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3h14v18H5zM8 7h8M8 11h8M8 15h5"/></svg>Laporan</a>
                    <a class="admin-nav-link" href="{{ route('admin.pengumuman') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 11 18-5v12L3 13v-2Z"/><path d="m7 14 2 6h4l-3-7M21 10v4"/></svg>Pengumuman</a>
                    <a class="admin-nav-link active" href="{{ route('admin.pengaturan') }}" aria-current="page"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/><circle cx="9" cy="6" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="8" cy="18" r="2"/></svg>Pengaturan</a>
                </nav>
                <div class="admin-sidebar-footer">
                    <div class="admin-profile"><span class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span><span><strong>{{ $user->name }}</strong><small>{{ $roleLabel }}</small></span></div>
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
                    @if (session('account_success'))<div class="inventory-alert" role="status">{{ session('account_success') }}</div>@endif
                    @if (session('temporary_password_reset'))
                        <div class="inventory-alert password-reset-result" role="status">
                            Password sementara untuk <strong>{{ session('temporary_password_reset.email') }}</strong> (tampilkan sekali). Sampaikan secara pribadi kepada pengguna dan minta segera diganti:
                            <code>{{ session('temporary_password_reset.password') }}</code>
                        </div>
                    @endif
                    @if ($errors->any())<div class="inventory-alert inventory-alert-error" role="alert">{{ $errors->first() }}</div>@endif
                    <div class="settings-layout">
                        <nav class="settings-nav" aria-label="Bagian pengaturan">
                            <a class="settings-nav-link is-active" href="#profil"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/></svg>Profil {{ $roleLabel }}</a>
                            <a class="settings-nav-link" href="#keamanan"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>Keamanan</a>
                        </nav>
                        <div class="settings-panels">
                            <section class="settings-panel" id="profil" aria-labelledby="profile-title">
                                <div class="settings-panel-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20a7 7 0 0 1 14 0"/></svg><span aria-hidden="true">✓</span></div>
                                <div class="settings-panel-content">
                                    <div class="settings-panel-heading"><h2 id="profile-title">Profil {{ $roleLabel }}</h2><p>Kelola informasi akun yang terdaftar di sistem.</p></div>
                                    <form class="settings-form" action="{{ route('admin.pengaturan.profil') }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="settings-form-grid">
                                            <label for="profile-name">Nama Lengkap<input id="profile-name" name="name" type="text" value="{{ old('name', $user->name) }}" autocomplete="name" required>@error('name')<small class="field-error">{{ $message }}</small>@enderror</label>
                                            <label for="profile-email">Email<input id="profile-email" name="email" type="email" value="{{ old('email', $user->email) }}" autocomplete="email" required>@error('email')<small class="field-error">{{ $message }}</small>@enderror</label>
                                            <label for="profile-role">Role<input id="profile-role" type="text" value="{{ $roleLabel === 'Admin' ? 'Administrator' : $roleLabel }}" disabled></label>
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
                                        <label for="current-password">Password Lama<div class="password-input-wrap"><input id="current-password" name="current_password" type="password" autocomplete="current-password" placeholder="Masukkan password lama" required><button type="button" data-toggle-password="current-password" aria-label="Tampilkan password lama" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2"/><path class="password-eye-slash" d="m4 4 16 16"/></svg></button></div>@error('current_password')<small class="field-error">{{ $message }}</small>@enderror</label>
                                        <label for="new-password">Password Baru<div class="password-input-wrap"><input id="new-password" name="password" type="password" autocomplete="new-password" placeholder="Masukkan password baru" required><button type="button" data-toggle-password="new-password" aria-label="Tampilkan password baru" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2"/><path class="password-eye-slash" d="m4 4 16 16"/></svg></button></div>@error('password')<small class="field-error">{{ $message }}</small>@enderror</label>
                                        <label for="password-confirmation">Konfirmasi Password Baru<div class="password-input-wrap"><input id="password-confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Konfirmasi password baru" required><button type="button" data-toggle-password="password-confirmation" aria-label="Tampilkan konfirmasi password" aria-pressed="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2"/><path class="password-eye-slash" d="m4 4 16 16"/></svg></button></div></label>
                                        <button class="settings-submit-button" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>Simpan Password</button>
                                    </form>
                                </div>
                            </section>
                        </div>
                    </div>
                    @if ($user->role === 'admin')
                        <section class="settings-panel account-monitor-panel" id="pemantauan-akun" aria-labelledby="account-monitor-title">
                            <div class="settings-panel-icon settings-security-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3 20a6 6 0 0 1 12 0M16 11h5M18.5 8.5v5"/></svg></div>
                            <div class="settings-panel-content">
                                <div class="settings-panel-heading">
                                    <h2 id="account-monitor-title">Pengelolaan Akun</h2>
                                    <p>Kelola tiga akun resmi: Admin, Kepala Yayasan, dan Kepala Sekolah. Password asli tidak disimpan atau ditampilkan.</p>
                                </div>
                                <div class="account-monitor-table-wrap">
                                    <table class="account-monitor-table">
                                        <thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Unit</th><th>Status</th><th>Password terakhir diubah</th><th>Aksi</th></tr></thead>
                                        <tbody>
                                            @forelse ($accounts as $account)
                                                <tr>
                                                    <td>{{ $account->name }}</td>
                                                    <td>{{ $account->email }}</td>
                                                    <td>{{ $account->hakAkses?->nama_hak_akses ?? 'Tidak diketahui' }}</td>
                                                    <td>{{ $account->unitPendidikan?->nama_unit ?? '—' }}</td>
                                                    <td><span class="account-status {{ $account->status !== 'nonaktif' ? 'is-active' : 'is-inactive' }}">{{ $account->status === 'nonaktif' ? 'Nonaktif' : 'Aktif' }}</span></td>
                                                    <td>{{ $latestPasswordChanges->get($account->id)?->changed_at?->translatedFormat('d M Y, H:i') ?? 'Belum tercatat' }}</td>
                                                    <td class="account-actions-cell">
                                                        <details class="account-actions-menu">
                                                            <summary class="account-actions-toggle" aria-label="Buka aksi akun {{ $account->name }}" title="Aksi akun">
                                                                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="5" cy="12" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/></svg>
                                                            </summary>
                                                            <div class="account-actions-menu-items">
                                                                <details class="account-edit-details">
                                                                    <summary class="account-action-item">Edit</summary>
                                                                    <form class="account-edit-form" action="{{ route('admin.pengguna.update', $account) }}" method="post">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <label>Nama lengkap<input name="name" type="text" value="{{ $account->name }}" maxlength="255" required></label>
                                                                        <label>Email<input name="email" type="email" value="{{ $account->email }}" maxlength="255" required></label>
                                                                        <label>Peran<select name="role" required @disabled($account->is($user))>@foreach ($roles as $role)<option value="{{ $role->role_key }}" @selected($account->role === $role->role_key)>{{ $role->nama_hak_akses }}</option>@endforeach</select>@if ($account->is($user))<input type="hidden" name="role" value="{{ $account->role }}">@endif</label>
                                                                        <label>Unit pendidikan<select name="unit_pendidikan_id"><option value="">Tidak ada unit</option>@foreach ($educationUnits as $unit)<option value="{{ $unit->id }}" @selected($account->unit_pendidikan_id === $unit->id)>{{ $unit->nama_unit }}</option>@endforeach</select></label>
                                                                        <button class="account-action-item" type="submit">Simpan perubahan</button>
                                                                    </form>
                                                                </details>
                                                                <form action="{{ route('admin.pengguna.password-reset', $account) }}" method="post" onsubmit="return confirm('Buat password sementara untuk akun {{ $account->email }}?')">
                                                                    @csrf
                                                                    <button class="account-action-item" type="submit" @disabled($account->status === 'nonaktif')>Reset password</button>
                                                                </form>
                                                                <form action="{{ route('admin.pengguna.status', $account) }}" method="post">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="hidden" name="status" value="{{ $account->status !== 'nonaktif' ? 'nonaktif' : 'aktif' }}">
                                                                    <button class="account-action-item" type="submit" @disabled($account->is($user) || ($account->role === 'admin' && $account->status !== 'nonaktif' && $activeAdminCount <= 1))>{{ $account->status !== 'nonaktif' ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                                                </form>
                                                                <form action="{{ route('admin.pengguna.destroy', $account) }}" method="post" onsubmit="return confirm('Hapus permanen akun {{ $account->email }}? Tindakan ini tidak dapat dibatalkan.')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="account-action-item is-danger" type="submit" @disabled($account->is($user) || ($account->role === 'admin' && $account->status !== 'nonaktif' && $activeAdminCount <= 1))>Hapus</button>
                                                                </form>
                                                            </div>
                                                        </details>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td class="account-monitor-empty" colspan="7">Belum ada akun terdaftar.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <h3 class="account-monitor-history-title">Riwayat perubahan password terbaru</h3>
                                <div class="account-monitor-table-wrap">
                                    <table class="account-monitor-table">
                                        <thead><tr><th>Email saat perubahan</th><th>Aksi</th><th>Diubah oleh</th><th>Waktu</th></tr></thead>
                                        <tbody>
                                            @forelse ($passwordChangeHistory as $change)
                                                <tr>
                                                    <td>{{ $change->email }}</td>
                                                    <td>{{ match ($change->action) { 'admin_reset' => 'Reset oleh admin', 'account_created' => 'Akun dibuat', 'account_deleted' => 'Akun dihapus', default => 'Diubah pengguna' } }}</td>
                                                    <td>{{ $change->changedBy?->name ?? 'Akun sudah tidak tersedia' }}</td>
                                                    <td>{{ $change->changed_at->translatedFormat('d M Y, H:i') }}</td>
                                                </tr>
                                            @empty
                                                <tr><td class="account-monitor-empty" colspan="4">Belum ada perubahan password sejak pemantauan diaktifkan.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    @endif
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
                    button.setAttribute('aria-pressed', String(showPassword));
                    button.classList.toggle('is-visible', showPassword);
                });
            });
        </script>
    </body>
</html>