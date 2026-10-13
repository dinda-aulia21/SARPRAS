<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use App\Models\Announcement;
use App\Models\KategoriInventaris;
use App\Models\Inventory;
use App\Models\LokasiInventaris;
use App\Models\UnitPendidikan;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

$scopeToUserUnit = function ($query, User $user) {
    if ($user->role !== 'kepala_sekolah' || $user->unit_pendidikan_id === null) {
        return $query;
    }

    return $query->whereHas('lokasiInventaris', fn ($locations) => $locations
        ->where('unit_pendidikan_id', $user->unit_pendidikan_id));
};

$executiveDashboard = function (Request $request, string $dashboardTitle) use ($scopeToUserUnit) {
    $inventories = Inventory::query()
        ->with(['kategoriInventaris', 'lokasiInventaris.unitPendidikan'])
        ->tap(fn ($query) => $scopeToUserUnit($query, $request->user()));
    $totalQuantity = (clone $inventories)->sum('jumlah');
    $conditionTotals = Inventory::query()->tap(fn ($query) => $scopeToUserUnit($query, $request->user()))
        ->selectRaw('kondisi as condition_value, SUM(jumlah) as total')
        ->groupBy('kondisi')
        ->pluck('total', 'condition_value');
    $categorySummaries = Inventory::query()->tap(fn ($query) => $scopeToUserUnit($query, $request->user()))
        ->join('kategori_inventaris', 'data_inventaris.kategori_inventaris_id', '=', 'kategori_inventaris.id')
        ->selectRaw('kategori_inventaris.nama_kategori as category, SUM(data_inventaris.jumlah) as total')
        ->groupBy('kategori_inventaris.nama_kategori')
        ->orderByDesc('total')
        ->get();
    $categoryColors = ['#3288d1', '#15936e', '#f1bd35', '#8158c6', '#e56983'];
    $categoryStops = [];
    $categoryOffset = 0;

    foreach ($categorySummaries as $index => $category) {
        $percentage = $totalQuantity > 0 ? ($category->total / $totalQuantity) * 100 : 0;
        $nextOffset = $categoryOffset + $percentage;
        $color = $categoryColors[$index % count($categoryColors)];
        $categoryStops[] = sprintf('%s %.2f%% %.2f%%', $color, $categoryOffset, $nextOffset);
        $categoryOffset = $nextOffset;
    }

    if ($categoryStops === []) {
        $categoryStops[] = '#dcebe6 0 100%';
    }

    $totalGood = (int) $conditionTotals->get('Baik', 0);
    $totalMinor = (int) $conditionTotals->get('Rusak Ringan', 0);
    $totalMajor = (int) $conditionTotals->get('Rusak Berat', 0);
    $percentageOf = fn (int $value) => $totalQuantity > 0 ? round(($value / $totalQuantity) * 100, 1) : 0;

    return view('kepala-yayasan', [
        'user' => $request->user(),
        'dashboardTitle' => $dashboardTitle,
        'totalQuantity' => $totalQuantity,
        'totalGood' => $totalGood,
        'totalMinor' => $totalMinor,
        'totalMajor' => $totalMajor,
        'goodPercentage' => $percentageOf($totalGood),
        'minorPercentage' => $percentageOf($totalMinor),
        'majorPercentage' => $percentageOf($totalMajor),
        'categorySummaries' => $categorySummaries,
        'categoryGradient' => 'conic-gradient(' . implode(', ', $categoryStops) . ')',
        'locationSummaries' => Inventory::query()
            ->tap(fn ($query) => $scopeToUserUnit($query, $request->user()))
            ->join('lokasi_inventaris', 'data_inventaris.lokasi_inventaris_id', '=', 'lokasi_inventaris.id')
            ->selectRaw('lokasi_inventaris.nama_lokasi as location, SUM(data_inventaris.jumlah) as total')
            ->groupBy('lokasi_inventaris.nama_lokasi')
            ->orderByDesc('total')
            ->take(3)
            ->get(),
        'recentInventories' => (clone $inventories)->latest()->take(5)->get(),
        'recentAnnouncements' => Announcement::query()
            ->where('status', 'aktif')
            ->whereDate('tanggal_pengumuman', '<=', today())
            ->orderByDesc('tanggal_pengumuman')
            ->orderByDesc('id')
            ->take(3)
            ->get(),
    ]);
};

