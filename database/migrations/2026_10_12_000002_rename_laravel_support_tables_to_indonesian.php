<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'failed_jobs' => 'pekerjaan_gagal',
            'password_reset_tokens' => 'token_reset_password',
            'personal_access_tokens' => 'token_akses_pribadi',
        ] as $englishName => $indonesianName) {
            if (Schema::hasTable($englishName) && ! Schema::hasTable($indonesianName)) {
                Schema::rename($englishName, $indonesianName);
            }
        }
    }

    public function down(): void
    {
        foreach ([
            'pekerjaan_gagal' => 'failed_jobs',
            'token_reset_password' => 'password_reset_tokens',
            'token_akses_pribadi' => 'personal_access_tokens',
        ] as $indonesianName => $englishName) {
            if (Schema::hasTable($indonesianName) && ! Schema::hasTable($englishName)) {
                Schema::rename($indonesianName, $englishName);
            }
        }
    }
};