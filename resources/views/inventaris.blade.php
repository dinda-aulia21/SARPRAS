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
                <a class="admin-brand" href="{{ auth()->user()->role === 'kepala_yayasan' ? route('kepala-yayasan.dashboard') : (auth()->user()->role === 'kepala_sekolah' ? route('kepala-sekolah.dashboard') : route('admin.dashboard')) }}"><span class="brand-mark" aria-hidden="true">R</span><span>Yayasan Raudhah<br>Syarifah</span></a>
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
                        inventoryForm.elements.namedItem('name').value = inventory.name;
                        inventoryForm.elements.namedItem('category').value = inventory.category;
                        inventoryForm.elements.namedItem('location').value = inventory.location;
                        inventoryForm.elements.namedItem('quantity').value = inventory.quantity;
                        inventoryForm.elements.namedItem('condition').value = inventory.condition;
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
                    const quantity = Number(inventory.quantity) || 0;
                    totals.total += quantity;
                    if (inventory.condition === 'Baik') totals.good += quantity;
                    if (inventory.condition === 'Rusak Ringan') totals.minor += quantity;
                    if (inventory.condition === 'Rusak Berat') totals.major += quantity;
                    return totals;
                }, { total: 0, good: 0, minor: 0, major: 0 });
                document.querySelectorAll('.inventory-summary strong').forEach((value, index) => {
                    value.textContent = [inventoryTotals.total, inventoryTotals.good, inventoryTotals.minor, inventoryTotals.major][index] ?? 0;
                });
                const inventoryCountLabel = document.querySelector('.inventory-toolbar h2 span');
                if (inventoryCountLabel) inventoryCountLabel.textContent = `${savedInventories.length} data`;
                const inventoryBody = document.querySelector('.full-inventory-table tbody');
                document.querySelector('.full-inventory-table thead th:first-child')?.remove();
                const escapeHtml = (value) => String(value).replace(/[&<>'"]/g, (character) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' })[character]);
                if (inventoryBody) inventoryBody.innerHTML = '';
                savedInventories.forEach((inventory, index) => {
                    const row = document.createElement('tr');
                    const conditionClass = inventory.condition === 'Baik' ? 'status-good' : (inventory.condition === 'Rusak Ringan' ? 'status-minor' : 'status-major');
                    const safeId = escapeHtml(inventory.id);
                    row.innerHTML = `<td>${String(index + 1).padStart(2, '0')}</td><td><strong>${escapeHtml(inventory.name)}</strong><small>INV-${safeId}</small></td><td>${escapeHtml(inventory.category)}</td><td>${escapeHtml(inventory.location)}</td><td>${escapeHtml(inventory.quantity)} unit</td><td><span class="status ${conditionClass}">${escapeHtml(inventory.condition)}</span></td><td>${new Date(inventory.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}</td><td><div class="inventory-row-actions"><button class="row-action" type="button" data-toggle-inventory-actions aria-expanded="false" aria-label="Aksi ${escapeHtml(inventory.name)}">•••</button><div class="inventory-action-menu" hidden><button type="button" data-edit-inventory="${safeId}" data-name="${escapeHtml(inventory.name)}" data-category="${escapeHtml(inventory.category)}" data-location="${escapeHtml(inventory.location)}" data-quantity="${escapeHtml(inventory.quantity)}" data-condition="${escapeHtml(inventory.condition)}">Ubah</button><button type="button" class="inventory-delete-action" data-delete-inventory="${safeId}">Hapus</button></div></div></td>`;
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
                            inventoryDetailDialog.querySelector('[data-detail-code]').textContent = `INV-${inventory.id}`;
                            inventoryDetailDialog.querySelector('[data-detail-name]').textContent = inventory.name;
                            inventoryDetailDialog.querySelector('[data-detail-category]').textContent = inventory.category;
                            inventoryDetailDialog.querySelector('[data-detail-location]').textContent = inventory.location;
                            inventoryDetailDialog.querySelector('[data-detail-quantity]').textContent = `${inventory.quantity} unit`;
                            inventoryDetailDialog.querySelector('[data-detail-condition]').textContent = inventory.condition;
                            inventoryDetailDialog.showModal();
                        }
                        closeInventoryMenus();
                        return;
                    }

                    if (editButton) {
                        setInventoryForm({
                            id: editButton.dataset.editInventory,
                            name: editButton.dataset.name,
                            category: editButton.dataset.category,
                            location: editButton.dataset.location,
                            quantity: editButton.dataset.quantity,
                            condition: editButton.dataset.condition,
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