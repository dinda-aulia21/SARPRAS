<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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

Route::get('/admin', function () {
    return view('admin');
})->middleware('auth')->name('admin.dashboard');

Route::get('/admin/inventaris', function () {
    return view('inventaris', [
        'inventories' => Inventory::latest()->get(),
    ]);
})->middleware('auth')->name('admin.inventaris');

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

    return redirect()->intended(route('admin.dashboard'));
})->name('login.authenticate');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');
