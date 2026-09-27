<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Dashboard Kepala Yayasan Raudhah Syarifah.">
        <title>Dashboard Kepala Yayasan | Sarpras</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-body">
        <div class="admin-layout">
            <aside class="admin-sidebar" id="admin-sidebar">
                <a class="admin-brand" href="{{ route('kepala-yayasan.dashboard') }}"><span class="brand-mark" aria-hidden="true">R</span><span>Yayasan Raudhah<br>Syarifah</span></a>
                <nav class="admin-nav" aria-label="Navigasi admin">
                    <a class="admin-nav-link active" href="{{ route('kepala-yayasan.dashboard') }}" aria-current="page"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 13 8-8 8 8v7H4z"/><path d="M9 20v-5h6v5"/></svg>Dashboard</a>
                    <a class="admin-nav-link" href="{{ route('admin.inventaris') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v13H4zM4 7l2-4h12l2 4M9 11h6M8 3v4M16 3v4"/></svg>Data Inventaris</a>
                    <a class="admin-nav-link" href="#lokasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>Lokasi</a>
                    <a class="admin-nav-link" href="{{ route('admin.laporan') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3h14v18H5zM8 7h8M8 11h8M8 15h5"/></svg>Laporan</a>
                    <a class="admin-nav-link" href="{{ route('admin.pengumuman') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 11 18-5v12L3 13v-2Z"/><path d="m7 14 2 6h4l-3-7M21 10v4"/></svg>Pengumuman</a>
                    <a class="admin-nav-link" href="{{ route('admin.pengaturan') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/><path d="m19 13 2-1-2-1-.4-1.1 1-1.8-2.7-2.7-1.8 1L12 6l-1-2-1 2-1.1.4-1.8-1L5.4 6.1l1 1.8L6 9l-2 1 2 1 .4 1.1-1 1.8 2.7 2.7 1.8-1L11 16l1 2 1-2 1.1-.4 1.8 1 2.7-2.7-1-1.8z"/></svg>Pengaturan</a>
                </nav>
                <div class="admin-sidebar-footer">
                    <div class="admin-profile"><span class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span><span><strong>{{ $user->name }}</strong><small>Kepala Yayasan</small></span></div>
                    <form class="admin-logout-form" action="{{ route('logout') }}" method="post">@csrf<button class="admin-logout" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3M19 3h2v18h-2"/></svg>Keluar</button></form>
                </div>
            </aside>
            <main class="admin-main">
                <header class="admin-topbar">
                    <button class="sidebar-toggle" type="button" aria-controls="admin-sidebar" aria-expanded="false" aria-label="Buka navigasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
                    <div class="admin-search"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/></svg><input type="search" placeholder="Cari inventaris, kategori, atau lokasi..." aria-label="Cari inventaris"></div>
                    <div class="topbar-actions"><button class="icon-button notification-button" type="button" aria-label="Notifikasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><span></span></button><div class="date-label"><strong>{{ now()->translatedFormat('l, d F Y') }}</strong><small>{{ now()->format('H:i') }} WIB</small></div><button class="account-button" type="button"><span class="mini-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span><span>{{ $user->name }}</span><span class="chevron">⌄</span></button></div>
                </header>
                <div class="admin-content executive-dashboard-content">
                    <section class="executive-welcome">
                        <div class="executive-welcome-copy"><p class="welcome-eyebrow">Selamat Datang,</p><h1>Kepala Yayasan</h1><p>Pantau kondisi sarana dan prasarana Yayasan Raudhah Syarifah secara keseluruhan.</p></div>
                        <div class="executive-welcome-note"><span>Ringkasan manajemen</span><strong>Informasi penting yayasan dalam satu tampilan.</strong></div>
                    </section>
                    <section class="stat-grid executive-stat-grid" aria-label="Ringkasan inventaris">
                        <article class="stat-card stat-total"><span class="stat-icon">▣</span><div><p>Total Inventaris</p><strong>{{ number_format($totalQuantity, 0, ',', '.') }}</strong><small>Jumlah seluruh unit</small></div><span class="stat-trend">{{ $categorySummaries->count() }} kategori</span></article>
                        <article class="stat-card stat-good"><span class="stat-icon">✓</span><div><p>Kondisi Baik</p><strong>{{ number_format($totalGood, 0, ',', '.') }}</strong><small>{{ number_format($goodPercentage, 1, ',', '.') }}% dari total</small></div><span class="stat-progress"><i style="width: {{ $goodPercentage }}%"></i></span></article>
                        <article class="stat-card stat-minor"><span class="stat-icon">!</span><div><p>Rusak Ringan</p><strong>{{ number_format($totalMinor, 0, ',', '.') }}</strong><small>{{ number_format($minorPercentage, 1, ',', '.') }}% dari total</small></div><span class="stat-progress"><i style="width: {{ $minorPercentage }}%"></i></span></article>
                        <article class="stat-card stat-major"><span class="stat-icon">!</span><div><p>Rusak Berat</p><strong>{{ number_format($totalMajor, 0, ',', '.') }}</strong><small>{{ number_format($majorPercentage, 1, ',', '.') }}% dari total</small></div><span class="stat-progress"><i style="width: {{ $majorPercentage }}%"></i></span></article>
                    </section>
                    <section class="executive-dashboard-grid">
                        <div class="executive-main-column">
                            <article class="dashboard-panel executive-panel executive-category-panel">
                                <div class="panel-heading"><div><p class="panel-kicker">Ringkasan</p><h2>Rekap Inventaris Berdasarkan Kategori</h2></div><a href="{{ route('admin.laporan') }}">Lihat Laporan <span aria-hidden="true">→</span></a></div>
                                <div class="category-content executive-category-content">
                                    <div class="donut-chart executive-donut-chart" style="background: {{ $categoryGradient }}"><div><strong>{{ number_format($totalQuantity, 0, ',', '.') }}</strong><small>Total barang</small></div></div>
                                    @if ($categorySummaries->isNotEmpty())
                                        <ul class="category-list executive-category-list">
                                            @foreach ($categorySummaries as $index => $category)
                                                @php($categoryPercentage = $totalQuantity > 0 ? round(($category->total / $totalQuantity) * 100, 1) : 0)
                                                <li><span><i class="dot" style="background: {{ ['#3288d1', '#15936e', '#f1bd35', '#8158c6', '#e56983'][$index % 5] }}"></i>{{ $category->category }}</span><strong>{{ number_format($category->total, 0, ',', '.') }} ({{ number_format($categoryPercentage, 1, ',', '.') }}%)</strong></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="executive-empty-note">Data kategori belum tersedia.</p>
                                    @endif
                                </div>
                            </article>
                            <article class="dashboard-panel executive-panel executive-inventory-panel">
                                <div class="panel-heading"><div><p class="panel-kicker">Data terbaru</p><h2>Data Inventaris Terbaru</h2></div><a href="{{ route('admin.inventaris') }}">Lihat Semua <span aria-hidden="true">→</span></a></div>
                                <div class="executive-inventory-table-wrap"><table class="executive-inventory-table"><thead><tr><th>No</th><th>Kode Barang</th><th>Nama Barang</th><th>Kategori</th><th>Lokasi</th><th>Jumlah</th><th>Kondisi</th></tr></thead><tbody>
                                    @forelse ($recentInventories as $index => $inventory)
                                        @php($conditionClass = $inventory->condition === 'Baik' ? 'status-good' : ($inventory->condition === 'Rusak Ringan' ? 'status-minor' : 'status-major'))
                                        <tr><td>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td><td>INV-{{ str_pad($inventory->id, 5, '0', STR_PAD_LEFT) }}</td><td>{{ $inventory->name }}</td><td>{{ $inventory->category }}</td><td>{{ $inventory->location }}</td><td>{{ $inventory->quantity }}</td><td><span class="status {{ $conditionClass }}">{{ $inventory->condition }}</span></td></tr>
                                    @empty
                                        <tr><td class="executive-table-empty" colspan="7">Belum ada data inventaris.</td></tr>
                                    @endforelse
                                </tbody></table></div>
                            </article>
                        </div>
                        <div class="executive-side-column">
                            <article class="dashboard-panel executive-panel executive-announcement-panel">
                                <div class="panel-heading"><div><p class="panel-kicker">Pusat informasi</p><h2>Pengumuman Terbaru</h2></div><a href="{{ route('admin.pengumuman') }}">Lihat Semua <span aria-hidden="true">→</span></a></div>
                                @if ($recentAnnouncements->isNotEmpty())
                                    <ul class="announcement-list executive-announcement-list">
                                        @foreach ($recentAnnouncements as $index => $announcement)
                                            <li><span class="announcement-icon">{{ ['▣', '↻', '✓'][$index % 3] }}</span><div><strong>{{ $announcement->title }}</strong><small>{{ $announcement->publish_date->translatedFormat('d F Y') }}</small><p>{{ \Illuminate\Support\Str::limit($announcement->body, 100) }}</p></div><span class="executive-active-tag">Aktif</span></li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="executive-empty-note">Belum ada pengumuman aktif.</p>
                                @endif
                            </article>
                            <article class="dashboard-panel executive-panel executive-location-panel" id="lokasi">
                                <div class="panel-heading"><div><p class="panel-kicker">Sebaran inventaris</p><h2>Informasi Singkat</h2></div><span class="executive-location-mark" aria-hidden="true">⌖</span></div>
                                @if ($locationSummaries->isNotEmpty())
                                    <ul class="executive-location-list">
                                        @foreach ($locationSummaries as $location)
                                            <li><span>{{ $location->location }}</span><strong>{{ number_format($location->total, 0, ',', '.') }} unit</strong></li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="executive-empty-note">Lokasi inventaris akan muncul setelah data ditambahkan.</p>
                                @endif
                                <a class="executive-location-link" href="{{ route('admin.inventaris') }}"><span>Lihat data inventaris</span><span aria-hidden="true">→</span></a>
                            </article>
                        </div>
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