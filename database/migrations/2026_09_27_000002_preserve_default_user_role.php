<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql' && Schema::hasColumn('users', 'role')) {
            DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('admin','guru','orangtua','siswa','kepala_yayasan') NOT NULL DEFAULT 'siswa'");
        }
    }

    public function down(): void
    {
    }
};