$requireAdmin = function (Request $request): void {
    abort_unless($request->user()->role === 'admin', 403);
};

Route::get('/admin', function (Request $request) use ($executiveDashboard) {
    return $executiveDashboard($request, 'Admin');
})->middleware('auth')->name('admin.dashboard');

Route::get('/kepala-yayasan', function (Request $request) use ($executiveDashboard) {
    abort_unless($request->user()->role === 'kepala_yayasan', 403);

    return $executiveDashboard($request, 'Kepala Yayasan');
})->middleware('auth')->name('kepala-yayasan.dashboard');

Route::get('/kepala-sekolah', function (Request $request) use ($executiveDashboard) {
    abort_unless($request->user()->role === 'kepala_sekolah', 403);

    return $executiveDashboard($request, 'Kepala Sekolah');
})->middleware('auth')->name('kepala-sekolah.dashboard');

Route::get('/admin/inventaris', function (Request $request) use ($scopeToUserUnit) {
    $user = $request->user();
    $inventories = Inventory::query()
        ->with(['kategoriInventaris', 'lokasiInventaris.unitPendidikan'])
        ->tap(fn ($query) => $scopeToUserUnit($query, $user))
        ->when($request->filled('search'), fn ($query) => $query->where(fn ($search) => $search
            ->where('nama_barang', 'like', '%' . $request->input('search') . '%')
            ->orWhereHas('kategoriInventaris', fn ($category) => $category->where('nama_kategori', 'like', '%' . $request->input('search') . '%'))
            ->orWhereHas('lokasiInventaris', fn ($location) => $location->where('nama_lokasi', 'like', '%' . $request->input('search') . '%')))
        )
        ->when($request->filled('category'), fn ($query) => $query->where('kategori_inventaris_id', $request->input('category')))
        ->when($request->filled('location'), fn ($query) => $query->where('lokasi_inventaris_id', $request->input('location')))
        ->when($request->filled('condition'), fn ($query) => $query->where('kondisi', $request->input('condition')))
        ->latest()
        ->get();

    return view('inventaris', [
        'inventories' => $inventories,
        'categories' => KategoriInventaris::orderBy('nama_kategori')->get(),
        'locations' => LokasiInventaris::with('unitPendidikan')
            ->when($user->role === 'kepala_sekolah' && $user->unit_pendidikan_id !== null, fn ($query) => $query->where('unit_pendidikan_id', $user->unit_pendidikan_id))
            ->orderBy('nama_lokasi')->get(),
    ]);
})->middleware('auth')->name('admin.inventaris');

Route::get('/admin/laporan', function (Request $request) use ($scopeToUserUnit) {
    $type = $request->query('type', 'inventaris');
    $types = ['inventaris', 'kondisi', 'cetak'];

    if (! in_array($type, $types, true)) {
        $type = 'inventaris';
    }

    $user = $request->user();
    $inventories = Inventory::query()
        ->with(['kategoriInventaris', 'lokasiInventaris.unitPendidikan'])
        ->tap(fn ($query) => $scopeToUserUnit($query, $user))
        ->when($request->filled('year'), fn ($query) => $query->where('tahun_perolehan', $request->input('year')))
        ->when($request->filled('category'), fn ($query) => $query->where('kategori_inventaris_id', $request->input('category')))
        ->when($request->filled('location'), fn ($query) => $query->where('lokasi_inventaris_id', $request->input('location')))
        ->when($request->filled('condition'), fn ($query) => $query->where('kondisi', $request->input('condition')))
        ->latest()
        ->get();

    $allInventories = Inventory::query()
        ->with(['kategoriInventaris', 'lokasiInventaris.unitPendidikan'])
        ->tap(fn ($query) => $scopeToUserUnit($query, $user))
        ->get();

    return view('laporan', [
        'inventories' => $inventories,
        'years' => $allInventories->pluck('tahun_perolehan')->filter()->unique()->sortDesc()->values(),
        'categories' => KategoriInventaris::orderBy('nama_kategori')->get(),
        'locations' => LokasiInventaris::with('unitPendidikan')
            ->when($user->role === 'kepala_sekolah' && $user->unit_pendidikan_id !== null, fn ($query) => $query->where('unit_pendidikan_id', $user->unit_pendidikan_id))
            ->orderBy('nama_lokasi')->get(),
        'type' => $type,
    ]);
})->middleware('auth')->name('admin.laporan');

