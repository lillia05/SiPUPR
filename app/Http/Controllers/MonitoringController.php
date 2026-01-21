<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Nasabah;
use App\Models\PengajuanRek;
use App\Models\StatusLog;
use App\Models\Batch;
use App\Models\PenerimaBantuan; 

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; 

class MonitoringController extends Controller
{
    public function index()
    {
        $totalNasabah = User::where('role', 'Nasabah')->count();

        $pendingCount = PengajuanRek::whereIn('status', ['draft', 'process'])->count();
        $readyCount = PengajuanRek::where('status', 'ready')->count();
        $doneCount = PengajuanRek::where('status', 'done')->count();

        $antreanTerbaru = PengajuanRek::with('nasabah.user')
                            ->latest()
                            ->take(5)
                            ->get();

        return view('cabang.dashboard', compact(
            'totalNasabah', 
            'pendingCount', 
            'readyCount', 
            'doneCount', 
            'antreanTerbaru'
        ));
    }

    /**
     * Halaman Tracking Bantuan 
     * Menggunakan tabel PenerimaBantuan & Batch
     */
    public function trackingPage(Request $request)
    {
        $batches = Batch::orderBy('id', 'asc')->get();
        
        $activeBatchId = $request->query('batch_id', $batches->last()->id ?? null);

        $query = PenerimaBantuan::with(['tahapan', 'batch']);

        // Filter by Batch (Jika ada batch)
        if ($activeBatchId) {
            $query->where('batch_id', $activeBatchId);
        }

        // --- FILTER KOLOM ---
        if ($request->filled('f_nama')) {
            $query->where('nama_pb', 'like', '%' . $request->f_nama . '%');
        }
        if ($request->filled('f_deli')) {
            $query->where('deliniasi', 'like', '%' . $request->f_deli . '%');
        }
        if ($request->filled('f_kab')) {
            $query->where('kabupaten', 'like', '%' . $request->f_kab . '%');
        }
        if ($request->filled('f_kec')) {
            $query->where('kecamatan', 'like', '%' . $request->f_kec . '%');
        }
        if ($request->filled('f_desa')) {
            $query->where('desa', 'like', '%' . $request->f_desa . '%');
        }

        // --- FILTER STATUS TAHAPAN (Complex Relationship Query) ---
        foreach ([1, 2, 3] as $tahap) {
            $paramKey = "f_tahap_$tahap"; 
            
            if ($request->filled($paramKey)) {
                $statusFilter = $request->$paramKey; 

                if ($statusFilter == 'DONE') {
                    $query->whereHas('tahapan', function($q) use ($tahap) {
                        $q->where('tahap_ke', $tahap)->where('status', 'DONE');
                    });
                } else {
                    $query->whereDoesntHave('tahapan', function($q) use ($tahap) {
                        $q->where('tahap_ke', $tahap)->where('status', 'DONE');
                    });
                }
            }
        }

        $perPage = $request->input('per_page', 10);
        $penerima = $query->latest()->paginate($perPage)->withQueryString();

        return view('cabang.tracking.index', compact('batches', 'activeBatchId', 'penerima'));
    }

    /**
     * Halaman Detail Penerima Bantuan
     */
    public function show($id)
    {
        $penerima = PenerimaBantuan::with(['batch', 'tahapan'])->findOrFail($id);
        
        return view('cabang.tracking.show', compact('penerima'));
    }


    
    public function updateStatus(Request $request, $id)
    {
        $pengajuan = PengajuanRek::findOrFail($id);
        $statusLama = $pengajuan->status;
        $statusBaru = $request->status;

        if ($statusBaru == 'ready') {
            $request->validate([
                'no_rek' => 'required|numeric|digits_between:10,20|unique:pengajuan_rek,no_rek,' . $id
            ], [
                'no_rek.required' => 'Nomor rekening wajib diisi setelah dicetak.',
                'no_rek.numeric' => 'Nomor rekening harus berupa angka.',
                'no_rek.unique' => 'Nomor rekening sudah terdaftar.'
            ]);
        }

        $dataUpdate = [
            'status' => $statusBaru,
            'tanggal_serah_terima' => ($statusBaru == 'done') ? now() : $pengajuan->tanggal_serah_terima
        ];

        if ($request->filled('no_rek')) {
            $dataUpdate['no_rek'] = $request->no_rek;
        }

        $pengajuan->update($dataUpdate);

        $catatan = $request->catatan ?? "Status berkas diperbarui menjadi " . ucfirst($statusBaru);
        if ($request->filled('no_rek')) {
            $catatan .= ". No Rek: " . $request->no_rek;
        }

        StatusLog::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id' => auth()->id(),
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'catatan' => $catatan,
        ]);

        return redirect()->back()->with('success', 'Status Berhasil Diperbarui!');
    }

    public function doTracking(Request $request)
    {
        $prefix = strtolower(auth()->user()->role); 
        $search = $request->query('search');

        if (!$search) {
            return redirect()->route($prefix . '.tracking.index')->with('error', 'Silakan pilih nasabah terlebih dahulu.');
        }

        $pengajuan = PengajuanRek::with(['nasabah.user', 'logs.user'])
            ->whereHas('nasabah', function ($q) use ($search) {
                $q->where('nik_ktp', $search);
            })->first();

        if (!$pengajuan) {
            return redirect()->route($prefix . '.tracking.index')->with('error', 'Data nasabah tidak ditemukan.');
        }

        return view('cabang.tracking.show_old', compact('pengajuan'));
    }

    public function cetakPdf()
    {
        $data_nasabah = PenerimaBantuan::with(['tahapan', 'batch'])->get();

        $pdf = Pdf::loadView('cabang.tracking.pdf', compact('data_nasabah'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Penyaluran_'.date('d-m-Y').'.pdf');
    }
}