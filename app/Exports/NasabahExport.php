<?php

namespace App\Exports;

use App\Models\PenerimaBantuan;
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
        return PenerimaBantuan::with('tahapan')->get();
    }

    public function map($penerima): array
    {
        $this->rowNumber++;
        
        return [
            $this->rowNumber,                                       
            $penerima->nama_pb,   
            $penerima->nomor_rekening, 
            $penerima->deliniasi,                            
            $penerima->kabupaten,                              
            $penerima->kecamatan,                             
            $penerima->desa,
            $penerima->total_alokasi_bantuan,
            $penerima->progress_terkini,
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Penerima',
            'Nomor Rekening',
            'Deliniasi',
            'Kabupaten',
            'Kecamatan',
            'Desa',
            'Total Alokasi Bantuan',
            'Progress Terkini',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}