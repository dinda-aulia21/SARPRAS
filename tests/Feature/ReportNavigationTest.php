<?php

namespace Tests\Feature;

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
}