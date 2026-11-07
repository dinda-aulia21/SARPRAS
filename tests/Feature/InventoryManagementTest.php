<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\KategoriInventaris;
use App\Models\LokasiInventaris;
use App\Models\UnitPendidikan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_update_inventory(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $inventory = Inventory::create([
            'name' => 'Meja lama',
            'category' => 'Furnitur',
            'location' => 'Ruang Guru',
            'quantity' => 2,
            'condition' => 'Baik',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.inventaris.update', $inventory), [
                'nama_barang' => 'Meja baru',
                'kategori_inventaris_id' => $inventory->kategori_inventaris_id,
                'kategori_nama' => $inventory->kategoriInventaris->nama_kategori,
                'lokasi_inventaris_id' => $inventory->lokasi_inventaris_id,
                'lokasi_nama' => $inventory->lokasiInventaris->nama_lokasi,
                'jumlah' => 4,
                'kondisi' => 'Rusak Ringan',
                'tahun_perolehan' => now()->year,
            ])
            ->assertRedirect(route('admin.inventaris'))
            ->assertSessionHas('success', 'Data inventaris berhasil diperbarui.');

        $this->assertDatabaseHas('data_inventaris', [
            'id' => $inventory->id,
            'nama_barang' => 'Meja baru',
            'jumlah' => 4,
            'kondisi' => 'Rusak Ringan',
        ]);
    }

    public function test_admin_can_delete_inventory(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $inventory = Inventory::create([
            'name' => 'Kursi rusak',
            'category' => 'Furnitur',
            'location' => 'Gudang',
            'quantity' => 1,
            'condition' => 'Rusak Berat',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.inventaris.destroy', $inventory))
            ->assertRedirect(route('admin.inventaris'))
            ->assertSessionHas('success', 'Data inventaris berhasil dihapus.');

        $this->assertDatabaseMissing('data_inventaris', ['id' => $inventory->id]);
    }

    public function test_admin_can_create_inventory_with_normalized_references(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $unit = UnitPendidikan::where('nama_unit', 'SD')->firstOrFail();
        $location = LokasiInventaris::create([
            'unit_pendidikan_id' => $unit->id,
            'nama_lokasi' => 'Ruang Perpustakaan',
        ]);
        $category = KategoriInventaris::firstOrCreate(['nama_kategori' => 'Furnitur']);

        $this->actingAs($admin)
            ->post(route('admin.inventaris.store'), [
                'nama_barang' => 'Rak Buku',
                'kategori_inventaris_id' => $category->id,
                'kategori_nama' => $category->nama_kategori,
                'lokasi_inventaris_id' => $location->id,
                'lokasi_nama' => $location->nama_lokasi . ' - SD',
                'jumlah' => 3,
                'kondisi' => 'Baik',
                'tahun_perolehan' => now()->year,
            ])
            ->assertRedirect(route('admin.inventaris'));

        $this->assertDatabaseHas('data_inventaris', [
            'nama_barang' => 'Rak Buku',
            'kategori_inventaris_id' => $category->id,
            'lokasi_inventaris_id' => $location->id,
            'jumlah' => 3,
            'kondisi' => 'Baik',
            'tahun_perolehan' => now()->year,
        ]);
        $this->assertNotEmpty(Inventory::where('nama_barang', 'Rak Buku')->value('kode_barang'));
    }

    public function test_admin_can_create_inventory_with_custom_category_location_and_condition(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.inventaris.store'), [
                'nama_barang' => 'Keyboard',
                'kategori_nama' => 'Perangkat Komputer',
                'lokasi_nama' => 'Kantor',
                'jumlah' => 1,
                'kondisi' => 'Perlu diperiksa',
                'tahun_perolehan' => now()->year,
            ])
            ->assertRedirect(route('admin.inventaris'));

        $category = KategoriInventaris::where('nama_kategori', 'Perangkat Komputer')->firstOrFail();
        $location = LokasiInventaris::where('nama_lokasi', 'Kantor')->firstOrFail();
        $this->assertNull($location->unit_pendidikan_id);
        $this->assertDatabaseHas('data_inventaris', [
            'nama_barang' => 'Keyboard',
            'kategori_inventaris_id' => $category->id,
            'lokasi_inventaris_id' => $location->id,
            'kondisi' => 'Perlu diperiksa',
        ]);
    }

    public function test_admin_can_export_filtered_inventory_as_excel_workbook(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = KategoriInventaris::firstOrCreate(['nama_kategori' => 'Perangkat Ekspor']);
        $location = LokasiInventaris::firstOrCreate([
            'unit_pendidikan_id' => null,
            'nama_lokasi' => 'Ruang Ekspor',
        ]);
        Inventory::create([
            'nama_barang' => 'Laptop Ekspor',
            'kategori_inventaris_id' => $category->id,
            'lokasi_inventaris_id' => $location->id,
            'jumlah' => 2,
            'kondisi' => 'Baik',
            'tahun_perolehan' => now()->year,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.laporan.export', [
                'category' => $category->id,
                'location' => $location->id,
                'condition' => 'Baik',
            ]))
            ->assertDownload('laporan-inventaris.xlsx')
            ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $archive = new \ZipArchive();
        $this->assertSame(true, $archive->open($response->baseResponse->getFile()->getPathname()));
        $worksheet = $archive->getFromName('xl/worksheets/sheet1.xml');
        $archive->close();

        $this->assertIsString($worksheet);
        $this->assertStringContainsString('Laptop Ekspor', $worksheet);
        $this->assertStringContainsString('Ruang Ekspor', $worksheet);
    }
}