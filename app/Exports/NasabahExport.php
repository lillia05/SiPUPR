<?php

namespace App\Exports;

use App\Models\Nasabah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NasabahExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    private $rowNumber = 0;
    public function collection()
    {
        return Nasabah::with(['user', 'pengajuan', 'pekerjaan'])->get();
    }

    public function map($nasabah): array
    {
        $this->rowNumber++;
        $pengajuan = $nasabah->pengajuan->first();
        $pekerjaan = $nasabah->pekerjaan;

        return [
            $this->rowNumber,                                       
            $nasabah->user->username ?? $nasabah->user->name,   
            $nasabah->user->email,                              
            "'" . $nasabah->nik_ktp,                            
            $nasabah->npwp ?? '-',                              
            $nasabah->tempat_lahir,                             
            
            $nasabah->tanggal_lahir ? $nasabah->tanggal_lahir->format('d-m-Y') : '-', 
            
            "'" . $nasabah->no_hp,                              
            $nasabah->alamat,                                   
            $nasabah->kode_pos,                                 
            $nasabah->status_pernikahan,                        
            $nasabah->nama_ibu,                                 
            $nasabah->nama_keluarga_tidak_serumah,              
            "'" . $nasabah->no_hp_keluarga_tidak_serumah,       
            $nasabah->alamat_keluarga_tidak_serumah,            
            $pekerjaan ? $pekerjaan->area_kerja : '-',          
            $pekerjaan ? $pekerjaan->jabatan : '-',             
            $nasabah->rek_bsi_lama ? "'" . $nasabah->rek_bsi_lama : '-', 
            $pengajuan ? $pengajuan->jenis_produk : '-',        
        ];
    }

    public function headings(): array
    {
        return [
            'No',
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

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}