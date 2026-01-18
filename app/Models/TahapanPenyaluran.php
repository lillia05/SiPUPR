<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TahapanPenyaluran extends Model
{
    use HasFactory;

    protected $table = 'tahapan_penyaluran';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_transaksi' => 'date',      
        'nominal' => 'decimal:2',           
    ];
    public function penerima(): BelongsTo
    {
        return $this->belongsTo(PenerimaBantuan::class, 'penerima_bantuan_id');
    }
}