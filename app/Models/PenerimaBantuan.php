<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenerimaBantuan extends Model
{
    use HasFactory;

    protected $table = 'penerima_bantuan';
    
    protected $guarded = ['id']; 

    /**
     * Relasi ke tabel tahapan (One to Many)
     */
    public function tahapan(): HasMany
    {
        return $this->hasMany(TahapanPenyaluran::class, 'penerima_bantuan_id');
    }

    public function getSisaSaldoAttribute()
    {
        $totalDisalurkan = $this->tahapan
            ->where('status', 'DONE')
            ->sum('nominal');

        return $this->total_alokasi_bantuan - $totalDisalurkan;
    }

    public function getProgressTerkiniAttribute()
    {
        $lastStage = $this->tahapan
            ->where('status', 'DONE')
            ->sortByDesc('tahap_ke')
            ->first();

        return $lastStage ? "Tahap " . $lastStage->tahap_ke : "Belum Cair";
    }
}