Route::get('/admin/pengaturan', function (Request $request) {
    return view('pengaturan', ['user' => $request->user()]);
})->middleware('auth')->name('admin.pengaturan');

Route::get('/admin/pengumuman', function (Request $request) {
    $announcements = Announcement::query()
        ->when($request->filled('status') && $request->input('status') !== 'Semua', fn ($query) => $query->where('status', $request->input('status') === 'Aktif' ? 'aktif' : 'tidak_aktif'))
        ->when($request->filled('search'), fn ($query) => $query->where(fn ($subquery) => $subquery
            ->where('judul', 'like', '%' . $request->input('search') . '%')
        ->orWhere('isi_pengumuman', 'like', '%' . $request->input('search') . '%')))
        ->orderByDesc('tanggal_pengumuman')
        ->orderByDesc('id')
        ->paginate(6)
        ->withQueryString();

    return view('pengumuman', [
        'announcements' => $announcements,
        'statusFilter' => $request->input('status', 'Semua'),
        'search' => $request->input('search', ''),
    ]);
})->middleware('auth')->name('admin.pengumuman');

Route::post('/admin/pengumuman', function (Request $request) use ($requireAdmin) {
    $requireAdmin($request);

    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'body' => ['required', 'string', 'max:5000'],
        'publish_date' => ['required', 'date'],
        'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])],
    ]);

    Announcement::create([
        'pengguna_id' => $request->user()->id,
        ...$validated,
        'type' => 'umum',
    ]);

    return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil ditambahkan.');
})->middleware('auth')->name('admin.pengumuman.store');

Route::put('/admin/pengumuman/{announcement}', function (Request $request, Announcement $announcement) use ($requireAdmin) {
    $requireAdmin($request);

    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'body' => ['required', 'string', 'max:5000'],
        'publish_date' => ['required', 'date'],
        'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])],
    ]);

    $announcement->update($validated);

    return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil diperbarui.');
})->middleware('auth')->name('admin.pengumuman.update');

Route::delete('/admin/pengumuman/{announcement}', function (Request $request, Announcement $announcement) use ($requireAdmin) {
    $requireAdmin($request);

    $announcement->delete();

    return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil dihapus.');
})->middleware('auth')->name('admin.pengumuman.destroy');

Route::put('/admin/pengaturan/profil', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', Rule::unique('pengguna', 'email')->ignore($request->user()->id)],
    ]);

    $request->user()->update($validated);

    return redirect()->route('admin.pengaturan')->with('profile_success', 'Profil berhasil diperbarui.');
})->middleware('auth')->name('admin.pengaturan.profil');

