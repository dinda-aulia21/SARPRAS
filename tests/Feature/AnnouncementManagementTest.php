<?php

namespace Tests\Feature;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AnnouncementManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_manage_announcements(): void
    {
        $admin = User::factory()->create();
        $this->actingAs($admin);

        $this->get(route('admin.pengumuman'))
            ->assertOk()
            ->assertSee('Pengumuman');

        $this->post(route('admin.pengumuman.store'), [
            'title' => 'Jadwal Pemeliharaan',
            'body' => 'Pemeliharaan ruang kelas dilakukan hari Jumat.',
            'publish_date' => '2026-10-02',
            'status' => 'Aktif',
        ])->assertRedirect(route('admin.pengumuman'));

        $this->assertDatabaseHas('announcements', [
            'judul' => 'Jadwal Pemeliharaan',
            'konten' => 'Pemeliharaan ruang kelas dilakukan hari Jumat.',
            'tipe' => 'umum',
            'tanggal_mulai' => '2026-10-02',
            'status' => 'aktif',
        ]);

        $announcement = Announcement::query()->where('judul', 'Jadwal Pemeliharaan')->firstOrFail();

        $this->put(route('admin.pengumuman.update', $announcement), [
            'title' => 'Jadwal Pemeliharaan Gedung',
            'body' => 'Pemeliharaan ruang kelas dan kantor dilakukan hari Jumat.',
            'publish_date' => '2026-10-02',
            'status' => 'Tidak Aktif',
        ])->assertRedirect(route('admin.pengumuman'));

        $this->assertDatabaseHas('announcements', [
            'id' => $announcement->id,
            'judul' => 'Jadwal Pemeliharaan Gedung',
            'status' => 'arsip',
        ]);

        $this->delete(route('admin.pengumuman.destroy', $announcement))
            ->assertRedirect(route('admin.pengumuman'));

        $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
    }
}