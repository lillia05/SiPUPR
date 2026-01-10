<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Nasabah;
use App\Models\PekerjaanNasabah;
use App\Models\PengajuanRek;
use App\Models\StatusLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class NasabahImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return DB::transaction(function() use ($row) {
            
            $user = User::create([
                'username'          => $row['nama_nasabah'], 
                'email'             => $row['email'],
                'password'          => Hash::make('12345678'), 
                'role'              => 'Nasabah',
                'email_verified_at' => now(),
            ]);

            $nasabah = Nasabah::create([
                'user_id'                       => $user->id,
                'nik_ktp'                       => $row['nik'],
                'npwp'                          => $row['npwp'] ?? null,
                'tempat_lahir'                  => $row['tempat_lahir'],
                'tanggal_lahir'                 => $row['tanggal_lahir'], 
                'no_hp'                         => $row['no_hp'],
                'alamat'                        => $row['alamat_domisili'],
                'kode_pos'                      => $row['kode_pos'],
                'status_pernikahan'             => $row['status_pernikahan'],
                'nama_ibu'                      => $row['nama_ibu_kandung'],
                'rek_bsi_lama'                  => $row['rekening_bsi_lama'] ?? null,
                'nama_keluarga_tidak_serumah'   => $row['nama_keluarga_darurat'],
                'no_hp_keluarga_tidak_serumah'  => $row['no_hp_keluarga_darurat'],
                'alamat_keluarga_tidak_serumah' => $row['alamat_keluarga_darurat'],
            ]);

            PekerjaanNasabah::create([
                'nasabah_id' => $nasabah->id,
                'area_kerja' => $row['area_kerja'],
                'jabatan'    => $row['jabatan'],
            ]);

            $pengajuan = PengajuanRek::create([
                'nasabah_id'    => $nasabah->id,
                'jenis_produk'  => $row['jenis_tabungan'], 
                'status'        => 'draft',
                'tanggal_input' => now(),
            ]);

            StatusLog::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id'      => auth()->id() ?? 1, 
                'status_baru'  => 'draft',
                'catatan'      => 'Import data via Excel',
            ]);

            return $nasabah;
        });
    }

    public function rules(): array
    {
        return [
            'Nama Nasabah',
            'Email',
            'NIK',
            'NPWP',
            'Tempat Lahir',
            'Tanggal Lahir',
            'No HP',
            'Alamat Domisili',
            'Kode Pos',
            'Status Pernikahan',
            'Nama Ibu Kandung',
            'Nama Keluarga (Darurat)',
            'No HP Keluarga (Darurat)',
            'Alamat Keluarga (Darurat)',
            'Area Kerja',
            'Jabatan',
            'Rekening BSI Lama',
            'Jenis Tabungan',
        ];
    }
}