<?php

namespace Tests\Feature;

use App\Models\UnitPendidikan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReportNavigationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_report_type_cards_open_the_selected_report_mode(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        foreach ([
            'inventaris' => 'Hasil Laporan Inventaris',
            'kondisi' => 'Hasil Laporan Kondisi',
            'cetak' => 'Hasil Cetak Laporan',
        ] as $type => $title) {
            $response = $this->get(route('admin.laporan', ['type' => $type]));

            $response->assertOk()
                ->assertSee($title)
                ->assertSee('aria-current="page"', false);

            if ($type === 'cetak') {
                $response->assertSee('Cetak Sekarang');
            }
        }
    }

    public function test_all_roles_see_their_own_identity_and_excel_export_on_reports(): void
    {
        $schoolUnit = UnitPendidikan::where('nama_unit', 'SD')->firstOrFail();

        foreach ([
            ['role' => 'admin', 'unit_pendidikan_id' => null],
            ['role' => 'kepala_yayasan', 'unit_pendidikan_id' => null],
            ['role' => 'kepala_sekolah', 'unit_pendidikan_id' => $schoolUnit->id],
        ] as $attributes) {
            $user = User::factory()->create($attributes);

            $this->actingAs($user)
                ->get(route('admin.laporan'))
                ->assertOk()
                ->assertSee($user->name)
                ->assertSee(route('admin.laporan.export'), false);
        }
    }
}