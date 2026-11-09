<?php

namespace Tests\Feature;

use App\Models\PasswordChangeLog;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PasswordChangeAuditTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_can_view_account_emails_and_password_change_history(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $account = User::factory()->create(['role' => 'kepala_sekolah']);
        PasswordChangeLog::create([
            'user_id' => $account->id,
            'changed_by' => $account->id,
            'email' => $account->email,
            'changed_at' => now(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.pengaturan'))
            ->assertOk()
            ->assertSee('Pemantauan Akun')
            ->assertSee('Riwayat perubahan password terbaru')
            ->assertSee($account->email)
            ->assertDontSee('password asli');
    }

    public function test_non_admin_cannot_view_account_monitoring(): void
    {
        $schoolHead = User::factory()->create(['role' => 'kepala_sekolah']);

        $this->actingAs($schoolHead)
            ->get(route('admin.pengaturan'))
            ->assertOk()
            ->assertDontSee('Pemantauan Akun')
            ->assertDontSee('Riwayat perubahan password terbaru');
    }

    public function test_successful_password_change_is_logged_without_storing_password_values(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('old-password'),
        ]);

        $this->actingAs($user)
            ->put(route('admin.pengaturan.password'), [
                'current_password' => 'old-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ])
            ->assertRedirect(route('admin.pengaturan'))
            ->assertSessionHas('password_success');

        $this->assertDatabaseHas('password_change_logs', [
            'user_id' => $user->id,
            'changed_by' => $user->id,
            'email' => $user->email,
        ]);
        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
        $this->assertNotContains('password', Schema::getColumnListing('password_change_logs'));
    }

    public function test_admin_reset_requires_user_to_set_a_new_password_before_continuing(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $account = User::factory()->create(['role' => 'kepala_yayasan']);

        $this->actingAs($admin)
            ->post(route('admin.pengguna.password-reset', $account))
            ->assertRedirect(route('admin.pengaturan') . '#keamanan')
            ->assertSessionHas('temporary_password_reset', function (array $reset) use ($account) {
                return $reset['email'] === $account->email
                    && Hash::check($reset['password'], $account->fresh()->password);
            });

        $this->assertTrue($account->fresh()->password_reset_required);
        $this->assertDatabaseHas('password_change_logs', [
            'user_id' => $account->id,
            'changed_by' => $admin->id,
            'email' => $account->email,
            'action' => 'admin_reset',
        ]);

        $account = $account->fresh();
        $this->actingAs($account)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('password.force-change'));

        $this->get(route('password.force-change'))
            ->assertOk()
            ->assertSee('Buat Password Baru');

        $this->post(route('password.force-update'), [
            'password' => 'personal-password',
            'password_confirmation' => 'personal-password',
        ])->assertRedirect(route('kepala-yayasan.dashboard'));

        $this->assertFalse($account->fresh()->password_reset_required);
        $this->assertTrue(Hash::check('personal-password', $account->fresh()->password));
    }

    public function test_non_admin_cannot_reset_another_accounts_password(): void
    {
        $schoolHead = User::factory()->create(['role' => 'kepala_sekolah']);
        $account = User::factory()->create(['role' => 'kepala_yayasan']);

        $this->actingAs($schoolHead)
            ->post(route('admin.pengguna.password-reset', $account))
            ->assertForbidden();

        $this->assertFalse($account->fresh()->password_reset_required);
    }
}
