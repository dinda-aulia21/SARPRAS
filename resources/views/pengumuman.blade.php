<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Kelola pengumuman Yayasan Raudhah Syarifah.">
        <title>Pengumuman | Sarpras</title>
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
                    <a class="admin-nav-link" href="{{ route('admin.inventaris') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16v13H4zM4 7l2-4h12l2 4M9 11h6M8 3v4M16 3v4"/></svg>Data Inventaris</a>
                    <a class="admin-nav-link" href="{{ route('admin.laporan') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 3h14v18H5zM8 7h8M8 11h8M8 15h5"/></svg>Laporan</a>
                    <a class="admin-nav-link active" href="{{ route('admin.pengumuman') }}" aria-current="page"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 11 18-5v12L3 13v-2Z"/><path d="m7 14 2 6h4l-3-7M21 10v4"/></svg>Pengumuman</a>
                    <a class="admin-nav-link" href="{{ route('admin.pengaturan') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/><circle cx="9" cy="6" r="2"/><circle cx="15" cy="12" r="2"/><circle cx="8" cy="18" r="2"/></svg>Pengaturan</a>
                </nav>
                <div class="admin-sidebar-footer">
                    <div class="admin-profile"><span class="profile-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span><strong>{{ auth()->user()->name }}</strong><small>{{ $userRoleLabel }}</small></span></div>
                    <form class="admin-logout-form" action="{{ route('logout') }}" method="post">@csrf<button class="admin-logout" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3M19 3h2v18h-2"/></svg>Keluar</button></form>
                </div>
            </aside>
            <main class="admin-main">
                <header class="admin-topbar">
                    <button class="sidebar-toggle" type="button" aria-controls="admin-sidebar" aria-expanded="false" aria-label="Buka navigasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
                    <div class="admin-search"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/></svg><input type="search" placeholder="Cari inventaris, kategori, atau lokasi..." aria-label="Cari inventaris"></div>
                    <div class="topbar-actions"><button class="icon-button notification-button" type="button" aria-label="Notifikasi"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg><span></span></button><div class="date-label"><strong>{{ now()->translatedFormat('l, d F Y') }}</strong><small>{{ now()->format('H:i') }} WIB</small></div><button class="account-button" type="button"><span class="mini-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span><span>{{ auth()->user()->name }}</span><span class="chevron">⌄</span></button></div>
                </header>
                <div class="admin-content inventory-page-content announcements-page-content">
                    <div class="page-heading"><div><p class="welcome-eyebrow">Pusat informasi</p><h1>Pengumuman</h1><p>Kelola informasi dan pengumuman terkait sarana dan prasarana yayasan.</p></div>@if ($canManage)<button class="announcement-add-button" type="button" data-open-announcement><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>Tambah Pengumuman</button>@endif</div>
                    @if (session('success'))<div class="inventory-alert" role="status">{{ session('success') }}</div>@endif
                    @if ($errors->any())<div class="inventory-alert inventory-alert-error" role="alert">{{ $errors->first() }}</div>@endif
                    <section class="announcement-table-panel dashboard-panel">
                        <div class="announcement-toolbar">
                            <nav class="announcement-tabs" aria-label="Filter status pengumuman">
                                @foreach (['Semua', 'Aktif', 'Tidak Aktif'] as $status)
                                    <a class="announcement-tab {{ $statusFilter === $status ? 'is-active' : '' }}" href="{{ route('admin.pengumuman', array_filter(['status' => $status === 'Semua' ? null : $status, 'search' => $search])) }}" @if ($statusFilter === $status) aria-current="page" @endif>{{ $status }}</a>
                                @endforeach
                            </nav>
                            <form class="announcement-search-form" action="{{ route('admin.pengumuman') }}" method="get">
                                @if ($statusFilter !== 'Semua')<input type="hidden" name="status" value="{{ $statusFilter }}">@endif
                                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m16 16 4 4"/></svg>
                                <input type="search" name="search" value="{{ $search }}" placeholder="Cari judul pengumuman..." aria-label="Cari judul pengumuman">
                            </form>
                        </div>
                        <div class="announcement-table-wrap"><table class="announcement-table"><thead><tr><th>No</th><th>Judul</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
                            @forelse ($announcements as $index => $announcement)
                                <tr>
                                    <td>{{ str_pad($announcements->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td class="announcement-title-cell"><strong>{{ $announcement->title }}</strong><small>{{ \Illuminate\Support\Str::limit($announcement->body, 115) }}</small></td>
                                    <td class="announcement-date-cell"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>{{ $announcement->publish_date->translatedFormat('d F Y') }}</td>
                                    <td><span class="announcement-status {{ $announcement->status === 'Aktif' ? 'is-active' : 'is-inactive' }}">{{ $announcement->status }}</span></td>
                                    <td><div class="announcement-row-actions">
                                        <button class="announcement-icon-button view-announcement-button" type="button" data-view-announcement data-title="{{ $announcement->title }}" data-body="{{ $announcement->body }}" data-date="{{ $announcement->publish_date->translatedFormat('d F Y') }}" data-status="{{ $announcement->status }}" aria-label="Lihat {{ $announcement->title }}" title="Lihat"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="2.5"/></svg></button>
                                        @if ($canManage)
                                            <button class="announcement-icon-button edit-announcement-button" type="button" data-edit-announcement data-id="{{ $announcement->id }}" data-title="{{ $announcement->title }}" data-body="{{ $announcement->body }}" data-date="{{ $announcement->publish_date->format('Y-m-d') }}" data-status="{{ $announcement->status }}" aria-label="Edit {{ $announcement->title }}" title="Edit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m14 5 5 5M4 20l4.5-1 10.8-10.8a2.1 2.1 0 0 0-3-3L5.5 16z"/></svg></button>
                                            <form action="{{ route('admin.pengumuman.destroy', $announcement) }}" method="post" onsubmit="return confirm('Hapus pengumuman ini?')">@csrf @method('DELETE')<button class="announcement-icon-button delete-announcement-button" type="submit" aria-label="Hapus {{ $announcement->title }}" title="Hapus"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M10 11v6M14 11v6M5 7l1 14h12l1-14M9 7V4h6v3"/></svg></button></form>
                                        @endif
                                    </div></td>
                                </tr>
                            @empty
                                <tr><td class="announcement-empty-state" colspan="5">Belum ada pengumuman untuk filter ini.</td></tr>
                            @endforelse
                        </tbody></table></div>
                        <div class="announcement-table-footer"><span>Menampilkan {{ $announcements->firstItem() ?? 0 }}–{{ $announcements->lastItem() ?? 0 }} dari {{ $announcements->total() }} data</span>{{ $announcements->onEachSide(1)->links() }}</div>
                    </section>
                </div>
            </main>
        </div>

        <div class="announcement-modal" data-announcement-form-modal aria-hidden="true"><div class="announcement-modal-backdrop" data-close-announcement></div><section class="announcement-modal-content" role="dialog" aria-modal="true" aria-labelledby="announcement-form-title"><div class="announcement-modal-heading"><div><p class="panel-kicker">Pusat informasi</p><h2 id="announcement-form-title">Tambah Pengumuman</h2></div><button class="announcement-modal-close" type="button" data-close-announcement aria-label="Tutup">&times;</button></div><form class="announcement-form" action="{{ route('admin.pengumuman.store') }}" method="post" data-announcement-form>@csrf<input type="hidden" name="announcement_id" value="{{ old('announcement_id') }}"><div class="announcement-form-grid"><label for="announcement-title">Judul Pengumuman<input id="announcement-title" name="title" type="text" value="{{ old('title') }}" maxlength="255" required></label><label for="announcement-date">Tanggal<div class="announcement-date-picker" data-date-picker><input id="announcement-date" name="publish_date" type="hidden" value="{{ old('publish_date', now()->format('Y-m-d')) }}"><button class="announcement-date-trigger" type="button" aria-haspopup="dialog" aria-expanded="false" aria-label="Pilih tanggal"><span data-date-label></span><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg></button><div class="announcement-date-popover" role="dialog" aria-label="Pilih tanggal" hidden><div class="announcement-date-header"><button type="button" data-date-previous aria-label="Bulan sebelumnya">‹</button><strong data-date-heading></strong><button type="button" data-date-next aria-label="Bulan berikutnya">›</button></div><div class="announcement-date-weekdays" aria-hidden="true"><span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span></div><div class="announcement-date-days" data-date-days></div><div class="announcement-date-footer"><button type="button" data-date-today>Hari ini</button></div></div></div></label><label for="announcement-status">Status<select id="announcement-status" name="status" required><option value="Aktif" @selected(old('status', 'Aktif') === 'Aktif')>Aktif</option><option value="Tidak Aktif" @selected(old('status') === 'Tidak Aktif')>Tidak Aktif</option></select></label><label class="announcement-body-field" for="announcement-body">Isi Pengumuman<textarea id="announcement-body" name="body" rows="5" maxlength="5000" required>{{ old('body') }}</textarea></label></div><div class="announcement-form-actions"><button class="announcement-cancel-button" type="button" data-close-announcement>Batal</button><button class="announcement-add-button" type="submit">Simpan Pengumuman</button></div></form></section></div>
        <div class="announcement-modal" data-announcement-detail-modal aria-hidden="true"><div class="announcement-modal-backdrop" data-close-announcement-detail></div><section class="announcement-modal-content announcement-detail-content" role="dialog" aria-modal="true" aria-labelledby="announcement-detail-title"><div class="announcement-modal-heading"><div><p class="panel-kicker">Detail pengumuman</p><h2 id="announcement-detail-title"></h2></div><button class="announcement-modal-close" type="button" data-close-announcement-detail aria-label="Tutup">&times;</button></div><div class="announcement-detail-meta"><span data-detail-date></span><span data-detail-status></span><span data-detail-author></span></div><p class="announcement-detail-body" data-detail-body></p></section></div>

        <script>
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.querySelector('.admin-sidebar');
            sidebarToggle?.addEventListener('click', () => {
                const isOpen = sidebar.classList.toggle('is-open');
                sidebarToggle.setAttribute('aria-expanded', String(isOpen));
            });

            const formModal = document.querySelector('[data-announcement-form-modal]');
            const form = document.querySelector('[data-announcement-form]');
            const originalAction = form.action;
            const originalForm = form.innerHTML;
            const initializeDatePicker = () => {
                const picker = form.querySelector('[data-date-picker]');
                if (!picker) return;

                const input = picker.querySelector('#announcement-date');
                const trigger = picker.querySelector('.announcement-date-trigger');
                const label = picker.querySelector('[data-date-label]');
                const popover = picker.querySelector('.announcement-date-popover');
                const heading = picker.querySelector('[data-date-heading]');
                const days = picker.querySelector('[data-date-days]');
                const monthFormatter = new Intl.DateTimeFormat('id-ID', { month: 'long', year: 'numeric' });
                const dateFormatter = new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
                const today = new Date();
                const toIsoDate = (date) => {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                };
                const parsedDate = /^\d{4}-\d{2}-\d{2}$/.test(input.value)
                    ? new Date(`${input.value}T00:00:00`)
                    : null;
                let selectedDate = parsedDate && toIsoDate(parsedDate) === input.value
                    ? parsedDate
                    : new Date(today.getFullYear(), today.getMonth(), today.getDate());
                let visibleMonth = new Date(selectedDate.getFullYear(), selectedDate.getMonth(), 1);
                const updateSelection = (date) => {
                    selectedDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
                    input.value = toIsoDate(selectedDate);
                    label.textContent = dateFormatter.format(selectedDate);
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                };
                const renderCalendar = () => {
                    heading.textContent = monthFormatter.format(visibleMonth);
                    days.replaceChildren();
                    const firstWeekday = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth(), 1).getDay();
                    const daysInMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() + 1, 0).getDate();
                    const daysInPreviousMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth(), 0).getDate();
                    const todayIso = toIsoDate(today);

                    for (let index = 0; index < 42; index += 1) {
                        const dayNumber = index - firstWeekday + 1;
                        const date = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth(), dayNumber);
                        const button = document.createElement('button');
                        const isCurrentMonth = dayNumber > 0 && dayNumber <= daysInMonth;
                        button.type = 'button';
                        button.className = 'announcement-date-day';
                        button.textContent = String(
                            dayNumber < 1 ? daysInPreviousMonth + dayNumber :
                                dayNumber > daysInMonth ? dayNumber - daysInMonth : dayNumber
                        );
                        button.setAttribute('aria-label', dateFormatter.format(date));
                        if (!isCurrentMonth) button.classList.add('is-outside-month');
                        if (toIsoDate(date) === toIsoDate(selectedDate)) button.classList.add('is-selected');
                        if (toIsoDate(date) === todayIso) button.classList.add('is-today');
                        button.addEventListener('click', () => {
                            updateSelection(date);
                            closeCalendar();
                            trigger.focus();
                        });
                        days.append(button);
                    }
                };
                const closeCalendar = () => {
                    popover.hidden = true;
                    trigger.setAttribute('aria-expanded', 'false');
                };

                updateSelection(selectedDate);
                renderCalendar();
                trigger.addEventListener('click', () => {
                    const isOpening = popover.hidden;
                    popover.hidden = !isOpening;
                    trigger.setAttribute('aria-expanded', String(isOpening));
                    if (isOpening) renderCalendar();
                });
                picker.querySelector('[data-date-previous]').addEventListener('click', () => {
                    visibleMonth.setMonth(visibleMonth.getMonth() - 1);
                    renderCalendar();
                });
                picker.querySelector('[data-date-next]').addEventListener('click', () => {
                    visibleMonth.setMonth(visibleMonth.getMonth() + 1);
                    renderCalendar();
                });
                picker.querySelector('[data-date-today]').addEventListener('click', () => {
                    updateSelection(today);
                    visibleMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                    renderCalendar();
                    closeCalendar();
                    trigger.focus();
                });
                picker.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && !popover.hidden) {
                        closeCalendar();
                        trigger.focus();
                    }
                });
            };
            initializeDatePicker();
            const closeForm = () => {
                formModal.classList.remove('is-visible');
                formModal.setAttribute('aria-hidden', 'true');
            };
            const openForm = () => {
                formModal.classList.add('is-visible');
                formModal.setAttribute('aria-hidden', 'false');
                document.querySelector('#announcement-title').focus();
            };
            document.querySelector('[data-open-announcement]')?.addEventListener('click', () => {
                form.action = originalAction;
                form.innerHTML = originalForm;
                initializeDatePicker();
                document.querySelector('#announcement-form-title').textContent = 'Tambah Pengumuman';
                openForm();
            });
            formModal.querySelectorAll('[data-close-announcement]').forEach((button) => {
                button.addEventListener('click', closeForm);
            });
            document.addEventListener('click', (event) => {
                const activePicker = form.querySelector('[data-date-picker]');
                if (!activePicker || activePicker.contains(event.target)) return;
                const popover = activePicker.querySelector('.announcement-date-popover');
                popover.hidden = true;
                activePicker.querySelector('.announcement-date-trigger').setAttribute('aria-expanded', 'false');
            });
            document.querySelectorAll('[data-edit-announcement]').forEach((button) => button.addEventListener('click', () => {
                form.action = `${originalAction}/${button.dataset.id}`;
                form.innerHTML = originalForm;
                document.querySelector('#announcement-form-title').textContent = 'Edit Pengumuman';
                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'PUT';
                form.prepend(method);
                form.querySelector('[name="announcement_id"]').value = button.dataset.id;
                form.querySelector('#announcement-title').value = button.dataset.title;
                form.querySelector('#announcement-body').value = button.dataset.body;
                form.querySelector('#announcement-date').value = button.dataset.date;
                initializeDatePicker();
                form.querySelector('#announcement-status').value = button.dataset.status;
                openForm();
            }));

            const detailModal = document.querySelector('[data-announcement-detail-modal]');
            const closeDetail = () => {
                detailModal.classList.remove('is-visible');
                detailModal.setAttribute('aria-hidden', 'true');
            };
            document.querySelectorAll('[data-close-announcement-detail]').forEach((button) => button.addEventListener('click', closeDetail));
            document.querySelectorAll('[data-view-announcement]').forEach((button) => button.addEventListener('click', () => {
                document.querySelector('#announcement-detail-title').textContent = button.dataset.title;
                document.querySelector('[data-detail-body]').textContent = button.dataset.body;
                document.querySelector('[data-detail-date]').textContent = button.dataset.date;
                document.querySelector('[data-detail-status]').textContent = button.dataset.status;
                document.querySelector('[data-detail-author]').textContent = 'Administrator';
                detailModal.classList.add('is-visible');
                detailModal.setAttribute('aria-hidden', 'false');
            }));
            @if ($errors->any())
                const failedAnnouncementId = form.querySelector('[name="announcement_id"]').value;
                if (failedAnnouncementId) {
                    form.action = `${originalAction}/${failedAnnouncementId}`;
                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'PUT';
                    form.prepend(method);
                    document.querySelector('#announcement-form-title').textContent = 'Edit Pengumuman';
                }
                openForm();
            @endif
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeForm();
                    closeDetail();
                }
            });
        </script>
    </body>
</html>