<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use App\Models\Announcement;
use App\Models\Inventory;

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

$executiveDashboard = function (Request $request, string $dashboardTitle) {
    $totalQuantity = Inventory::sum('quantity');
    $conditionTotals = Inventory::query()
        ->selectRaw('`condition` as condition_value, SUM(quantity) as total')
        ->groupBy('condition')
        ->pluck('total', 'condition_value');
    $categorySummaries = Inventory::query()
        ->selectRaw('category, SUM(quantity) as total')
        ->groupBy('category')
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
            ->selectRaw('location, SUM(quantity) as total')
            ->groupBy('location')
            ->orderByDesc('total')
            ->take(3)
            ->get(),
        'recentInventories' => Inventory::latest()->take(5)->get(),
        'recentAnnouncements' => Announcement::query()
            ->where('status', 'aktif')
            ->whereDate('tanggal_mulai', '<=', today())
            ->where(fn ($query) => $query->whereNull('tanggal_selesai')->orWhereDate('tanggal_selesai', '>=', today()))
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('id')
            ->take(3)
            ->get(),
    ]);
};

Route::get('/admin', function () {
    return view('admin');
})->middleware('auth')->name('admin.dashboard');

Route::get('/kepala-yayasan', function (Request $request) use ($executiveDashboard) {
    abort_unless($request->user()->role === 'kepala_yayasan', 403);

    return $executiveDashboard($request, 'Kepala Yayasan');
})->middleware('auth')->name('kepala-yayasan.dashboard');

Route::get('/kepala-sekolah', function (Request $request) use ($executiveDashboard) {
    abort_unless($request->user()->role === 'kepala_sekolah', 403);

    return $executiveDashboard($request, 'Kepala Sekolah');
})->middleware('auth')->name('kepala-sekolah.dashboard');

Route::get('/admin/inventaris', function () {
    return view('inventaris', [
        'inventories' => Inventory::latest()->get(),
    ]);
})->middleware('auth')->name('admin.inventaris');

Route::get('/admin/laporan', function (Request $request) {
    $type = $request->query('type', 'inventaris');
    $types = ['inventaris', 'kondisi', 'cetak'];

    if (! in_array($type, $types, true)) {
        $type = 'inventaris';
    }

    $inventories = Inventory::query()
        ->when($request->filled('year'), fn ($query) => $query->whereYear('created_at', $request->input('year')))
        ->when($request->filled('category'), fn ($query) => $query->where('category', $request->input('category')))
        ->when($request->filled('location'), fn ($query) => $query->where('location', $request->input('location')))
        ->when($request->filled('condition'), fn ($query) => $query->where('condition', $request->input('condition')))
        ->latest()
        ->get();

    $allInventories = Inventory::query()->get();

    return view('laporan', [
        'inventories' => $inventories,
        'years' => $allInventories->pluck('created_at')->filter()->map(fn ($date) => $date->format('Y'))->unique()->sortDesc()->values(),
        'categories' => $allInventories->pluck('category')->unique()->sort()->values(),
        'locations' => $allInventories->pluck('location')->unique()->sort()->values(),
        'type' => $type,
    ]);
})->middleware('auth')->name('admin.laporan');

Route::get('/admin/pengaturan', function (Request $request) {
    return view('pengaturan', ['user' => $request->user()]);
})->middleware('auth')->name('admin.pengaturan');

Route::get('/admin/pengumuman', function (Request $request) {
    $announcements = Announcement::query()
        ->when($request->filled('status') && $request->input('status') !== 'Semua', fn ($query) => $query->where('status', $request->input('status') === 'Aktif' ? 'aktif' : 'arsip'))
        ->when($request->filled('search'), fn ($query) => $query->where(fn ($subquery) => $subquery
            ->where('judul', 'like', '%' . $request->input('search') . '%')
            ->orWhere('konten', 'like', '%' . $request->input('search') . '%')))
        ->orderByDesc('tanggal_mulai')
        ->orderByDesc('id')
        ->paginate(6)
        ->withQueryString();

    return view('pengumuman', [
        'announcements' => $announcements,
        'statusFilter' => $request->input('status', 'Semua'),
        'search' => $request->input('search', ''),
    ]);
})->middleware('auth')->name('admin.pengumuman');

Route::post('/admin/pengumuman', function (Request $request) {
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'body' => ['required', 'string', 'max:5000'],
        'publish_date' => ['required', 'date'],
        'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])],
    ]);

    Announcement::create([...$validated, 'type' => 'umum']);

    return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil ditambahkan.');
})->middleware('auth')->name('admin.pengumuman.store');

Route::put('/admin/pengumuman/{announcement}', function (Request $request, Announcement $announcement) {
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'body' => ['required', 'string', 'max:5000'],
        'publish_date' => ['required', 'date'],
        'status' => ['required', Rule::in(['Aktif', 'Tidak Aktif'])],
    ]);

    $announcement->update($validated);

    return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil diperbarui.');
})->middleware('auth')->name('admin.pengumuman.update');

Route::delete('/admin/pengumuman/{announcement}', function (Announcement $announcement) {
    $announcement->delete();

    return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil dihapus.');
})->middleware('auth')->name('admin.pengumuman.destroy');

Route::put('/admin/pengaturan/profil', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($request->user()->id)],
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

Route::post('/admin/inventaris', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'category' => ['required', 'string', 'max:100'],
        'location' => ['required', 'string', 'max:255'],
        'quantity' => ['required', 'integer', 'min:1'],
        'condition' => ['required', 'in:Baik,Rusak Ringan,Rusak Berat'],
    ]);

    Inventory::create($validated);

    return redirect()->route('admin.inventaris')->with('success', 'Inventaris berhasil ditambahkan.');
})->middleware('auth')->name('admin.inventaris.store');

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
