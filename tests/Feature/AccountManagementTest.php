<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AccountManagementTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_manage_the_three_system_accounts_but_cannot_create_more(): void
    {
        $admin = User::query()->where('username', 'admin')->first()
            ?? User::factory()->create(['role' => 'admin', 'username' => 'admin']);
        $account = User::query()->where('username', 'kepalasekolah')->first()
            ?? User::factory()->create(['role' => 'kepala_sekolah', 'username' => 'kepalasekolah']);
        $yayasan = User::query()->where('username', 'kepala')->first()
            ?? User::factory()->create(['role' => 'kepala_yayasan', 'username' => 'kepala']);
        $extraAccount = User::factory()->create(['email' => 'extra@example.test']);
        $this->actingAs($admin);

        $this->post(route('admin.pengguna.store'), [
            'name' => 'Akun Baru',
            'email' => 'akun.baru@example.test',
            'role' => 'kepala_sekolah',
            'unit_pendidikan_id' => '',
        ])->assertStatus(422);
        $this->assertDatabaseMissing('pengguna', ['email' => 'akun.baru@example.test']);

        $this->get(route('admin.pengaturan'))
            ->assertOk()
            ->assertSee($admin->email)
            ->assertSee($account->email)
            ->assertSee($yayasan->email)
            ->assertSee('Buka aksi akun ' . $account->name)
            ->assertSee('Reset password')
            ->assertSee('Nonaktifkan')
            ->assertSee('Hapus')
            ->assertDontSee($extraAccount->email);

        $this->put(route('admin.pengguna.update', $extraAccount), [
            'name' => 'Akun Tambahan',
            'email' => $extraAccount->email,
            'role' => 'admin',
        ])->assertNotFound();

        $this->put(route('admin.pengguna.update', $account), [
            'name' => 'Nama Diubah',
            'email' => 'akun.diubah@example.test',
            'role' => 'kepala_yayasan',
            'unit_pendidikan_id' => '',
        ])->assertRedirect(route('admin.pengaturan') . '#pemantauan-akun');

        $this->assertSame('Nama Diubah', $account->fresh()->name);
        $this->assertSame('kepala_yayasan', $account->fresh()->role);
        $this->assertNull($account->fresh()->unit_pendidikan_id);

        $this->patch(route('admin.pengguna.status', $account), ['status' => 'nonaktif'])
            ->assertRedirect(route('admin.pengaturan') . '#pemantauan-akun');
        $this->assertSame('nonaktif', $account->fresh()->status);

        $this->patch(route('admin.pengguna.status', $account), ['status' => 'aktif'])
            ->assertRedirect(route('admin.pengaturan') . '#pemantauan-akun');
        $this->assertSame('aktif', $account->fresh()->status);

        $this->delete(route('admin.pengguna.destroy', $account))
            ->assertRedirect(route('admin.pengaturan') . '#pemantauan-akun');
        $this->assertDatabaseMissing('pengguna', ['id' => $account->id]);
        $this->assertDatabaseHas('pengguna', ['id' => $extraAccount->id]);
    }

    public function test_non_admin_cannot_manage_accounts(): void
    {
        $schoolHead = User::factory()->create(['role' => 'kepala_sekolah']);

        $this->actingAs($schoolHead)
            ->post(route('admin.pengguna.store'), [
                'name' => 'Akun Baru',
                'email' => 'akun.baru@example.test',
                'role' => 'admin',
            ])
            ->assertForbidden();
    }

    public function test_admin_cannot_delete_or_disable_themselves(): void
    {
        $admin = User::query()->where('username', 'admin')->first()
            ?? User::factory()->create(['role' => 'admin', 'username' => 'admin']);

        $this->actingAs($admin)
            ->delete(route('admin.pengguna.destroy', $admin))
            ->assertStatus(422);

        $this->patch(route('admin.pengguna.status', $admin), ['status' => 'nonaktif'])
            ->assertSessionHasErrors('status');

        $this->assertSame('aktif', $admin->fresh()->status);
    }
}
