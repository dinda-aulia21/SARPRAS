<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'users' => 'pengguna',
            'inventories' => 'data_inventaris',
            'announcements' => 'pengumuman',
        ] as $englishName => $indonesianName) {
            if (Schema::hasTable($englishName) && ! Schema::hasTable($indonesianName)) {
                Schema::rename($englishName, $indonesianName);
            }
        }
    }

    public function down(): void
    {
        foreach ([
            'pengguna' => 'users',
            'data_inventaris' => 'inventories',
            'pengumuman' => 'announcements',
        ] as $indonesianName => $englishName) {
            if (Schema::hasTable($indonesianName) && ! Schema::hasTable($englishName)) {
                Schema::rename($indonesianName, $englishName);
            }
        }
    }
};