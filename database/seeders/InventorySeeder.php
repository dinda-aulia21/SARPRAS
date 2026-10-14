<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\KategoriInventaris;
use App\Models\LokasiInventaris;
use App\Models\UnitPendidikan;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Mebel',
            'Elektronik',
            'Peralatan Kantor',
            'Buku & Arsip',
        ];

        $categoryIds = [];

        foreach ($categories as $categoryName) {
            $category = KategoriInventaris::updateOrCreate(
                ['nama_kategori' => $categoryName],
                ['keterangan' => null]
            );

            $categoryIds[$categoryName] = $category->id;
        }

        $unitIds = UnitPendidikan::query()->pluck('id', 'nama_unit');

        $locations = [
            ['unit' => 'TK', 'name' => 'Ruang Kelas TK A'],
            ['unit' => 'TK', 'name' => 'Ruang Kelas TK B'],
            ['unit' => 'SD', 'name' => 'Ruang Kelas 1'],
            ['unit' => 'SD', 'name' => 'Ruang Kelas 2'],
            ['unit' => 'SMP', 'name' => 'Ruang Lab Komputer'],
            ['unit' => 'SMK', 'name' => 'Ruang Administrasi'],
            ['unit' => 'SMK', 'name' => 'Gudang Utama'],
        ];

        $locationIds = [];

        foreach ($locations as $locationData) {
            $location = LokasiInventaris::updateOrCreate(
                [
                    'unit_pendidikan_id' => $unitIds[$locationData['unit']] ?? null,
                    'nama_lokasi' => $locationData['name'],
                ],
                ['keterangan' => null]
            );

            $locationIds[$locationData['name']] = $location->id;
        }

        $inventories = [
            [
                'kode_barang' => 'INV-SEED-0001',
                'nama_barang' => 'Meja Belajar Siswa',
                'kategori' => 'Mebel',
                'lokasi' => 'Ruang Kelas 1',
                'jumlah' => 24,
                'kondisi' => 'Baik',
                'tahun_perolehan' => 2024,
                'keterangan' => 'Digunakan untuk kegiatan belajar harian.',
            ],
            [
                'kode_barang' => 'INV-SEED-0002',
                'nama_barang' => 'Kursi Guru',
                'kategori' => 'Mebel',
                'lokasi' => 'Ruang Administrasi',
                'jumlah' => 6,
                'kondisi' => 'Baik',
                'tahun_perolehan' => 2023,
                'keterangan' => 'Ditempatkan di ruang guru dan tata usaha.',
            ],
            [
                'kode_barang' => 'INV-SEED-0003',
                'nama_barang' => 'Laptop Inventaris',
                'kategori' => 'Elektronik',
                'lokasi' => 'Ruang Lab Komputer',
                'jumlah' => 8,
                'kondisi' => 'Rusak Ringan',
                'tahun_perolehan' => 2022,
                'keterangan' => 'Sebagian unit perlu pengecekan baterai.',
            ],
            [
                'kode_barang' => 'INV-SEED-0004',
                'nama_barang' => 'Proyektor Ruang Kelas',
                'kategori' => 'Elektronik',
                'lokasi' => 'Ruang Kelas 2',
                'jumlah' => 3,
                'kondisi' => 'Baik',
                'tahun_perolehan' => 2024,
                'keterangan' => 'Dipakai untuk presentasi dan pembelajaran digital.',
            ],
            [
                'kode_barang' => 'INV-SEED-0005',
                'nama_barang' => 'Buku Absensi Arsip',
                'kategori' => 'Buku & Arsip',
                'lokasi' => 'Gudang Utama',
                'jumlah' => 40,
                'kondisi' => 'Baik',
                'tahun_perolehan' => 2021,
                'keterangan' => 'Disimpan sebagai arsip administrasi.',
            ],
            [
                'kode_barang' => 'INV-SEED-0006',
                'nama_barang' => 'Kipas Angin Ruang Belajar',
                'kategori' => 'Peralatan Kantor',
                'lokasi' => 'Ruang Kelas TK A',
                'jumlah' => 5,
                'kondisi' => 'Rusak Berat',
                'tahun_perolehan' => 2020,
                'keterangan' => 'Sebagian unit tidak dapat digunakan dan perlu penggantian.',
            ],
        ];

        foreach ($inventories as $inventoryData) {
            Inventory::updateOrCreate(
                ['kode_barang' => $inventoryData['kode_barang']],
                [
                    'kategori_inventaris_id' => $categoryIds[$inventoryData['kategori']],
                    'lokasi_inventaris_id' => $locationIds[$inventoryData['lokasi']],
                    'nama_barang' => $inventoryData['nama_barang'],
                    'jumlah' => $inventoryData['jumlah'],
                    'kondisi' => $inventoryData['kondisi'],
                    'tahun_perolehan' => $inventoryData['tahun_perolehan'],
                    'keterangan' => $inventoryData['keterangan'],
                    'foto' => null,
                ]
            );
        }
    }
}