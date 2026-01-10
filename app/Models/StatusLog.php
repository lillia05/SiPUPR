<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusLog extends Model
{
    use HasFactory;

    protected $table = 'status_logs';
    protected $fillable = [
        'pengajuan_id',
        'user_id',
        'status_lama',
        'status_baru',
        'catatan',
    ];

    /**
     * Relasi ke Pengajuan
     */
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanRek::class, 'pengajuan_id');
    }

    /**
     * Relasi ke User (Petugas yang mengubah status)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}