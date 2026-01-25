<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenerimaBantuan extends Model
{
    use HasFactory;

    protected $table = 'penerima_bantuan';
    
    protected $guarded = ['id']; 

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

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

    /**
     */
    public function getStatusTahap($tahapKe)
    {
        $tahap = $this->tahapan->firstWhere('tahap_ke', $tahapKe);
        return $tahap ? $tahap->status : 'NOT';
    }
}