<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class KepalaSekolahSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrNew(['email' => 'kepalasekolah@raudhahsyarifah.sch.id']);

        if (! $user->exists) {
            $user->name = 'Kepala Sekolah';
            $user->username = 'kepala_sekolah';
            $user->password = 'sekolah123';
        }

        $user->role = 'kepala_sekolah';
        $user->status = 'aktif';
        $user->save();
    }
}