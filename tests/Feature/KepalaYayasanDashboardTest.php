<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class KepalaYayasanDashboardTest extends TestCase
{
    use DatabaseTransactions;

    public function test_authenticated_dashboard_shows_inventory_from_the_database(): void
    {
        $user = User::factory()->create(['role' => 'kepala_yayasan']);
        Inventory::create([
            'name' => 'Meja Rapat',
            'category' => 'Furnitur',
            'location' => 'Ruang Rapat',
            'quantity' => 3,
            'condition' => 'Baik',
        ]);

        $this->actingAs($user)
            ->get(route('kepala-yayasan.dashboard'))
            ->assertOk()
            ->assertSee('Kepala Yayasan')
            ->assertSee('Ruang Rapat')
            ->assertSee('Furnitur')
            ->assertSee('Meja Rapat');
    }

    public function test_head_yayasan_login_redirects_to_the_executive_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'kepala_yayasan']);

        $this->post(route('login.authenticate'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('kepala-yayasan.dashboard'));
    }

    public function test_admin_login_still_redirects_to_the_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->post(route('login.authenticate'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_cannot_open_the_head_yayasan_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('kepala-yayasan.dashboard'))
            ->assertForbidden();
    }
}