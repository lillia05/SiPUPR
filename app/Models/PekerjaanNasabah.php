<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PekerjaanNasabah extends Model
{
    use HasFactory;

    protected $table = 'pekerjaan_nasabah';

    protected $fillable = [
        'nasabah_id',
        'area_kerja', 
        'jabatan',         
    ];
}
