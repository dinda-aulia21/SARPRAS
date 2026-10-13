<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hak_akses', function (Blueprint $table) {
            $table->id();
            $table->string('nama_hak_akses')->unique();
            $table->timestamps();
        });

        Schema::create('unit_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_unit')->unique();
            $table->string('alamat')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        foreach (['Admin', 'Kepala Yayasan', 'Kepala Sekolah'] as $roleName) {
            DB::table('hak_akses')->insert([
                'nama_hak_akses' => $roleName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach (['TK', 'SD', 'SMP', 'SMK'] as $unitName) {
            DB::table('unit_pendidikan')->insert([
                'nama_unit' => $unitName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::create('kategori_inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori')->unique();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        Schema::create('lokasi_inventaris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_pendidikan_id')->nullable()
                ->constrained('unit_pendidikan')->nullOnDelete();
            $table->string('nama_lokasi');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['unit_pendidikan_id', 'nama_lokasi']);
        });

        $units = DB::table('unit_pendidikan')->pluck('id', 'nama_unit');
        $knownLocationUnits = [
            'ruang kelas 2' => 'SD',
            'ruang kelas 3' => 'SD',
            'ruang kelas 7' => 'SMP',
        ];

        foreach (DB::table('data_inventaris')->select('category')->distinct()->pluck('category') as $categoryName) {
            DB::table('kategori_inventaris')->insert([
                'nama_kategori' => $categoryName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach (DB::table('data_inventaris')->select('location')->distinct()->pluck('location') as $locationName) {
            $normalizedName = Str::lower(trim($locationName));
            $unitName = $knownLocationUnits[$normalizedName] ?? null;

            DB::table('lokasi_inventaris')->insert([
                'unit_pendidikan_id' => $unitName ? $units[$unitName] : null,
                'nama_lokasi' => $locationName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('pengguna', function (Blueprint $table) {
            $table->foreignId('hak_akses_id')->nullable()->after('id');
            $table->foreignId('unit_pendidikan_id')->nullable()->after('hak_akses_id')
                ->constrained('unit_pendidikan')->nullOnDelete();
            $table->string('nama_lengkap')->nullable()->after('unit_pendidikan_id');
            $table->string('username')->nullable()->unique()->after('nama_lengkap');
            $table->string('foto')->nullable();
            $table->string('status')->default('aktif');
        });

        $roleIds = DB::table('hak_akses')->pluck('id', 'nama_hak_akses');
        $roleNames = [
            'admin' => 'Admin',
            'kepala_yayasan' => 'Kepala Yayasan',
            'kepala_sekolah' => 'Kepala Sekolah',
        ];

        foreach (DB::table('pengguna')->select('id', 'name', 'email', 'role')->get() as $user) {
            $roleName = $roleNames[$user->role] ?? null;
            if (! $roleName) {
                throw new RuntimeException("Role pengguna {$user->id} tidak dikenali: {$user->role}");
            }

            $baseUsername = Str::slug(Str::before($user->email, '@'), '_') ?: 'pengguna';
            $username = $baseUsername;
            $suffix = 1;
            while (DB::table('pengguna')->where('username', $username)->where('id', '!=', $user->id)->exists()) {
                $username = $baseUsername . '_' . $suffix++;
            }

            DB::table('pengguna')->where('id', $user->id)->update([
                'hak_akses_id' => $roleIds[$roleName],
                'nama_lengkap' => $user->name,
                'username' => $username,
            ]);
        }

        Schema::table('pengguna', function (Blueprint $table) {
            $table->foreign('hak_akses_id')->references('id')->on('hak_akses');
            $table->dropColumn(['name', 'role']);
        });

        Schema::table('data_inventaris', function (Blueprint $table) {
            $table->foreignId('kategori_inventaris_id')->nullable();
            $table->foreignId('lokasi_inventaris_id')->nullable();
            $table->string('kode_barang')->nullable()->unique();
            $table->unsignedSmallInteger('tahun_perolehan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
        });

        foreach (DB::table('data_inventaris')->select('id', 'category', 'location', 'created_at')->get() as $inventory) {
            $categoryId = DB::table('kategori_inventaris')
                ->where('nama_kategori', $inventory->category)->value('id');
            $locationQuery = DB::table('lokasi_inventaris')
                ->where('nama_lokasi', $inventory->location);
            $locationId = $locationQuery->value('id');

            DB::table('data_inventaris')->where('id', $inventory->id)->update([
                'kategori_inventaris_id' => $categoryId,
                'lokasi_inventaris_id' => $locationId,
                'kode_barang' => 'INV-' . str_pad((string) $inventory->id, 6, '0', STR_PAD_LEFT),
            ]);
        }

        Schema::table('data_inventaris', function (Blueprint $table) {
            $table->foreign('kategori_inventaris_id')->references('id')->on('kategori_inventaris');
            $table->foreign('lokasi_inventaris_id')->references('id')->on('lokasi_inventaris');
            $table->renameColumn('name', 'nama_barang');
            $table->renameColumn('quantity', 'jumlah');
            $table->renameColumn('condition', 'kondisi');
            $table->dropColumn(['category', 'location']);
        });

        Schema::table('pengumuman', function (Blueprint $table) {
            $table->foreignId('pengguna_id')->nullable()->after('id')
                ->constrained('pengguna')->nullOnDelete();
            $table->text('isi_pengumuman')->nullable();
            $table->date('tanggal_pengumuman')->nullable();
            $table->string('status_baru')->default('aktif');
        });

        DB::table('pengumuman')->update([
            'isi_pengumuman' => DB::raw('konten'),
            'tanggal_pengumuman' => DB::raw('tanggal_mulai'),
        ]);
        DB::table('pengumuman')->where('status', 'arsip')->update(['status_baru' => 'tidak_aktif']);
        DB::table('pengumuman')->where('status', 'aktif')->update(['status_baru' => 'aktif']);

        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropColumn(['konten', 'tanggal_mulai', 'status']);
            $table->renameColumn('status_baru', 'status');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `pengguna` MODIFY `hak_akses_id` BIGINT UNSIGNED NOT NULL, MODIFY `nama_lengkap` VARCHAR(255) NOT NULL, MODIFY `username` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `data_inventaris` MODIFY `kategori_inventaris_id` BIGINT UNSIGNED NOT NULL, MODIFY `lokasi_inventaris_id` BIGINT UNSIGNED NOT NULL, MODIFY `kode_barang` VARCHAR(255) NOT NULL');
            DB::statement('ALTER TABLE `pengumuman` MODIFY `isi_pengumuman` TEXT NOT NULL, MODIFY `tanggal_pengumuman` DATE NOT NULL');
        }
    }

    public function down(): void
    {
        throw new RuntimeException('Pemulihan struktur lama perlu dilakukan dari backup karena data referensi baru mungkin sudah digunakan.');
    }
};
