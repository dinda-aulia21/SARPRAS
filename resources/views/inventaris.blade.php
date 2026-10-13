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
        @php($canManage = auth()->user()->role === 'admin')
        @php($userRoleLabel = ['admin' => 'Admin Sarpras', 'kepala_yayasan' => 'Kepala Yayasan', 'kepala_sekolah' => 'Kepala Sekolah'][auth()->user()->role] ?? 'Pengguna')
        <div class="admin-layout">
            <aside class="admin-sidebar" id="admin-sidebar">
                <a class="admin-brand" href="{{ auth()->user()->role === 'kepala_yayasan' ? route('kepala-yayasan.dashboard') : (auth()->user()->role === 'kepala_sekolah' ? route('kepala-sekolah.dashboard') : route('admin.dashboard')) }}"><img class="brand-mark" src="{{ asset('images/logo-yayasan.png') }}" alt=""><span>Yayasan Raudhah<br>Syarifah</span></a>
                <nav class="admin-nav" aria-label="Navigasi admin">
                    <a class="admin-nav-link" href="{{ auth()->user()->role === 'kepala_yayasan' ? route('kepala-yayasan.dashboard') : (auth()->user()->role === 'kepala_sekolah' ? route('kepala-sekolah.dashboard') : route('admin.dashboard')) }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 13 8-8 8 8v7H4z"/><path d="M9 20v-5h6v5"/></svg>Dashboard</a>
                    <a class="admin-nav-link active" href="{{ route('admin.inventaris') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v13H4zM4 7l2-4h12l2 4M9 11h6M8 3v4M16 3v4"/></svg>Data Inventaris</a>
                    <a class="admin-nav-link" href="{{ route('admin.laporan') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3h14v18H5zM8 7h8M8 11h8M8 15h5"/></svg>Laporan</a>
                    <a class="admin-nav-link" href="{{ route('admin.pengumuman') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 11 18-5v12L3 13v-2Z"/><path d="m7 14 2 6h4l-3-7M21 10v4"/></svg>Pengumuman</a>
                    <a class="admin-nav-link" href="{{ route('admin.pengaturan') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/><circle cx="9" cy="6" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="8" cy="18" r="2"/></svg>Pengaturan</a>
                </nav>
                <div class="admin-sidebar-footer"><div class="admin-profile"><span class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span><strong>{{ auth()->user()->name }}</strong><small>{{ $userRoleLabel }}</small></span></div><form class="admin-logout-form" action="{{ route('logout') }}" method="post">@csrf<button class="admin-logout" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3M19 3h2v18h-2"/></svg>Keluar</button></form></div>
            </aside>
            <main class="admin-main">
                <header class="admin-topbar"><button class="sidebar-toggle" type="button" aria-controls="admin-sidebar" aria-expanded="false" aria-label="Buka navigasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button><div class="admin-search"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/></svg><input type="search" placeholder="Cari inventaris, kategori, atau lokasi..." aria-label="Cari inventaris"></div><div class="topbar-actions"><button class="icon-button notification-button" type="button" aria-label="Notifikasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><span></span></button><div class="date-label"><strong>{{ now()->translatedFormat('l, d F Y') }}</strong><small>{{ now()->format('H:i') }} WIB</small></div><button class="account-button" type="button"><span class="mini-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span>{{ auth()->user()->name }}</span><span class="chevron">⌄</span></button></div></header>
                <div class="admin-content inventory-page-content">
                    <div class="page-heading"><div><p class="welcome-eyebrow">Manajemen data</p><h1>Data Inventaris</h1><p>Kelola seluruh data sarana dan prasarana Yayasan Raudhah Syarifah.</p></div>@if ($canManage)<button class="inventory-primary-button" type="button" data-open-inventory-modal><span aria-hidden="true">+</span> Tambah Inventaris</button>@endif</div>
                    @if (session('success'))
                        <div class="inventory-alert" role="status">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="inventory-alert inventory-alert-error" role="alert">Periksa kembali data inventaris yang diisi.</div>
                    @endif
                    <section class="inventory-summary" aria-label="Ringkasan data inventaris"><article><span class="summary-icon summary-green">▣</span><div><small>Total Barang</small><strong>0</strong></div></article><article><span class="summary-icon summary-blue">✓</span><div><small>Kondisi Baik</small><strong>0</strong></div></article><article><span class="summary-icon summary-yellow">!</span><div><small>Rusak Ringan</small><strong>0</strong></div></article><article><span class="summary-icon summary-red">!</span><div><small>Rusak Berat</small><strong>0</strong></div></article></section>
                    @if ($canManage)
                        <section class="dashboard-panel" aria-labelledby="school-head-unit-title">
                            <div class="panel-heading"><div><p class="panel-kicker">Akses inventaris</p><h2 id="school-head-unit-title">Unit Kepala Sekolah</h2></div></div>
                            <div class="full-inventory-table school-head-units-table"><table><thead><tr><th>Nama</th><th>Email</th><th>Atur Unit Pendidikan</th><th>Status Unit</th></tr></thead><tbody>
                                @foreach ($schoolHeads as $schoolHead)
                                    <tr><td>{{ $schoolHead->nama_lengkap }}</td><td>{{ $schoolHead->email }}</td><td>
                                        <form action="{{ route('admin.pengguna.unit.update', $schoolHead) }}" method="post">@csrf @method('PUT')
                                            <select name="unit_pendidikan_id" required aria-label="Unit pendidikan {{ $schoolHead->nama_lengkap }}">
                                                <option value="">Pilih unit</option>
                                                @foreach ($units as $unit)<option value="{{ $unit->id }}" @selected($schoolHead->unit_pendidikan_id === $unit->id)>{{ $unit->nama_unit }}</option>@endforeach
                                            </select>
                                            <button type="submit">Simpan Unit</button>
                                        </form>
                                    </td><td>{{ $schoolHead->unitPendidikan?->nama_unit ?? 'Belum ditetapkan' }}</td></tr>
                                @endforeach
                            </tbody></table></div>
                        </section>
                        <section class="dashboard-panel" aria-labelledby="location-management-title">
                            <div class="panel-heading"><div><p class="panel-kicker">Referensi lokasi</p><h2 id="location-management-title">Kelola Lokasi Inventaris</h2></div></div>
                            <form action="{{ route('admin.lokasi.store') }}" method="post" class="inventory-form-grid">
                                @csrf
                                <label>Nama Lokasi<input name="nama_lokasi" required maxlength="255"></label>
                                <label>Unit Pendidikan<select name="unit_pendidikan_id" required><option value="">Pilih unit</option>@foreach ($units as $unit)<option value="{{ $unit->id }}">{{ $unit->nama_unit }}</option>@endforeach</select></label>
                                <label>Keterangan<input name="keterangan"></label>
                                <button class="inventory-primary-button" type="submit">Tambah Lokasi</button>
                            </form>
                            <div class="full-inventory-table location-data-table"><table><thead><tr><th>Lokasi</th><th>Unit</th><th>Aksi</th></tr></thead><tbody>
                                @foreach ($locations as $location)
                                    <tr><td>{{ $location->nama_lokasi }}</td><td>
                                        <form action="{{ route('admin.lokasi.update', $location) }}" method="post">@csrf @method('PUT')
                                            <select name="unit_pendidikan_id" required>@foreach ($units as $unit)<option value="{{ $unit->id }}" @selected($location->unit_pendidikan_id === $unit->id)>{{ $unit->nama_unit }}</option>@endforeach</select>
                                            <button type="submit">Simpan Unit</button>
                                        </form>
                                    </td><td>
                                        <form action="{{ route('admin.lokasi.destroy', $location) }}" method="post" onsubmit="return confirm('Hapus lokasi ini?')">@csrf @method('DELETE')<button type="submit">Hapus</button></form>
                                    </td></tr>
                                @endforeach
                            </tbody></table></div>
                        </section>
                    @endif
                    <div class="inventory-modal" data-inventory-modal aria-hidden="true"><div class="inventory-modal-backdrop" data-close-inventory-modal></div><section class="inventory-modal-content" role="dialog" aria-modal="true" aria-labelledby="inventory-modal-title"><div class="inventory-modal-heading"><div><p class="panel-kicker">Data baru</p><h2 id="inventory-modal-title">Tambah Inventaris</h2></div><button class="inventory-modal-close" type="button" aria-label="Tutup" data-close-inventory-modal>&times;</button></div><form action="{{ route('admin.inventaris.store') }}" method="post" class="inventory-form">@csrf<div class="inventory-form-grid"><label>Nama Barang<input name="nama_barang" type="text" value="{{ old('nama_barang') }}" required></label><label>Kategori<select name="kategori_inventaris_id" required><option value="">Pilih kategori</option>@foreach ($categories as $category)<option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>@endforeach</select></label><label>Lokasi<select name="lokasi_inventaris_id" required><option value="">Pilih lokasi</option>@foreach ($locations as $location)<option value="{{ $location->id }}">{{ $location->nama_lokasi }}{{ $location->unitPendidikan ? ' - '.$location->unitPendidikan->nama_unit : ' - Belum ditentukan' }}</option>@endforeach</select></label><label>Jumlah<input name="jumlah" type="number" min="1" value="{{ old('jumlah', 1) }}" required></label><label>Kondisi<select name="kondisi" required><option value="">Pilih kondisi</option><option>Baik</option><option>Rusak Ringan</option><option>Rusak Berat</option></select></label><label>Tahun Perolehan<input name="tahun_perolehan" type="number" min="1900" max="{{ now()->year }}" value="{{ old('tahun_perolehan', now()->year) }}" required></label><label>Keterangan<input name="keterangan" value="{{ old('keterangan') }}"></label></div><div class="inventory-form-actions"><button class="inventory-secondary-button" type="button" data-close-inventory-modal>Batal</button><button class="inventory-primary-button" type="submit">Simpan Inventaris</button></div></form></section></div>
                    <section class="inventory-table-panel dashboard-panel"><div class="inventory-toolbar"><div><p class="panel-kicker">Daftar sarana dan prasarana</p><h2>Semua Inventaris <span>{{ $inventories->count() }} data</span></h2></div><form class="inventory-actions" action="{{ route('admin.inventaris') }}" method="get"><input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama barang" aria-label="Cari nama barang"><label class="filter-control"><span>Kategori</span><select name="category"><option value="">Semua Kategori</option>@foreach ($categories as $category)<option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->nama_kategori }}</option>@endforeach</select></label><label class="filter-control"><span>Lokasi</span><select name="location"><option value="">Semua Lokasi</option>@foreach ($locations as $location)<option value="{{ $location->id }}" @selected(request('location') == $location->id)>{{ $location->nama_lokasi }}</option>@endforeach</select></label><label class="filter-control"><span>Kondisi</span><select name="condition"><option value="">Semua Kondisi</option>@foreach (['Baik', 'Rusak Ringan', 'Rusak Berat'] as $condition)<option @selected(request('condition') === $condition)>{{ $condition }}</option>@endforeach</select></label><button class="filter-button" type="submit" aria-label="Terapkan filter">Filter</button></form></div><div class="full-inventory-table inventory-data-table"><table><thead><tr><th><input type="checkbox" aria-label="Pilih semua"></th><th>No</th><th>Nama Barang</th><th>Kategori</th><th>Lokasi</th><th>Jumlah</th><th>Kondisi</th><th>Terakhir Diperbarui</th><th>Aksi</th></tr></thead><tbody></tbody></table></div><div class="inventory-footer"><span>Menampilkan <strong>1-5</strong> dari <strong>0</strong> data</span><div><button type="button" disabled>←</button><button class="current-page" type="button">1</button><button type="button" disabled>→</button></div></div></section>
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

                const inventoryBaseUrl = @json(url('/admin/inventaris'));
                const inventoryForm = document.querySelector('.inventory-form');
                const inventoryFormTitle = inventoryModal?.querySelector('.inventory-modal-heading h2');
                const inventoryFormSubmit = inventoryForm?.querySelector('[type="submit"]');
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                methodInput.disabled = true;
                inventoryForm?.append(methodInput);

                const setInventoryForm = (inventory = null) => {
                    inventoryForm?.reset();
                    inventoryForm.action = inventory ? `${inventoryBaseUrl}/${inventory.id}` : inventoryBaseUrl;
                    methodInput.disabled = !inventory;
                    inventoryFormTitle.textContent = inventory ? 'Ubah Inventaris' : 'Tambah Inventaris';
                    inventoryFormSubmit.textContent = inventory ? 'Simpan Perubahan' : 'Simpan Inventaris';

                    if (inventory) {
                        inventoryForm.elements.namedItem('nama_barang').value = inventory.nama_barang;
                        inventoryForm.elements.namedItem('kategori_inventaris_id').value = inventory.kategori_inventaris_id;
                        inventoryForm.elements.namedItem('lokasi_inventaris_id').value = inventory.lokasi_inventaris_id;
                        inventoryForm.elements.namedItem('jumlah').value = inventory.jumlah;
                        inventoryForm.elements.namedItem('kondisi').value = inventory.kondisi;
                        inventoryForm.elements.namedItem('tahun_perolehan').value = inventory.tahun_perolehan;
                        inventoryForm.elements.namedItem('keterangan').value = inventory.keterangan ?? '';
                    }
                };

                openInventoryModal?.addEventListener('click', () => {
                    setInventoryForm();
                    setInventoryModal(true);
                });
                closeInventoryModal.forEach((button) => button.addEventListener('click', () => setInventoryModal(false)));
                const savedInventories = @json($inventories);
                const canManageInventory = @json($canManage);
                const inventoryTotals = savedInventories.reduce((totals, inventory) => {
                    const quantity = Number(inventory.jumlah) || 0;
                    totals.total += quantity;
                    if (inventory.kondisi === 'Baik') totals.good += quantity;
                    if (inventory.kondisi === 'Rusak Ringan') totals.minor += quantity;
                    if (inventory.kondisi === 'Rusak Berat') totals.major += quantity;
                    return totals;
                }, { total: 0, good: 0, minor: 0, major: 0 });
                document.querySelectorAll('.inventory-summary strong').forEach((value, index) => {
                    value.textContent = [inventoryTotals.total, inventoryTotals.good, inventoryTotals.minor, inventoryTotals.major][index] ?? 0;
                });
                const inventoryCountLabel = document.querySelector('.inventory-toolbar h2 span');
                if (inventoryCountLabel) inventoryCountLabel.textContent = `${savedInventories.length} data`;
                const inventoryBody = document.querySelector('.inventory-data-table tbody');
                document.querySelector('.inventory-data-table thead th:first-child')?.remove();
                const escapeHtml = (value) => String(value).replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[character]);
                if (inventoryBody) inventoryBody.innerHTML = '';
                savedInventories.forEach((inventory, index) => {
                    const row = document.createElement('tr');
                    const conditionClass = inventory.kondisi === 'Baik' ? 'status-good' : (inventory.kondisi === 'Rusak Ringan' ? 'status-minor' : 'status-major');
                    const safeId = escapeHtml(inventory.id);
                    row.innerHTML = `<td>${String(index + 1).padStart(2, '0')}</td><td><strong>${escapeHtml(inventory.nama_barang)}</strong><small>${escapeHtml(inventory.kode_barang)}</small></td><td>${escapeHtml(inventory.category)}</td><td>${escapeHtml(inventory.location)}</td><td>${escapeHtml(inventory.jumlah)} unit</td><td><span class="status ${conditionClass}">${escapeHtml(inventory.kondisi)}</span></td><td>${new Date(inventory.updated_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td><td><div class="inventory-row-actions"><button class="row-action" type="button" data-toggle-inventory-actions aria-expanded="false" aria-label="Aksi ${escapeHtml(inventory.nama_barang)}">•••</button><div class="inventory-action-menu" hidden><button type="button" data-edit-inventory="${safeId}">Ubah</button><button type="button" class="inventory-delete-action" data-delete-inventory="${safeId}">Hapus</button></div></div></td>`;
                    const actionMenu = row.querySelector('.inventory-action-menu');
                    const detailButton = document.createElement('button');
                    detailButton.type = 'button';
                    detailButton.dataset.viewInventory = String(inventory.id);
                    detailButton.textContent = 'Detail';
                    actionMenu.prepend(detailButton);
                    if (!canManageInventory) {
                        actionMenu.querySelector('[data-edit-inventory]').remove();
                        actionMenu.querySelector('[data-delete-inventory]').remove();
                    }
                    inventoryBody?.append(row);
                });
                if (inventoryBody && savedInventories.length === 0) {
                    inventoryBody.innerHTML = '<tr><td class="inventory-empty-row" colspan="8">Belum ada data inventaris.</td></tr>';
                }

                const inventoryDetailDialog = document.createElement('dialog');
                inventoryDetailDialog.className = 'inventory-detail-dialog';
                inventoryDetailDialog.innerHTML = '<form method="dialog"><div class="inventory-modal-heading"><h2>Detail Inventaris</h2><button class="inventory-modal-close" type="submit" aria-label="Tutup">&times;</button></div><dl><div><dt>Kode Barang</dt><dd data-detail-code></dd></div><div><dt>Nama Barang</dt><dd data-detail-name></dd></div><div><dt>Kategori</dt><dd data-detail-category></dd></div><div><dt>Lokasi</dt><dd data-detail-location></dd></div><div><dt>Jumlah</dt><dd data-detail-quantity></dd></div><div><dt>Kondisi</dt><dd data-detail-condition></dd></div></dl></form>';
                document.body.append(inventoryDetailDialog);

                const inventoryRows = Array.from(inventoryBody?.querySelectorAll('tr') ?? []).filter((row) => !row.querySelector('.inventory-empty-row'));
                const inventoryFooter = document.querySelector('.inventory-footer');
                const inventoryRange = inventoryFooter?.querySelector('span');
                const paginationNav = inventoryFooter?.querySelector('div');
                const rowsPerPage = 10;
                const pageCount = Math.max(1, Math.ceil(inventoryRows.length / rowsPerPage));
                let currentPage = 1;

                const renderInventoryPage = (page) => {
                    currentPage = Math.max(1, Math.min(page, pageCount));
                    const firstIndex = (currentPage - 1) * rowsPerPage;
                    inventoryRows.forEach((row, index) => {
                        row.hidden = index < firstIndex || index >= firstIndex + rowsPerPage;
                    });

                    if (inventoryRange) {
                        const firstItem = inventoryRows.length ? firstIndex + 1 : 0;
                        const lastItem = Math.min(firstIndex + rowsPerPage, inventoryRows.length);
                        inventoryRange.innerHTML = `Menampilkan <strong>${firstItem}-${lastItem}</strong> dari <strong>${inventoryRows.length}</strong> data`;
                    }

                    if (!paginationNav) return;
                    paginationNav.innerHTML = '';

                    const addPageButton = (label, page, options = {}) => {
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.textContent = label;
                        button.disabled = Boolean(options.disabled);
                        if (options.current) {
                            button.className = 'current-page';
                            button.setAttribute('aria-current', 'page');
                        }
                        button.addEventListener('click', () => renderInventoryPage(page));
                        paginationNav.append(button);
                    };

                    addPageButton('←', currentPage - 1, { disabled: currentPage === 1 });
                    const firstVisiblePage = Math.max(1, Math.min(currentPage - 1, pageCount - 2));
                    const lastVisiblePage = Math.min(pageCount, firstVisiblePage + 2);

                    if (firstVisiblePage > 1) {
                        addPageButton('1', 1);
                        if (firstVisiblePage > 2) paginationNav.append('…');
                    }

                    for (let page = firstVisiblePage; page <= lastVisiblePage; page += 1) {
                        addPageButton(String(page), page, { current: page === currentPage });
                    }

                    if (lastVisiblePage < pageCount) {
                        if (lastVisiblePage < pageCount - 1) paginationNav.append('…');
                        addPageButton(String(pageCount), pageCount);
                    }

                    addPageButton('→', currentPage + 1, { disabled: currentPage === pageCount });
                };

                renderInventoryPage(1);

                const closeInventoryMenus = () => {
                    document.querySelectorAll('.inventory-action-menu').forEach((menu) => {
                        menu.hidden = true;
                        menu.parentElement?.querySelector('[data-toggle-inventory-actions]')?.setAttribute('aria-expanded', 'false');
                    });
                };

                inventoryBody?.addEventListener('click', (event) => {
                    const toggleButton = event.target.closest('[data-toggle-inventory-actions]');
                    if (toggleButton) {
                        const menu = toggleButton.nextElementSibling;
                        const shouldOpen = menu.hidden;
                        closeInventoryMenus();
                        if (shouldOpen) {
                            menu.hidden = false;
                            toggleButton.setAttribute('aria-expanded', 'true');
                            const buttonRect = toggleButton.getBoundingClientRect();
                            const menuWidth = menu.offsetWidth;
                            const menuHeight = menu.offsetHeight;
                            menu.style.left = `${Math.max(8, Math.min(buttonRect.right - menuWidth, window.innerWidth - menuWidth - 8))}px`;
                            menu.style.top = `${buttonRect.bottom + menuHeight + 8 < window.innerHeight ? buttonRect.bottom + 4 : Math.max(8, buttonRect.top - menuHeight - 4)}px`;
                        }
                        return;
                    }

                    const editButton = event.target.closest('[data-edit-inventory]');
                    const detailButton = event.target.closest('[data-view-inventory]');
                    if (detailButton) {
                        const inventory = savedInventories.find((item) => String(item.id) === detailButton.dataset.viewInventory);
                        if (inventory) {
                            inventoryDetailDialog.querySelector('[data-detail-code]').textContent = inventory.kode_barang;
                            inventoryDetailDialog.querySelector('[data-detail-name]').textContent = inventory.nama_barang;
                            inventoryDetailDialog.querySelector('[data-detail-category]').textContent = inventory.category;
                            inventoryDetailDialog.querySelector('[data-detail-location]').textContent = inventory.location;
                            inventoryDetailDialog.querySelector('[data-detail-quantity]').textContent = `${inventory.jumlah} unit`;
                            inventoryDetailDialog.querySelector('[data-detail-condition]').textContent = inventory.kondisi;
                            inventoryDetailDialog.showModal();
                        }
                        closeInventoryMenus();
                        return;
                    }

                    if (editButton) {
                        setInventoryForm({
                            id: editButton.dataset.editInventory,
                            ...savedInventories.find((item) => String(item.id) === editButton.dataset.editInventory),
                        });
                        closeInventoryMenus();
                        setInventoryModal(true);
                        return;
                    }

                    const deleteButton = event.target.closest('[data-delete-inventory]');
                    if (deleteButton && window.confirm('Hapus data inventaris ini? Tindakan ini tidak dapat dibatalkan.')) {
                        const deleteForm = document.createElement('form');
                        deleteForm.method = 'POST';
                        deleteForm.action = `${inventoryBaseUrl}/${deleteButton.dataset.deleteInventory}`;
                        deleteForm.innerHTML = `<input type="hidden" name="_token" value="${@json(csrf_token())}"><input type="hidden" name="_method" value="DELETE">`;
                        document.body.append(deleteForm);
                        deleteForm.submit();
                    }
                });

                document.addEventListener('click', (event) => {
                    if (!event.target.closest('.inventory-row-actions')) closeInventoryMenus();
                });
                window.addEventListener('scroll', closeInventoryMenus, true);
                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        setInventoryModal(false);
                        closeInventoryMenus();
                    }
                });
            </script>
    </body>
</html>