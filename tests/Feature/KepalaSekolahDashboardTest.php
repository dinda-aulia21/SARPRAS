<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class KepalaSekolahDashboardTest extends TestCase
{
    public function test_school_head_can_open_their_dashboard(): void
    {
        $schoolHead = User::factory()->create(['role' => 'kepala_sekolah']);

        $this->actingAs($schoolHead)
            ->get(route('kepala-sekolah.dashboard'))
            ->assertOk()
            ->assertSee('Kepala Sekolah');
    }

    public function test_school_head_login_redirects_to_their_dashboard(): void
    {
        $schoolHead = User::factory()->create(['role' => 'kepala_sekolah']);

        $this->post(route('login.authenticate'), [
            'email' => $schoolHead->email,
            'password' => 'password',
        ])->assertRedirect(route('kepala-sekolah.dashboard'));
    }

    public function test_admin_cannot_open_the_school_head_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('kepala-sekolah.dashboard'))
            ->assertForbidden();
    }
}