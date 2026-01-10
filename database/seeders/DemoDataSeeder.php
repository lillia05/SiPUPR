<?php

namespace Database\Seeders;

use App\Models\Nasabah;
use App\Models\PengajuanRek;
use App\Models\StatusLog;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $funding = User::create([
            'username' => 'funding',
            'email' => 'funding@bsi.com',
            'password' => Hash::make('12345678'),
            'role' => 'Funding',
            'email_verified_at' => now(),
            'status' => 'active', // Pastikan status aktif
        ]);

        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@bsi.com',
            'password' => Hash::make('admin123@'),
            'role' => 'Admin',
            'email_verified_at' => now(), 
            'status' => 'active', // Pastikan status aktif
        ]);

        // Data Dummy Pendaftar (Nasabah)
        $dataPendaftar = [
            [
                'username' => 'fauzi99',
                'email' => 'fauzi@example.com',
                'nik' => '3201010101010001',
                'status_pengajuan' => 'draft', // Saya ubah nama key biar ga bingung sama status user
                'produk' => 'Payroll Wadiah'
            ],
            [
                'username' => 'sitiaminah',
                'email' => 'siti@example.com',
                'nik' => '3201010101010002',
                'status_pengajuan' => 'process', 
                'produk' => 'Easy Mudharabah'
            ],
            [
                'username' => 'budi_s',
                'email' => 'budi@example.com',
                'nik' => '3201010101010003',
                'status_pengajuan' => 'ready', 
                'produk' => 'Haji'
            ],
            [
                'username' => 'rina wijaya',
                'email' => 'rina@example.com',
                'nik' => '3201010101010004',
                'status_pengajuan' => 'done', 
                'produk' => 'Tapenas'
            ],
            [
                'username' => 'andi pratama',
                'email' => 'andi@example.com',
                'nik' => '3201010101010005',
                'status_pengajuan' => 'draft', 
                'produk' => 'Easy Wadiah'
            ],
        ];

        foreach ($dataPendaftar as $data) {
            // 3. Buat User Nasabah (Tambahkan status active)
            $userNasabah = User::create([
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make('12345678'),
                'role' => 'Nasabah',
                'email_verified_at' => now(), 
                'status' => 'active', // <--- TAMBAHAN: Set status user jadi active
            ]);

            $nasabah = Nasabah::create([
                'user_id' => $userNasabah->id,
                'nik_ktp' => $data['nik'],
                'tempat_lahir' => 'Bandar Lampung',
                'tanggal_lahir' => '1995-05-10',
                'alamat' => 'Jl. Diponegoro No. ' . rand(1, 50),
                'kode_pos' => '35111',
                'status_pernikahan' => 'Lajang',
                'no_hp' => '0812' . rand(11111111, 99999999),
                'nama_ibu' => 'Ibu Kandung ' . $data['username'],
                'nama_keluarga_tidak_serumah' => 'Keluarga ' . $data['username'],
                'alamat_keluarga_tidak_serumah' => 'Alamat Keluarga...',
                'no_hp_keluarga_tidak_serumah' => '0899' . rand(11111111, 99999999),
            ]);

            $pengajuan = PengajuanRek::create([
                'nasabah_id' => $nasabah->id,
                'jenis_produk' => $data['produk'],
                'no_rek' => in_array($data['status_pengajuan'], ['ready', 'done']) ? '7' . rand(100000000, 999999999) : null,
                'status' => $data['status_pengajuan'], // Pakai status_pengajuan
                'tanggal_input' => Carbon::now()->subDays(rand(1, 5)),
            ]);

            // Log Awal (Draft)
            StatusLog::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id' => $userNasabah->id, 
                'status_lama' => '-',
                'status_baru' => 'draft',
                'catatan' => 'Nasabah melakukan pendaftaran mandiri melalui sistem SiFunding.',
                'created_at' => $pengajuan->tanggal_input,
            ]);

            // Log Lanjutan (Jika status bukan draft)
            if ($data['status_pengajuan'] !== 'draft') {
                StatusLog::create([
                    'pengajuan_id' => $pengajuan->id,
                    'user_id' => $funding->id,
                    'status_lama' => 'draft',
                    'status_baru' => $data['status_pengajuan'],
                    'catatan' => 'Status berkas diperbarui oleh petugas funding untuk proses selanjutnya.',
                    'created_at' => Carbon::now(),
                ]);
            }
        }
    }
}