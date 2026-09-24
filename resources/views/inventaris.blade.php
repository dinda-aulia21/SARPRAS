<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Data inventaris sarana dan prasarana Yayasan Raudhah Syarifah.">
        <title>Data Inventaris | Sarpras</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-body">
        <div class="admin-layout">
            <aside class="admin-sidebar" id="admin-sidebar">
                <a class="admin-brand" href="{{ route('admin.dashboard') }}"><span class="brand-mark" aria-hidden="true">R</span><span>Yayasan Raudhah<br>Syarifah</span></a>
                <nav class="admin-nav" aria-label="Navigasi admin">
                    <a class="admin-nav-link" href="{{ route('admin.dashboard') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 13 8-8 8 8v7H4z"/><path d="M9 20v-5h6v5"/></svg>Dashboard</a>
                    <a class="admin-nav-link active" href="{{ route('admin.inventaris') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v13H4zM4 7l2-4h12l2 4M9 11h6M8 3v4M16 3v4"/></svg>Data Inventaris</a>
                    <a class="admin-nav-link" href="#laporan"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3h14v18H5zM8 7h8M8 11h8M8 15h5"/></svg>Laporan</a>
                    <a class="admin-nav-link" href="#pengaturan"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Z"/><path d="m19 13 2-1-2-1-.4-1.1 1-1.8-2.7-2.7-1.8 1L12 6l-1-2-1 2-1.1.4-1.8-1L5.4 6.1l1 1.8L6 9l-2 1 2 1 .4 1.1-1 1.8 2.7 2.7 1.8-1L11 16l1 2 1-2 1.1-.4 1.8 1 2.7-2.7-1-1.8z"/></svg>Pengaturan</a>
                </nav>
                <div class="admin-sidebar-footer"><div class="admin-profile"><span class="profile-avatar">AR</span><span><strong>Admin</strong><small>Administrator</small></span></div><form class="admin-logout-form" action="{{ route('logout') }}" method="post">@csrf<button class="admin-logout" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3M19 3h2v18h-2"/></svg>Keluar</button></form></div>
            </aside>
            <main class="admin-main">
                <header class="admin-topbar"><button class="sidebar-toggle" type="button" aria-controls="admin-sidebar" aria-expanded="false" aria-label="Buka navigasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button><div class="admin-search"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/></svg><input type="search" placeholder="Cari inventaris, kategori, atau lokasi..." aria-label="Cari inventaris"></div><div class="topbar-actions"><button class="icon-button notification-button" type="button" aria-label="Notifikasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><span></span></button><div class="date-label"><strong>Selasa, 23 September 2025</strong><small>10:34 WIB</small></div><button class="account-button" type="button"><span class="mini-avatar">A</span><span>Admin</span><span class="chevron">⌄</span></button></div></header>
                <div class="admin-content inventory-page-content">
                    <div class="page-heading"><div><p class="welcome-eyebrow">Manajemen data</p><h1>Data Inventaris</h1><p>Kelola seluruh data sarana dan prasarana Yayasan Raudhah Syarifah.</p></div><button class="inventory-primary-button" type="button" data-open-inventory-modal><span aria-hidden="true">+</span> Tambah Inventaris</button></div>
                    @if (session('success'))
                        <div class="inventory-alert" role="status">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="inventory-alert inventory-alert-error" role="alert">Periksa kembali data inventaris yang diisi.</div>
                    @endif
                    <section class="inventory-summary" aria-label="Ringkasan data inventaris"><article><span class="summary-icon summary-green">▣</span><div><small>Total Barang</small><strong>{{ 128 + $inventories->count() }}</strong></div></article><article><span class="summary-icon summary-blue">✓</span><div><small>Kondisi Baik</small><strong>112</strong></div></article><article><span class="summary-icon summary-yellow">!</span><div><small>Rusak Ringan</small><strong>10</strong></div></article><article><span class="summary-icon summary-red">!</span><div><small>Rusak Berat</small><strong>6</strong></div></article></section>
                    <div class="inventory-modal" data-inventory-modal aria-hidden="true"><div class="inventory-modal-backdrop" data-close-inventory-modal></div><section class="inventory-modal-content" role="dialog" aria-modal="true" aria-labelledby="inventory-modal-title"><div class="inventory-modal-heading"><div><p class="panel-kicker">Data baru</p><h2 id="inventory-modal-title">Tambah Inventaris</h2></div><button class="inventory-modal-close" type="button" aria-label="Tutup" data-close-inventory-modal>&times;</button></div><form action="{{ route('admin.inventaris.store') }}" method="post" class="inventory-form">@csrf<div class="inventory-form-grid"><label>Nama Barang<input name="name" type="text" value="{{ old('name') }}" required></label><label>Kategori<select name="category" required><option value="">Pilih kategori</option><option>Elektronik</option><option>Furnitur</option><option>Sarana Pembelajaran</option><option>Kesehatan</option><option>Lainnya</option></select></label><label>Lokasi<input name="location" type="text" value="{{ old('location') }}" placeholder="Contoh: Ruang Kelas 1" required></label><label>Jumlah<input name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}" required></label><label>Kondisi<select name="condition" required><option value="">Pilih kondisi</option><option>Baik</option><option>Rusak Ringan</option><option>Rusak Berat</option></select></label></div><div class="inventory-form-actions"><button class="inventory-secondary-button" type="button" data-close-inventory-modal>Batal</button><button class="inventory-primary-button" type="submit">Simpan Inventaris</button></div></form></section></div>
                    <section class="inventory-table-panel dashboard-panel"><div class="inventory-toolbar"><div><p class="panel-kicker">Daftar sarana dan prasarana</p><h2>Semua Inventaris <span>128 data</span></h2></div><div class="inventory-actions"><label class="filter-control"><span>Kategori</span><select><option>Semua Kategori</option><option>Elektronik</option><option>Furnitur</option><option>Sarana Pembelajaran</option></select></label><label class="filter-control"><span>Kondisi</span><select><option>Semua Kondisi</option><option>Baik</option><option>Rusak Ringan</option><option>Rusak Berat</option></select></label><button class="filter-button" type="button" aria-label="Buka filter tambahan"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M7 12h10M10 18h4"/></svg></button></div></div><div class="full-inventory-table"><table><thead><tr><th><input type="checkbox" aria-label="Pilih semua"></th><th>No</th><th>Nama Barang</th><th>Kategori</th><th>Lokasi</th><th>Jumlah</th><th>Kondisi</th><th>Terakhir Diperbarui</th><th>Aksi</th></tr></thead><tbody><tr><td><input type="checkbox" aria-label="Pilih Proyektor"></td><td>01</td><td><strong>Proyektor Epson EB-X06</strong><small>INV-2025-0001</small></td><td>Elektronik</td><td>Ruang Kelas 2</td><td>4 unit</td><td><span class="status status-good">Baik</span></td><td>20 Sep 2025</td><td><button class="row-action" type="button" aria-label="Opsi Proyektor">•••</button></td></tr><tr><td><input type="checkbox" aria-label="Pilih Meja Guru"></td><td>02</td><td><strong>Meja Guru</strong><small>INV-2025-0002</small></td><td>Furnitur</td><td>Ruang Guru</td><td>12 unit</td><td><span class="status status-good">Baik</span></td><td>19 Sep 2025</td><td><button class="row-action" type="button" aria-label="Opsi Meja Guru">•••</button></td></tr><tr><td><input type="checkbox" aria-label="Pilih Laptop"></td><td>03</td><td><strong>Laptop Lenovo ThinkPad</strong><small>INV-2025-0003</small></td><td>Elektronik</td><td>Ruang TU</td><td>8 unit</td><td><span class="status status-good">Baik</span></td><td>18 Sep 2025</td><td><button class="row-action" type="button" aria-label="Opsi Laptop">•••</button></td></tr><tr><td><input type="checkbox" aria-label="Pilih AC"></td><td>04</td><td><strong>AC Panasonic 1 PK</strong><small>INV-2025-0004</small></td><td>Elektronik</td><td>Ruang Kelas 1</td><td>3 unit</td><td><span class="status status-minor">Rusak Ringan</span></td><td>17 Sep 2025</td><td><button class="row-action" type="button" aria-label="Opsi AC">•••</button></td></tr><tr><td><input type="checkbox" aria-label="Pilih Kursi Plastik"></td><td>05</td><td><strong>Kursi Plastik</strong><small>INV-2025-0005</small></td><td>Furnitur</td><td>Lapangan</td><td>45 unit</td><td><span class="status status-good">Baik</span></td><td>15 Sep 2025</td><td><button class="row-action" type="button" aria-label="Opsi Kursi Plastik">•••</button></td></tr></tbody></table></div><div class="inventory-footer"><span>Menampilkan <strong>1-5</strong> dari <strong>128</strong> data</span><div><button type="button" disabled>←</button><button class="current-page" type="button">1</button><button type="button">2</button><button type="button">3</button><button type="button">→</button></div></div></section>
                </div>
            </main>
        </div>
            <script>
                const toggle = document.querySelector('.sidebar-toggle');
                const sidebar = document.querySelector('.admin-sidebar');
                const inventoryModal = document.querySelector('[data-inventory-modal]');
                const openInventoryModal = document.querySelector('[data-open-inventory-modal]');
                const closeInventoryModal = document.querySelectorAll('[data-close-inventory-modal]');

                toggle?.addEventListener('click', () => {
                    const isOpen = sidebar.classList.toggle('is-open');
                    toggle.setAttribute('aria-expanded', String(isOpen));
                });

                const setInventoryModal = (isOpen) => {
                    inventoryModal?.classList.toggle('is-visible', isOpen);
                    inventoryModal?.setAttribute('aria-hidden', String(!isOpen));
                };

                openInventoryModal?.addEventListener('click', () => setInventoryModal(true));
                closeInventoryModal.forEach((button) => button.addEventListener('click', () => setInventoryModal(false)));
                const savedInventories = @json($inventories);
                const inventoryBody = document.querySelector('.full-inventory-table tbody');
                const escapeHtml = (value) => String(value).replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[character]);
                savedInventories.reverse().forEach((inventory, index) => {
                    const row = document.createElement('tr');
                    const conditionClass = inventory.condition === 'Baik' ? 'status-good' : (inventory.condition === 'Rusak Ringan' ? 'status-minor' : 'status-major');
                    row.innerHTML = `<td><input type="checkbox" aria-label="Pilih ${escapeHtml(inventory.name)}"></td><td>${String(index + 1).padStart(2, '0')}</td><td><strong>${escapeHtml(inventory.name)}</strong><small>INV-${inventory.id}</small></td><td>${escapeHtml(inventory.category)}</td><td>${escapeHtml(inventory.location)}</td><td>${inventory.quantity} unit</td><td><span class="status ${conditionClass}">${escapeHtml(inventory.condition)}</span></td><td>${new Date(inventory.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td><td><button class="row-action" type="button" aria-label="Opsi ${escapeHtml(inventory.name)}">•••</button></td>`;
                    inventoryBody?.prepend(row);
                });
                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') setInventoryModal(false);
                });
            </script>
    </body>
</html>