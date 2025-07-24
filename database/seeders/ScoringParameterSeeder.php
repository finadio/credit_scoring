<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ScoringParameter; // Pastikan Anda mengimpor model ScoringParameter

class ScoringParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contoh parameter untuk kategori 'UMKM/Pengusaha'
        $parametersUMKM = [
            [
                'parameter_name' => 'Omzet Usaha',
                'category' => 'UMKM/Pengusaha',
                'description' => 'Estimasi omzet bulanan usaha nasabah.',
                'rules' => [
                    'type' => 'range',
                    'options' => [
                        ['min' => 0, 'max' => 5000000, 'score' => 10],
                        ['min' => 5000001, 'max' => 20000000, 'score' => 25],
                        ['min' => 20000001, 'score' => 40] // Tanpa max berarti > dari min
                    ]
                ]
            ],
            [
                'parameter_name' => 'Lama Usaha',
                'category' => 'UMKM/Pengusaha',
                'description' => 'Berapa lama usaha telah berjalan (dalam tahun).',
                'rules' => [
                    'type' => 'range',
                    'options' => [
                        ['min' => 0, 'max' => 1, 'score' => 5],
                        ['min' => 2, 'max' => 3, 'score' => 15],
                        ['min' => 4, 'score' => 30]
                    ]
                ]
            ],
            [
                'parameter_name' => 'Sektor Ekonomi',
                'category' => 'UMKM/Pengusaha',
                'description' => 'Sektor usaha nasabah (stabil vs berisiko tinggi).',
                'rules' => [
                    'type' => 'discrete',
                    'options' => [
                        ['value' => 'Pertanian', 'score' => 25],
                        ['value' => 'Sembako', 'score' => 20],
                        ['value' => 'Hiburan', 'score' => 10],
                        ['value' => 'Kafe', 'score' => 10],
                        ['value' => 'Lainnya', 'score' => 15]
                    ]
                ]
            ],
            [
                'parameter_name' => 'Riwayat Pinjaman (UMKM)',
                'category' => 'UMKM/Pengusaha',
                'description' => 'Pernah menunggak atau macet sebelumnya.',
                'rules' => [
                    'type' => 'discrete',
                    'options' => [
                        ['value' => 'Tidak Pernah', 'score' => 30],
                        ['value' => 'Pernah Menunggak', 'score' => 10],
                        ['value' => 'Pernah Macet', 'score' => 0]
                    ]
                ]
            ],
            [
                'parameter_name' => 'Jenis Jaminan',
                'category' => 'UMKM/Pengusaha',
                'description' => 'Jenis jaminan yang diberikan (kuat vs bergerak).',
                'rules' => [
                    'type' => 'discrete',
                    'options' => [
                        ['value' => 'Tanah/Bangunan', 'score' => 30],
                        ['value' => 'Barang Bergerak', 'score' => 15],
                        ['value' => 'Tanpa Jaminan', 'score' => 0]
                    ]
                ]
            ],
            [
                'parameter_name' => 'Sumber Dana Pengembalian',
                'category' => 'UMKM/Pengusaha',
                'description' => 'Sumber dana utama untuk pengembalian pinjaman.',
                'rules' => [
                    'type' => 'discrete',
                    'options' => [
                        ['value' => 'Usaha Sendiri', 'score' => 25],
                        ['value' => 'Hibah/Pinjaman Lain', 'score' => 10]
                    ]
                ]
            ],
            [
                'parameter_name' => 'Plafond Pengajuan',
                'category' => 'UMKM/Pengusaha',
                'description' => 'Besarnya plafond yang diajukan.',
                'rules' => [
                    'type' => 'range',
                    'options' => [
                        ['min' => 0, 'max' => 50000000, 'score' => 30],
                        ['min' => 50000001, 'max' => 200000000, 'score' => 20],
                        ['min' => 200000001, 'score' => 10]
                    ]
                ]
            ],
            [
                'parameter_name' => 'Jangka Waktu Kredit (UMKM)',
                'category' => 'UMKM/Pengusaha',
                'description' => 'Durasi kredit yang diajukan.',
                'rules' => [
                    'type' => 'range',
                    'options' => [
                        ['min' => 0, 'max' => 12, 'score' => 25], // 0-12 bulan
                        ['min' => 13, 'max' => 36, 'score' => 15], // 13-36 bulan
                        ['min' => 37, 'score' => 5] // > 36 bulan
                    ]
                ]
            ],
        ];

        // Contoh parameter untuk kategori 'Pegawai'
        $parametersPegawai = [
            [
                'parameter_name' => 'Usia',
                'category' => 'Pegawai',
                'description' => 'Usia nasabah.',
                'rules' => [
                    'type' => 'range',
                    'options' => [
                        ['min' => 20, 'max' => 30, 'score' => 25],
                        ['min' => 31, 'max' => 50, 'score' => 30],
                        ['min' => 51, 'max' => 58, 'score' => 20],
                        ['min' => 59, 'score' => 5] // Mendekati pensiun
                    ]
                ]
            ],
            [
                'parameter_name' => 'Masa Kerja',
                'category' => 'Pegawai',
                'description' => 'Lama masa kerja (dalam tahun).',
                'rules' => [
                    'type' => 'range',
                    'options' => [
                        ['min' => 0, 'max' => 1, 'score' => 10],
                        ['min' => 2, 'max' => 5, 'score' => 20],
                        ['min' => 6, 'score' => 30]
                    ]
                ]
            ],
            [
                'parameter_name' => 'Golongan/Jabatan',
                'category' => 'Pegawai',
                'description' => 'Tingkat golongan atau jabatan nasabah.',
                'rules' => [
                    'type' => 'discrete',
                    'options' => [
                        ['value' => 'Staf', 'score' => 15],
                        ['value' => 'Supervisor', 'score' => 25],
                        ['value' => 'Manajer', 'score' => 35],
                        ['value' => 'Direktur', 'score' => 40]
                    ]
                ]
            ],
            [
                'parameter_name' => 'Status Kepegawaian',
                'category' => 'Pegawai',
                'description' => 'Status kepegawaian nasabah (tetap vs kontrak).',
                'rules' => [
                    'type' => 'discrete',
                    'options' => [
                        ['value' => 'Tetap', 'score' => 30],
                        ['value' => 'Kontrak', 'score' => 15]
                    ]
                ]
            ],
            [
                'parameter_name' => 'Gaji Bulanan',
                'category' => 'Pegawai',
                'description' => 'Gaji bulanan nasabah.',
                'rules' => [
                    'type' => 'range',
                    'options' => [
                        ['min' => 0, 'max' => 3000000, 'score' => 10],
                        ['min' => 3000001, 'max' => 7000000, 'score' => 25],
                        ['min' => 7000001, 'score' => 40]
                    ]
                ]
            ],
            [
                'parameter_name' => 'Jumlah Tanggungan',
                'category' => 'Pegawai',
                'description' => 'Jumlah tanggungan keluarga nasabah.',
                'rules' => [
                    'type' => 'range',
                    'options' => [
                        ['min' => 0, 'max' => 1, 'score' => 30],
                        ['min' => 2, 'max' => 3, 'score' => 20],
                        ['min' => 4, 'score' => 10]
                    ]
                ]
            ],
            [
                'parameter_name' => 'Riwayat Kredit (Pegawai)',
                'category' => 'Pegawai',
                'description' => 'Pernah macet kredit sebelumnya.',
                'rules' => [
                    'type' => 'discrete',
                    'options' => [
                        ['value' => 'Tidak Pernah', 'score' => 30],
                        ['value' => 'Pernah Macet', 'score' => 0]
                    ]
                ]
            ],
        ];

        // Masukkan data ke database jika belum ada
        foreach (array_merge($parametersUMKM, $parametersPegawai) as $paramData) {
            if (!ScoringParameter::where('parameter_name', $paramData['parameter_name'])->exists()) {
                ScoringParameter::create($paramData);
                $this->command->info("Scoring parameter '{$paramData['parameter_name']}' created.");
            } else {
                $this->command->info("Scoring parameter '{$paramData['parameter_name']}' already exists.");
            }
        }
    }
}