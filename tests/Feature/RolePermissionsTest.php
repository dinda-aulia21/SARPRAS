<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RolePermissionsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_sees_inventory_and_announcement_management_controls(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));
        Inventory::create([
            'name' => 'Proyektor Aula',
            'category' => 'Elektronik',
            'location' => 'Aula',
            'quantity' => 2,
            'condition' => 'Baik',
        ]);
        Announcement::create([
            'title' => 'Jadwal Pemeliharaan',
            'body' => 'Pemeliharaan dilakukan hari Jumat.',
            'publish_date' => now()->toDateString(),
            'status' => 'Aktif',
            'type' => 'umum',
        ]);

        $this->get(route('admin.inventaris'))
            ->assertOk()
            ->assertSee('Admin Sarpras')
            ->assertSee('data-open-inventory-modal', false);

        $this->get(route('admin.pengumuman'))
            ->assertOk()
            ->assertSee('data-open-announcement', false)
            ->assertSee('data-edit-announcement', false)
            ->assertSee('delete-announcement-button', false);
    }

    public function test_school_heads_can_view_but_cannot_manage_inventory_or_announcements(): void
    {
        $inventory = Inventory::create([
            'name' => 'Proyektor Aula',
            'category' => 'Elektronik',
            'location' => 'Aula',
            'quantity' => 2,
            'condition' => 'Baik',
        ]);
        $announcement = Announcement::create([
            'title' => 'Jadwal Pemeliharaan',
            'body' => 'Pemeliharaan dilakukan hari Jumat.',
            'publish_date' => now()->toDateString(),
            'status' => 'Aktif',
            'type' => 'umum',
        ]);

        foreach (['kepala_yayasan', 'kepala_sekolah'] as $role) {
            $user = User::factory()->create(['role' => $role]);
            $this->actingAs($user);

            $this->get(route('admin.inventaris'))
                ->assertOk()
                ->assertSee('Proyektor Aula')
                ->assertDontSee('class="inventory-primary-button" type="button" data-open-inventory-modal', false);

            $this->get(route('admin.pengumuman'))
                ->assertOk()
                ->assertSee('Jadwal Pemeliharaan')
                ->assertDontSee('class="announcement-icon-button edit-announcement-button"', false)
                ->assertDontSee('class="announcement-icon-button delete-announcement-button"', false);

            $this->post(route('admin.inventaris.store'), [
                'name' => 'Meja Baru',
                'category' => 'Furnitur',
                'location' => 'Ruang Guru',
                'quantity' => 1,
                'condition' => 'Baik',
            ])->assertForbidden();
            $this->put(route('admin.inventaris.update', $inventory), [
                'name' => 'Proyektor Aula',
                'category' => 'Elektronik',
                'location' => 'Aula',
                'quantity' => 2,
                'condition' => 'Baik',
            ])->assertForbidden();
            $this->delete(route('admin.inventaris.destroy', $inventory))->assertForbidden();

            $this->post(route('admin.pengumuman.store'), [
                'title' => 'Pengumuman Baru',
                'body' => 'Informasi baru.',
                'publish_date' => now()->toDateString(),
                'status' => 'Aktif',
            ])->assertForbidden();
            $this->put(route('admin.pengumuman.update', $announcement), [
                'title' => 'Jadwal Pemeliharaan',
                'body' => 'Pemeliharaan dilakukan hari Jumat.',
                'publish_date' => now()->toDateString(),
                'status' => 'Aktif',
            ])->assertForbidden();
            $this->delete(route('admin.pengumuman.destroy', $announcement))->assertForbidden();
        }
    }
}