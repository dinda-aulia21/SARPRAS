<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Dashboard admin inventaris Yayasan Raudhah Syarifah.">
        <title>Dashboard Admin | Sarpras</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-body">
        <div class="admin-layout">
            <aside class="admin-sidebar" id="admin-sidebar">
                <a class="admin-brand" href="{{ url('/admin') }}">
                    <span class="brand-mark" aria-hidden="true">R</span>
                    <span>Yayasan Raudhah<br>Syarifah</span>
                </a>
                <nav class="admin-nav" aria-label="Navigasi admin">
                    <a class="admin-nav-link active" href="{{ url('/admin') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 13 8-8 8 8v7H4z"/><path d="M9 20v-5h6v5"/></svg>Dashboard</a>
                    <a class="admin-nav-link" href="{{ route('admin.inventaris') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v13H4zM4 7l2-4h12l2 4M9 11h6M8 3v4M16 3v4"/></svg>Data Inventaris</a>
                    <a class="admin-nav-link" href="#laporan"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3h14v18H5zM8 7h8M8 11h8M8 15h5"/></svg>Laporan</a>
                    <a class="admin-nav-link" href="#pengaturan"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/><path d="m19 13 2-1-2-1-.4-1.1 1-1.8-2.7-2.7-1.8 1L12 6l-1-2-1 2-1.1.4-1.8-1L5.4 6.1l1 1.8L6 9l-2 1 2 1 .4 1.1-1 1.8 2.7 2.7 1.8-1L11 16l1 2 1-2 1.1-.4 1.8 1 2.7-2.7-1-1.8z"/></svg>Pengaturan</a>
                </nav>
                <div class="admin-sidebar-footer">
                    <div class="admin-profile"><span class="profile-avatar">AR</span><span><strong>Admin</strong><small>Administrator</small></span></div>
                    <form class="admin-logout-form" action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="admin-logout" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3M19 3h2v18h-2"/></svg>Keluar</button>
                    </form>
                </div>
            </aside>
            <main class="admin-main">
                <header class="admin-topbar">
                    <button class="sidebar-toggle" type="button" aria-controls="admin-sidebar" aria-expanded="false" aria-label="Buka navigasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
                    <div class="admin-search"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/></svg><input type="search" placeholder="Cari inventaris, kategori, atau lokasi..." aria-label="Cari inventaris"></div>
                    <div class="topbar-actions"><button class="icon-button notification-button" type="button" aria-label="Notifikasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><span></span></button><div class="date-label"><strong>Selasa, 23 September 2025</strong><small>10:34 WIB</small></div><button class="account-button" type="button"><span class="mini-avatar">A</span><span>Admin</span><span class="chevron">⌄</span></button></div>
                </header>
                <div class="admin-content">
                    <section class="admin-welcome"><div><p class="welcome-eyebrow">Selamat Datang,</p><h1>Admin</h1><p>Kelola data inventaris sarana dan prasarana<br class="desktop-only"> Yayasan Raudhah Syarifah dengan mudah dan terstruktur.</p></div><div class="welcome-image" role="img" aria-label="Gedung Yayasan Raudhah Syarifah"></div></section>
                    <section class="stat-grid" aria-label="Ringkasan inventaris">
                        <article class="stat-card stat-total"><span class="stat-icon">▣</span><div><p>Total Inventaris</p><strong>128</strong><small>Jumlah seluruh barang</small></div><span class="stat-trend">+8%</span></article>
                        <article class="stat-card stat-good"><span class="stat-icon">✓</span><div><p>Kondisi Baik</p><strong>112</strong><small>87,5% dari total</small></div><span class="stat-progress"><i></i></span></article>
                        <article class="stat-card stat-minor"><span class="stat-icon">!</span><div><p>Rusak Ringan</p><strong>10</strong><small>7,8% dari total</small></div><span class="stat-progress"><i></i></span></article>
                        <article class="stat-card stat-major"><span class="stat-icon">!</span><div><p>Rusak Berat</p><strong>6</strong><small>4,7% dari total</small></div><span class="stat-progress"><i></i></span></article>
                    </section>
                    <section class="dashboard-grid">
                        <article class="dashboard-panel category-panel" id="laporan"><div class="panel-heading"><div><p class="panel-kicker">Ringkasan</p><h2>Rekap Inventaris per Kategori</h2></div><button class="panel-menu" type="button" aria-label="Menu rekap">•••</button></div><div class="category-content"><div class="donut-chart"><div><strong>128</strong><small>Total inventaris</small></div></div><ul class="category-list"><li><span><i class="dot dot-green"></i>Furnitur</span><strong>45</strong></li><li><span><i class="dot dot-blue"></i>Elektronik</span><strong>35</strong></li><li><span><i class="dot dot-yellow"></i>Sarana Pembelajaran</span><strong>18</strong></li><li><span><i class="dot dot-orange"></i>Kesehatan</span><strong>18</strong></li><li><span><i class="dot dot-purple"></i>Lainnya</span><strong>12</strong></li></ul></div></article>
                        <article class="dashboard-panel inventory-panel" id="inventaris"><div class="panel-heading"><div><p class="panel-kicker">Data terbaru</p><h2>Inventaris Terbaru</h2></div><a href="#inventaris">Lihat Semua <span aria-hidden="true">→</span></a></div><div class="table-wrap"><table><thead><tr><th>No</th><th>Nama Barang</th><th>Kategori</th><th>Lokasi</th><th>Kondisi</th></tr></thead><tbody><tr><td>01</td><td>Proyektor</td><td>Elektronik</td><td>Ruang Kelas 2</td><td><span class="status status-good">Baik</span></td></tr><tr><td>02</td><td>Meja Guru</td><td>Furnitur</td><td>Ruang Guru</td><td><span class="status status-good">Baik</span></td></tr><tr><td>03</td><td>Laptop</td><td>Elektronik</td><td>Ruang TU</td><td><span class="status status-good">Baik</span></td></tr><tr><td>04</td><td>AC</td><td>Elektronik</td><td>Ruang Kelas 1</td><td><span class="status status-minor">Rusak Ringan</span></td></tr><tr><td>05</td><td>Kursi Plastik</td><td>Furnitur</td><td>Lapangan</td><td><span class="status status-good">Baik</span></td></tr></tbody></table></div></article>
                        <article class="dashboard-panel announcement-panel" id="pengaturan"><div class="panel-heading"><div><p class="panel-kicker">Pusat informasi</p><h2>Pengumuman</h2></div><a href="#pengaturan">Lihat Semua <span aria-hidden="true">→</span></a></div><ul class="announcement-list"><li><span class="announcement-icon">▣</span><div><strong>Pemeliharaan Gedung</strong><small>Admin • 20 Sep 2025</small><p>Akan dilakukan pemeliharaan ruang kelas pada 3 dan 4 September.</p></div></li><li><span class="announcement-icon">↻</span><div><strong>Pengadaan Inventaris Baru</strong><small>Admin • 17 Sep 2025</small><p>Tersedia pengajuan baru untuk inventaris sarana.</p></div></li><li><span class="announcement-icon">✓</span><div><strong>Rapat Koordinasi</strong><small>Admin • 15 Sep 2025</small><p>Rapat koordinasi inventaris akan dilaksanakan pekan depan.</p></div></li></ul></article>
                    </section>
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
        </script>
    </body>
</html>