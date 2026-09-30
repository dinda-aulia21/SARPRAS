<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use DatabaseTransactions;

    public function test_admin_dashboard_uses_the_shared_executive_layout_and_live_inventory(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Inventory::create([
            'name' => 'Proyektor Aula',
            'category' => 'Elektronik',
            'location' => 'Aula',
            'quantity' => 4,
            'condition' => 'Baik',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Admin')
            ->assertSee('executive-dashboard-content', false)
            ->assertSee('Proyektor Aula')
            ->assertSee('Aula');
    }
}