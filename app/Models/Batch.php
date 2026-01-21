<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $table = 'batches';

    protected $fillable = [
        'nama_batch',
        'tanggal_mulai',
    ];

    // Casting agar tanggal otomatis menjadi objek Carbon (mudah diformat)
    protected $casts = [
        'tanggal_mulai' => 'date',
    ];

    /**
     * Relasi One-to-Many: 
     * Satu Batch memiliki banyak data Penerima Bantuan.
     */
    public function penerimaBantuan()
    {
        return $this->hasMany(PenerimaBantuan::class, 'batch_id');
    }
}