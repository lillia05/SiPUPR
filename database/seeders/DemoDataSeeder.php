<?php

namespace Database\Seeders;

use App\Models\Nasabah;
use App\Models\PenerimaBantuan;
use App\Models\PengajuanRek;
use App\Models\StatusLog;
use App\Models\TahapanPenyaluran;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $cabang = User::create([
            'username' => 'cabang',
            'password' => Hash::make('12345678'),
            'role' => 'cabang',
        ]);

        $pupr = User::create([
            'username' => 'pupr',
            'password' => Hash::make('12345678'),
            'role' => 'pupr',
        ]);
        
        $penerima1 = PenerimaBantuan::create([
            'nama_pb' => 'Siti Aminah', // Progress 100%
            'nomor_rekening' => '7000987654',
            'deliniasi' => 'Pesisir',
            'kabupaten' => 'Pesawaran',
            'kecamatan' => 'Gedong Tataan',
            'desa' => 'Sukaraja',
            'total_alokasi_bantuan' => 20000000.00,
        ]);

        // Tahap 1: 10 Juta (DONE)
        TahapanPenyaluran::create([
            'penerima_bantuan_id' => $penerima1->id,
            'tahap_ke' => 1,
            'nominal' => 10000000.00,
            'status' => 'DONE',
            'tanggal_transaksi' => Carbon::now()->subMonths(2),
        ]);

        // Tahap 2: 7,5 Juta (DONE)
        TahapanPenyaluran::create([
            'penerima_bantuan_id' => $penerima1->id,
            'tahap_ke' => 2,
            'nominal' => 7500000.00,
            'status' => 'DONE',
            'tanggal_transaksi' => Carbon::now()->subMonth(),
        ]);

        // Tahap 3: 2,5 Juta (DONE)
        TahapanPenyaluran::create([
            'penerima_bantuan_id' => $penerima1->id,
            'tahap_ke' => 3,
            'nominal' => 2500000.00,
            'status' => 'DONE',
            'tanggal_transaksi' => Carbon::now()->subDays(5),
        ]);


        // --- SKENARIO 2: BARU SELESAI TAHAP 1 (SEDANG BERJALAN) ---
        $penerima2 = PenerimaBantuan::create([
            'nama_pb' => 'Budi Santoso', // Progress ~50%
            'nomor_rekening' => '7000123456',
            'deliniasi' => 'Pesisir',
            'kabupaten' => 'Lampung Selatan',
            'kecamatan' => 'Natar',
            'desa' => 'Hajimena',
            'total_alokasi_bantuan' => 20000000.00,
        ]);

        // Tahap 1: 10 Juta (DONE)
        TahapanPenyaluran::create([
            'penerima_bantuan_id' => $penerima2->id,
            'tahap_ke' => 1,
            'nominal' => 10000000.00,
            'status' => 'DONE',
            'tanggal_transaksi' => Carbon::now()->subDays(10),
        ]);

        // Tahap 2: 7,5 Juta (BELUM)
        TahapanPenyaluran::create([
            'penerima_bantuan_id' => $penerima2->id,
            'tahap_ke' => 2,
            'nominal' => 7500000.00,
            'status' => 'not',
            'tanggal_transaksi' => null,
        ]);

        // Tahap 3: 2,5 Juta (BELUM)
        TahapanPenyaluran::create([
            'penerima_bantuan_id' => $penerima2->id,
            'tahap_ke' => 3,
            'nominal' => 2500000.00,
            'status' => 'not',
            'tanggal_transaksi' => null,
        ]);


        // --- SKENARIO 3: BELUM ADA PENCAIRAN SAMA SEKALI ---
        $penerima3 = PenerimaBantuan::create([
            'nama_pb' => 'Joko Widodo', // Progress 0%
            'nomor_rekening' => '7000555555',
            'deliniasi' => 'Perkotaan',
            'kabupaten' => 'Bandar Lampung',
            'kecamatan' => 'Kedaton',
            'desa' => 'Surabaya',
            'total_alokasi_bantuan' => 20000000.00,
        ]);

        // Buat 3 tahapan tapi statusnya 'not' semua
        $nominals = [10000000.00, 7500000.00, 2500000.00];
        foreach ($nominals as $index => $nominal) {
            TahapanPenyaluran::create([
                'penerima_bantuan_id' => $penerima3->id,
                'tahap_ke' => $index + 1,
                'nominal' => $nominal,
                'status' => 'not',
                'tanggal_transaksi' => null,
            ]);
        }
    }
}