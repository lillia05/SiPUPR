<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanRek extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_rek';

    protected $fillable = [
        'nasabah_id',
        'jenis_produk',
        'no_rek',
        'status',
        'tanggal_input',
        'tanggal_serah_terima',
    ];

    protected $casts = [
        'tanggal_input' => 'datetime',
        'tanggal_serah_terima' => 'datetime',
    ];

    /**
     * Relasi ke Nasabah (Mengambil data pemilik berkas)
     */
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }

    /**
     * Relasi ke Logs (Riwayat perubahan status untuk fitur tracking)
     */
    public function logs()
    {
        return $this->hasMany(StatusLog::class, 'pengajuan_id');
    }
}