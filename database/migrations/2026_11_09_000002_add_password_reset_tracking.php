<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->boolean('password_reset_required')->default(false);
        });

        Schema::table('password_change_logs', function (Blueprint $table) {
            $table->string('action', 30)->default('password_changed');
        });
    }

    public function down(): void
    {
        Schema::table('password_change_logs', function (Blueprint $table) {
            $table->dropColumn('action');
        });

        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropColumn('password_reset_required');
        });
    }
};
