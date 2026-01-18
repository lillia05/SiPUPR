<?php

namespace App\Http\Controllers;

use App\Models\PenerimaBantuan;
use App\Models\TahapanPenyaluran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\NasabahExport; 
use App\Imports\NasabahImport; 
use Maatwebsite\Excel\Facades\Excel;

class NasabahController extends Controller
{
    public function index(Request $request)
    {
        $query = PenerimaBantuan::with('tahapan');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_pb', 'like', "%{$search}%")
                  ->orWhere('nomor_rekening', 'like', "%{$search}%")
                  ->orWhere('desa', 'like', "%{$search}%")
                  ->orWhere('kecamatan', 'like', "%{$search}%")
                  ->orWhere('kabupaten', 'like', "%{$search}%")
                  ->orWhere('deliniasi', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $nasabah = $query->latest()->paginate($perPage);
        
        $nasabah->appends($request->all());

        $prefix = strtolower(auth()->user()->role); 
        
        return view($prefix . '.nasabah.index', compact('nasabah'));
    }

    public function create()
    {
        $prefix = strtolower(auth()->user()->role);
        return view($prefix . '.nasabah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pb' => 'required|string|max:255',
            'nomor_rekening' => 'required|numeric|unique:penerima_bantuan,nomor_rekening',
            'deliniasi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'desa' => 'required|string',
            'total_alokasi_bantuan' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $penerima = PenerimaBantuan::create([
                    'nama_pb' => $request->nama_pb,
                    'nomor_rekening' => $request->nomor_rekening,
                    'deliniasi' => $request->deliniasi,
                    'kabupaten' => $request->kabupaten,
                    'kecamatan' => $request->kecamatan,
                    'desa' => $request->desa,
                    'total_alokasi_bantuan' => $request->total_alokasi_bantuan,
                ]);

                $persentase = [10000000, 7500000, 2500000]; 
                foreach ($persentase as $index => $nominal) {
                    TahapanPenyaluran::create([
                        'penerima_bantuan_id' => $penerima->id,
                        'tahap_ke' => $index + 1,
                        'nominal' => $nominal,
                        'status' => 'not',
                    ]);
                }
            });

            $prefix = strtolower(auth()->user()->role); 
            return redirect()->route($prefix . '.nasabah.index')
                             ->with('success', 'Data Penerima Bantuan berhasil disimpan!');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                             ->withInput();
        }
    }

    public function show($id)
    {
        $nasabah = PenerimaBantuan::with(['tahapan' => function($q) {
            $q->orderBy('tahap_ke', 'asc');
        }])->findOrFail($id);

        $prefix = strtolower(auth()->user()->role);
        return view($prefix . '.nasabah.show', compact('nasabah'));
    }

    public function edit($id)
    {
        $nasabah = PenerimaBantuan::findOrFail($id);
        $prefix = strtolower(auth()->user()->role);
        return view($prefix . '.nasabah.edit', compact('nasabah'));
    }

    public function update(Request $request, $id)
    {
        $penerima = PenerimaBantuan::findOrFail($id);

        $request->validate([
            'nama_pb' => 'required|string|max:255',
            'nomor_rekening' => 'required|numeric|unique:penerima_bantuan,nomor_rekening,' . $id,
            'deliniasi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'desa' => 'required|string',
            'total_alokasi_bantuan' => 'required|numeric|min:0',
        ]);

        try {
            $penerima->update([
                'nama_pb' => $request->nama_pb,
                'nomor_rekening' => $request->nomor_rekening,
                'deliniasi' => $request->deliniasi,
                'kabupaten' => $request->kabupaten,
                'kecamatan' => $request->kecamatan,
                'desa' => $request->desa,
                'total_alokasi_bantuan' => $request->total_alokasi_bantuan,
            ]);

            $prefix = strtolower(auth()->user()->role);
            return redirect()->route($prefix . '.nasabah.index')
                             ->with('success', 'Data berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Gagal update: ' . $e->getMessage())
                             ->withInput();
        }
    }

    public function destroy($id)
    {
        $penerima = PenerimaBantuan::findOrFail($id);
        
        $penerima->delete(); 
        
        return redirect()->back()->with('success', 'Data penerima bantuan telah dihapus.');
    }

    public function export() 
    {
        return Excel::download(new NasabahExport, 'data_penerima_bantuan_' . date('d-m-Y_H-i') . '.xlsx');
    }

    public function import(Request $request) 
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new NasabahImport, $request->file('file_excel'));
            return redirect()->back()->with('success', 'Data berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}