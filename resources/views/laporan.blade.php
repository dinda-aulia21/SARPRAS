<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Laporan inventaris sarana dan prasarana Yayasan Raudhah Syarifah.">
        <title>Laporan | Sarpras</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-body">
        <div class="admin-layout">
            <aside class="admin-sidebar" id="admin-sidebar">
                <a class="admin-brand" href="{{ auth()->user()->role === 'kepala_yayasan' ? route('kepala-yayasan.dashboard') : (auth()->user()->role === 'kepala_sekolah' ? route('kepala-sekolah.dashboard') : route('admin.dashboard')) }}"><span class="brand-mark" aria-hidden="true">R</span><span>Yayasan Raudhah<br>Syarifah</span></a>
                <nav class="admin-nav" aria-label="Navigasi admin">
                    <a class="admin-nav-link" href="{{ auth()->user()->role === 'kepala_yayasan' ? route('kepala-yayasan.dashboard') : (auth()->user()->role === 'kepala_sekolah' ? route('kepala-sekolah.dashboard') : route('admin.dashboard')) }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 13 8-8 8 8v7H4z"/><path d="M9 20v-5h6v5"/></svg>Dashboard</a>
                    <a class="admin-nav-link" href="{{ route('admin.inventaris') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v13H4zM4 7l2-4h12l2 4M9 11h6M8 3v4M16 3v4"/></svg>Data Inventaris</a>
                    <a class="admin-nav-link active" href="{{ route('admin.laporan') }}" aria-current="page"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3h14v18H5zM8 7h8M8 11h8M8 15h5"/></svg>Laporan</a>
                    <a class="admin-nav-link" href="{{ route('admin.pengumuman') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 11 18-5v12L3 13v-2Z"/><path d="m7 14 2 6h4l-3-7M21 10v4"/></svg>Pengumuman</a>
                    <a class="admin-nav-link" href="{{ route('admin.pengaturan') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/><circle cx="9" cy="6" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="8" cy="18" r="2"/></svg>Pengaturan</a>
                </nav>
                <div class="admin-sidebar-footer">
                    <div class="admin-profile"><span class="profile-avatar">AR</span><span><strong>Admin</strong><small>Administrator</small></span></div>
                    <form class="admin-logout-form" action="{{ route('logout') }}" method="post">@csrf<button class="admin-logout" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3M19 3h2v18h-2"/></svg>Keluar</button></form>
                </div>
            </aside>
            <main class="admin-main">
                <header class="admin-topbar">
                    <button class="sidebar-toggle" type="button" aria-controls="admin-sidebar" aria-expanded="false" aria-label="Buka navigasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
                    <div class="admin-search"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/></svg><input type="search" placeholder="Cari inventaris, kategori, atau lokasi..." aria-label="Cari inventaris"></div>
                    <div class="topbar-actions"><button class="icon-button notification-button" type="button" aria-label="Notifikasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><span></span></button><div class="date-label"><strong>{{ now()->translatedFormat('l, d F Y') }}</strong><small>{{ now()->format('H:i') }} WIB</small></div><button class="account-button" type="button"><span class="mini-avatar">A</span><span>Admin</span><span class="chevron">⌄</span></button></div>
                </header>
                @php($reportTitle = ['inventaris' => 'Laporan Inventaris', 'kondisi' => 'Laporan Kondisi', 'cetak' => 'Cetak Laporan'][$type])
                <div class="admin-content inventory-page-content report-page-content">
                    <div class="page-heading"><div><p class="welcome-eyebrow">Ringkasan dan dokumentasi</p><h1>Laporan</h1><p>Lihat, saring, dan cetak laporan data inventaris sarana dan prasarana.</p></div></div>
                    <section class="report-type-section" aria-labelledby="report-type-title">
                        <h2 id="report-type-title">Jenis Laporan</h2>
                        <div class="report-type-grid">
                            <a class="report-type-card {{ $type === 'inventaris' ? 'is-selected' : '' }}" href="{{ route('admin.laporan', array_merge(request()->query(), ['type' => 'inventaris'])) }}" @if ($type === 'inventaris') aria-current="page" @endif><span class="report-type-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3h14v18H5zM8 7h8M8 11h8M8 15h5"/></svg></span><span><strong>Laporan Inventaris</strong><small>Laporan data seluruh inventaris berdasarkan filter yang dipilih.</small></span><span class="report-card-arrow" aria-hidden="true">›</span></a>
                            <a class="report-type-card {{ $type === 'kondisi' ? 'is-selected' : '' }}" href="{{ route('admin.laporan', array_merge(request()->query(), ['type' => 'kondisi'])) }}" @if ($type === 'kondisi') aria-current="page" @endif><span class="report-type-icon report-type-icon-blue"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 20 6v5c0 5-3.5 8-8 10-4.5-2-8-5-8-10V6z"/><path d="m8.5 12 2.2 2.2 4.8-5"/></svg></span><span><strong>Laporan Kondisi</strong><small>Rekap kondisi baik dan inventaris yang membutuhkan tindak lanjut.</small></span><span class="report-card-arrow" aria-hidden="true">›</span></a>
                            <a class="report-type-card {{ $type === 'cetak' ? 'is-selected' : '' }}" href="{{ route('admin.laporan', array_merge(request()->query(), ['type' => 'cetak'])) }}" @if ($type === 'cetak') aria-current="page" @endif><span class="report-type-icon report-type-icon-slate"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 8V3h10v5M7 17H5a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M7 14h10v7H7zM17 11h.01"/></svg></span><span><strong>Cetak Laporan</strong><small>Cetak laporan inventaris dalam format siap cetak atau PDF.</small></span><span class="report-card-arrow" aria-hidden="true">›</span></a>
                        </div>
                    </section>
                    <section class="report-filter-panel" aria-labelledby="report-filter-title">
                        <div class="report-section-heading"><h2 id="report-filter-title"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 5h16l-6.5 7.5V19l-3 1v-7.5z"/></svg>Filter {{ $reportTitle }}</h2></div>
                        <form class="report-filter-form" action="{{ route('admin.laporan') }}" method="get">
                            <input type="hidden" name="type" value="{{ $type }}">
                            <label class="filter-control"><span>Periode</span><select name="year"><option value="">Semua Periode</option>@foreach ($years as $year)<option value="{{ $year }}" @selected(request('year') == $year)>{{ $year }}</option>@endforeach</select></label>
                            <label class="filter-control"><span>Kategori</span><select name="category"><option value="">Semua Kategori</option>@foreach ($categories as $category)<option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>@endforeach</select></label>
                            <label class="filter-control"><span>Lokasi</span><select name="location"><option value="">Semua Lokasi</option>@foreach ($locations as $location)<option value="{{ $location }}" @selected(request('location') === $location)>{{ $location }}</option>@endforeach</select></label>
                            <label class="filter-control"><span>Kondisi</span><select name="condition"><option value="">Semua Kondisi</option><option value="Baik" @selected(request('condition') === 'Baik')>Baik</option><option value="Rusak Ringan" @selected(request('condition') === 'Rusak Ringan')>Rusak Ringan</option><option value="Rusak Berat" @selected(request('condition') === 'Rusak Berat')>Rusak Berat</option></select></label>
                            <div class="report-filter-actions"><a class="report-reset-button" href="{{ route('admin.laporan', ['type' => $type]) }}" aria-label="Reset filter" title="Reset filter"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 7v5h-5M4 17v-5h5"/><path d="M5.5 9a7 7 0 0 1 11.8-2L20 12M4 12l2.7 5a7 7 0 0 0 11.8-2"/></svg></a><button class="report-submit-button" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/></svg>Tampilkan</button></div>
                        </form>
                    </section>
                    <section class="report-table-panel dashboard-panel" aria-labelledby="report-table-title">
                        <div class="report-table-heading"><div><h2 id="report-table-title"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3h14v18H5zM8 7h8M8 11h8M8 15h5"/></svg>Hasil {{ $reportTitle }}</h2><p>Menampilkan {{ $inventories->count() }} data inventaris</p></div><div class="report-export-actions"><button class="report-outline-button" type="button" data-export-csv><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v12m-5-5 5 5 5-5M5 17v4h14v-4"/></svg>CSV</button><button class="report-primary-button" type="button" data-print-report><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 8V3h10v5M7 17H5a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M7 14h10v7H7z"/></svg>{{ $type === 'cetak' ? 'Cetak Sekarang' : 'Export PDF' }}</button></div></div>
                        <div class="report-table-wrap"><table id="report-table"><thead><tr><th>No</th><th>Nama Barang</th><th>Kategori</th><th>Lokasi</th><th>Jumlah</th><th>Kondisi</th><th>Tahun Pendataan</th></tr></thead><tbody>
                            @forelse ($inventories as $index => $inventory)
                                @php($conditionClass = $inventory->condition === 'Baik' ? 'status-good' : ($inventory->condition === 'Rusak Ringan' ? 'status-minor' : 'status-major'))
                                <tr><td>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td><td>{{ $inventory->name }}</td><td>{{ $inventory->category }}</td><td>{{ $inventory->location }}</td><td>{{ $inventory->quantity }}</td><td><span class="status {{ $conditionClass }}">{{ $inventory->condition }}</span></td><td>{{ $inventory->created_at?->format('Y') ?? '-' }}</td></tr>
                            @empty
                                <tr><td class="report-empty-state" colspan="7">Belum ada data inventaris yang sesuai dengan filter.</td></tr>
                            @endforelse
                        </tbody></table></div>
                        <div class="report-table-footer"><span>Total <strong>{{ $inventories->count() }}</strong> data</span><span>Dicetak pada {{ now()->translatedFormat('d F Y') }}</span></div>
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

            document.querySelector('[data-print-report]')?.addEventListener('click', () => window.print());
            document.querySelector('[data-export-csv]')?.addEventListener('click', () => {
                const rows = [...document.querySelectorAll('#report-table tr')].map((row) => [...row.cells].map((cell) => `"${cell.innerText.replaceAll('"', '""')}"`).join(','));
                const file = new Blob(['\ufeff' + rows.join('\r\n')], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                link.href = URL.createObjectURL(file);
                link.download = 'laporan-inventaris.csv';
                link.click();
                URL.revokeObjectURL(link.href);
            });
        </script>
    </body>
</html>