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
                'lokasi_inventaris_id' => $inventory->lokasi_inventaris_id,
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
                'lokasi_inventaris_id' => $location->id,
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
}