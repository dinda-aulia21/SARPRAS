<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SettingsProfileTest extends TestCase
{
    use DatabaseTransactions;

    public function test_settings_profile_labels_match_the_signed_in_users_role(): void
    {
        $roleLabels = [
            'admin' => ['Admin', 'Administrator'],
            'kepala_yayasan' => ['Kepala Yayasan', 'Kepala Yayasan'],
            'kepala_sekolah' => ['Kepala Sekolah', 'Kepala Sekolah'],
        ];

        foreach ($roleLabels as $role => [$profileLabel, $fieldLabel]) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('admin.pengaturan'))
                ->assertOk()
                ->assertSee("Profil {$profileLabel}")
                ->assertSee('value="' . $fieldLabel . '"', false);
        }
    }
}