Route::put('/admin/pengaturan/password', function (Request $request) {
    $validated = $request->validate([
        'current_password' => ['required', 'current_password'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $request->user()->update(['password' => $validated['password']]);

    return redirect()->route('admin.pengaturan')->with('password_success', 'Password berhasil diperbarui.');
})->middleware('auth')->name('admin.pengaturan.password');

Route::put('/admin/pengguna/{schoolHead}/unit', function (Request $request, User $schoolHead) use ($requireAdmin) {
    $requireAdmin($request);
    abort_unless($schoolHead->role === 'kepala_sekolah', 404);

    $validated = $request->validate([
        'unit_pendidikan_id' => ['required', 'exists:unit_pendidikan,id'],
    ]);

    $schoolHead->update($validated);

    return redirect()->route('admin.inventaris')->with('success', 'Unit Kepala Sekolah berhasil diperbarui.');
})->middleware('auth')->name('admin.pengguna.unit.update');

Route::post('/admin/inventaris', function (Request $request) use ($requireAdmin) {
    $requireAdmin($request);

    $validated = $request->validate([
        'nama_barang' => ['required', 'string', 'max:255'],
        'kategori_inventaris_id' => ['required', 'exists:kategori_inventaris,id'],
        'lokasi_inventaris_id' => ['required', 'exists:lokasi_inventaris,id'],
        'jumlah' => ['required', 'integer', 'min:1'],
        'kondisi' => ['required', 'in:Baik,Rusak Ringan,Rusak Berat'],
        'tahun_perolehan' => ['required', 'integer', 'min:1900', 'max:' . now()->year],
        'keterangan' => ['nullable', 'string'],
    ]);

    Inventory::create($validated);

    return redirect()->route('admin.inventaris')->with('success', 'Inventaris berhasil ditambahkan.');
})->middleware('auth')->name('admin.inventaris.store');

Route::put('/admin/inventaris/{inventory}', function (Request $request, Inventory $inventory) use ($requireAdmin) {
    $requireAdmin($request);

    $validated = $request->validate([
        'nama_barang' => ['required', 'string', 'max:255'],
        'kategori_inventaris_id' => ['required', 'exists:kategori_inventaris,id'],
        'lokasi_inventaris_id' => ['required', 'exists:lokasi_inventaris,id'],
        'jumlah' => ['required', 'integer', 'min:1'],
        'kondisi' => ['required', 'in:Baik,Rusak Ringan,Rusak Berat'],
        'tahun_perolehan' => ['required', 'integer', 'min:1900', 'max:' . now()->year],
        'keterangan' => ['nullable', 'string'],
    ]);

    $inventory->update($validated);

    return redirect()->route('admin.inventaris')->with('success', 'Data inventaris berhasil diperbarui.');
})->middleware('auth')->name('admin.inventaris.update');

Route::delete('/admin/inventaris/{inventory}', function (Request $request, Inventory $inventory) use ($requireAdmin) {
    $requireAdmin($request);

    $inventory->delete();

    return redirect()->route('admin.inventaris')->with('success', 'Data inventaris berhasil dihapus.');
})->middleware('auth')->name('admin.inventaris.destroy');

Route::post('/admin/lokasi', function (Request $request) use ($requireAdmin) {
    $requireAdmin($request);

    $validated = $request->validate([
        'unit_pendidikan_id' => ['required', 'exists:unit_pendidikan,id'],
        'nama_lokasi' => ['required', 'string', 'max:255'],
        'keterangan' => ['nullable', 'string'],
    ]);

    LokasiInventaris::create($validated);

    return redirect()->route('admin.inventaris')->with('success', 'Lokasi berhasil ditambahkan.');
})->middleware('auth')->name('admin.lokasi.store');

Route::put('/admin/lokasi/{lokasiInventaris}', function (Request $request, LokasiInventaris $lokasiInventaris) use ($requireAdmin) {
    $requireAdmin($request);

    $validated = $request->validate([
        'unit_pendidikan_id' => ['required', 'exists:unit_pendidikan,id'],
    ]);

    $lokasiInventaris->update($validated);

    return redirect()->route('admin.inventaris')->with('success', 'Unit lokasi berhasil diperbarui.');
})->middleware('auth')->name('admin.lokasi.update');

Route::delete('/admin/lokasi/{lokasiInventaris}', function (Request $request, LokasiInventaris $lokasiInventaris) use ($requireAdmin) {
    $requireAdmin($request);
    abort_if($lokasiInventaris->dataInventaris()->exists(), 422, 'Lokasi yang masih digunakan inventaris tidak dapat dihapus.');

    $lokasiInventaris->delete();

    return redirect()->route('admin.inventaris')->with('success', 'Lokasi berhasil dihapus.');
})->middleware('auth')->name('admin.lokasi.destroy');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    $credentials = request()->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (! Auth::attempt($credentials, true)) {
        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan salah.',
        ])->onlyInput('email');
    }

    request()->session()->regenerate();

    if (Auth::user()->role === 'kepala_yayasan') {
        return redirect()->route('kepala-yayasan.dashboard');
    }

    if (Auth::user()->role === 'kepala_sekolah') {
        return redirect()->route('kepala-sekolah.dashboard');
    }

    return redirect()->intended(route('admin.dashboard'));
})->name('login.authenticate');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');
