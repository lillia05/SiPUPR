<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

// Models
use App\Models\User;
use App\Models\Batch; // Pastikan Model Batch sudah dibuat
use App\Models\PenerimaBantuan;
use App\Models\TahapanPenyaluran;
// Model lain jika diperlukan (Nasabah, dll) biarkan saja
use App\Models\Nasabah; 
use App\Models\PengajuanRek;
use App\Models\StatusLog;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT USER PETUGAS
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

        // 2. BUAT DATA BATCH (GELOMBANG)
        $batch1 = Batch::create([
            'nama_batch' => 'Batch 1 - Periode Januari',
            'tanggal_mulai' => Carbon::create(2026, 1, 10),
        ]);

        $batch2 = Batch::create([
            'nama_batch' => 'Batch 2 - Periode Februari',
            'tanggal_mulai' => Carbon::create(2026, 2, 15),
        ]);

        // 3. BUAT DATA PENERIMA & TAHAPAN
        
        // --- SKENARIO 1: SELESAI SEMUA (DONE) - Masuk Batch 1 ---
        $penerima1 = PenerimaBantuan::create([
            'batch_id' => $batch1->id, // Relasi ke Batch 1
            'nama_pb' => 'Siti Aminah', 
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


        // --- SKENARIO 2: BARU SELESAI TAHAP 1 (SEDANG BERJALAN) - Masuk Batch 1 ---
        $penerima2 = PenerimaBantuan::create([
            'batch_id' => $batch1->id, // Relasi ke Batch 1
            'nama_pb' => 'Budi Santoso', 
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

        // Tahap 2: 7,5 Juta (NOT)
        TahapanPenyaluran::create([
            'penerima_bantuan_id' => $penerima2->id,
            'tahap_ke' => 2,
            'nominal' => 7500000.00,
            'status' => 'NOT',
            'tanggal_transaksi' => null,
        ]);

        // Tahap 3: 2,5 Juta (NOT)
        TahapanPenyaluran::create([
            'penerima_bantuan_id' => $penerima2->id,
            'tahap_ke' => 3,
            'nominal' => 2500000.00,
            'status' => 'NOT',
            'tanggal_transaksi' => null,
        ]);


        // --- SKENARIO 3: BELUM ADA PENCAIRAN SAMA SEKALI - Masuk Batch 2 ---
        $penerima3 = PenerimaBantuan::create([
            'batch_id' => $batch2->id, // Relasi ke Batch 2
            'nama_pb' => 'Joko Widodo', 
            'nomor_rekening' => '7000555555',
            'deliniasi' => 'Perkotaan',
            'kabupaten' => 'Bandar Lampung',
            'kecamatan' => 'Kedaton',
            'desa' => 'Surabaya',
            'total_alokasi_bantuan' => 20000000.00,
        ]);

        // Buat 3 tahapan tapi statusnya 'NOT' semua
        $nominals = [10000000.00, 7500000.00, 2500000.00];
        foreach ($nominals as $index => $nominal) {
            TahapanPenyaluran::create([
                'penerima_bantuan_id' => $penerima3->id,
                'tahap_ke' => $index + 1,
                'nominal' => $nominal,
                'status' => 'NOT',
                'tanggal_transaksi' => null,
            ]);
        }
    }
}