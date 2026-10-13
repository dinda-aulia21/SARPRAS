<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\Inventory;
use App\Models\LokasiInventaris;
use App\Models\UnitPendidikan;
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
        $sd = UnitPendidikan::where('nama_unit', 'SD')->firstOrFail();
        $smp = UnitPendidikan::where('nama_unit', 'SMP')->firstOrFail();
        $sdLocation = LokasiInventaris::create(['unit_pendidikan_id' => $sd->id, 'nama_lokasi' => 'Aula SD']);
        $smpLocation = LokasiInventaris::create(['unit_pendidikan_id' => $smp->id, 'nama_lokasi' => 'Aula SMP']);
        $inventory = Inventory::create([
            'nama_barang' => 'Proyektor Aula',
            'kategori_inventaris_id' => \App\Models\KategoriInventaris::firstOrCreate(['nama_kategori' => 'Elektronik'])->id,
            'lokasi_inventaris_id' => $sdLocation->id,
            'jumlah' => 2,
            'kondisi' => 'Baik',
        ]);
        Inventory::create([
            'name' => 'Proyektor Unit Lain',
            'kategori_inventaris_id' => \App\Models\KategoriInventaris::firstOrCreate(['nama_kategori' => 'Elektronik'])->id,
            'lokasi_inventaris_id' => $smpLocation->id,
            'quantity' => 1,
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
            $user = User::factory()->create([
                'role' => $role,
                'unit_pendidikan_id' => $role === 'kepala_sekolah' ? $sd->id : null,
            ]);
            $this->actingAs($user);

            $this->get(route('admin.inventaris'))
                ->assertOk()
                ->assertSee('Proyektor Aula')
                ->assertDontSee('class="inventory-primary-button" type="button" data-open-inventory-modal', false);
            if ($role === 'kepala_sekolah') {
                $this->get(route('admin.inventaris'))->assertDontSee('Proyektor Unit Lain');
            } else {
                $this->get(route('admin.inventaris'))->assertSee('Proyektor Unit Lain');
            }

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

    public function test_school_head_without_unit_can_see_all_inventory_and_reports(): void
    {
        $sd = UnitPendidikan::where('nama_unit', 'SD')->firstOrFail();
        $smp = UnitPendidikan::where('nama_unit', 'SMP')->firstOrFail();
        $sdLocation = LokasiInventaris::create(['unit_pendidikan_id' => $sd->id, 'nama_lokasi' => 'Ruang SD']);
        $smpLocation = LokasiInventaris::create(['unit_pendidikan_id' => $smp->id, 'nama_lokasi' => 'Ruang SMP']);
        Inventory::create([
            'nama_barang' => 'Inventaris SD',
            'kategori_inventaris_id' => \App\Models\KategoriInventaris::firstOrCreate(['nama_kategori' => 'Furnitur'])->id,
            'lokasi_inventaris_id' => $sdLocation->id,
            'jumlah' => 3,
            'kondisi' => 'Baik',
        ]);
        Inventory::create([
            'nama_barang' => 'Inventaris SMP',
            'kategori_inventaris_id' => \App\Models\KategoriInventaris::firstOrCreate(['nama_kategori' => 'Furnitur'])->id,
            'lokasi_inventaris_id' => $smpLocation->id,
            'jumlah' => 2,
            'kondisi' => 'Baik',
        ]);
        Inventory::create([
            'name' => 'Inventaris Belum Dipetakan',
            'category' => 'Furnitur',
            'location' => 'Lokasi Tidak Diketahui',
            'quantity' => 1,
            'condition' => 'Baik',
        ]);

        $schoolHead = User::factory()->create([
            'role' => 'kepala_sekolah',
            'unit_pendidikan_id' => null,
        ]);

        $this->actingAs($schoolHead)
            ->get(route('admin.inventaris'))
            ->assertOk()
            ->assertSee('Inventaris SD')
            ->assertSee('Inventaris SMP')
            ->assertSee('Inventaris Belum Dipetakan')
            ->assertSee('Ruang SD')
            ->assertSee('Ruang SMP');

        $this->get(route('admin.laporan'))
            ->assertOk()
            ->assertSee('Inventaris SD')
            ->assertSee('Inventaris SMP')
            ->assertSee('Inventaris Belum Dipetakan');

        $this->get(route('kepala-sekolah.dashboard'))
            ->assertOk()
            ->assertSee('Inventaris SD')
            ->assertSee('Inventaris SMP');
    }

    public function test_admin_can_assign_a_school_head_to_a_unit(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $schoolHead = User::factory()->create([
            'role' => 'kepala_sekolah',
            'unit_pendidikan_id' => null,
        ]);
        $smp = UnitPendidikan::where('nama_unit', 'SMP')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('admin.pengguna.unit.update', $schoolHead), [
                'unit_pendidikan_id' => $smp->id,
            ])
            ->assertRedirect(route('admin.inventaris'));

        $this->assertDatabaseHas('pengguna', [
            'id' => $schoolHead->id,
            'unit_pendidikan_id' => $smp->id,
        ]);
    }
}