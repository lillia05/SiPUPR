<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    use HasFactory;

    protected $table = 'nasabah';

    protected $fillable = [
        'user_id',
        'nik_ktp',
        'npwp',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'kode_pos',
        'status_pernikahan',
        'no_hp',
        'nama_ibu',
        'rek_bsi_lama',
        'nama_keluarga_tidak_serumah',
        'alamat_keluarga_tidak_serumah',
        'no_hp_keluarga_tidak_serumah',
        'foto_ktp',
        'foto_npwp',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Relasi ke User (Akun login/entitas pendaftar).
     * Nasabah memiliki satu akun user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Pengajuan Rekening.
     * Satu nasabah bisa memiliki satu atau lebih pengajuan produk.
     */
    public function pengajuan()
    {
        return $this->hasMany(PengajuanRek::class, 'nasabah_id');
    }

    /**
     * Relasi ke Pekerjaan Nasabah.
     */
    public function pekerjaan()
    {
        return $this->hasOne(PekerjaanNasabah::class, 'nasabah_id');
    }
}
