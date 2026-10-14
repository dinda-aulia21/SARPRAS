<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@raudhahsyarifah.sch.id'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => 'admin123',
                'role' => 'admin',
                'status' => 'aktif',
            ]
        );

        $this->call(KepalaYayasanSeeder::class);
        $this->call(KepalaSekolahSeeder::class);
        $this->call(InventorySeeder::class);
    }
}
