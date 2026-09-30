<?php

namespace Tests\Feature;

use App\Models\Inventory;
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
                'name' => 'Meja baru',
                'category' => 'Furnitur',
                'location' => 'Ruang Kelas 1',
                'quantity' => 4,
                'condition' => 'Rusak Ringan',
            ])
            ->assertRedirect(route('admin.inventaris'))
            ->assertSessionHas('success', 'Data inventaris berhasil diperbarui.');

        $this->assertDatabaseHas('inventories', [
            'id' => $inventory->id,
            'name' => 'Meja baru',
            'location' => 'Ruang Kelas 1',
            'quantity' => 4,
            'condition' => 'Rusak Ringan',
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

        $this->assertDatabaseMissing('inventories', ['id' => $inventory->id]);
    }
}