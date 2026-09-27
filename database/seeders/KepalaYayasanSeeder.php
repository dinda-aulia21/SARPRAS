<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class KepalaYayasanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrNew(['email' => 'kepala@raudhahsyarifah.sch.id']);

        if (! $user->exists) {
            $user->name = 'Kepala Yayasan';
            $user->password = 'kepala123';
        }

        $user->role = 'kepala_yayasan';
        $user->save();
    }
}