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

    public function trackingPage(Request $request)
    {
        $batches = Batch::orderBy('id', 'asc')->get();
        
        $activeBatchId = $request->query('batch_id'); 

        $query = PenerimaBantuan::with(['tahapan', 'batch']);

        if ($activeBatchId) {
            $query->where('batch_id', $activeBatchId);
        }

        if ($request->filled('f_nama')) {
        $keyword = $request->f_nama;
        
        $query->where(function($q) use ($keyword) {
            $q->where('nama_pb', 'like', '%' . $keyword . '%')
              ->orWhere('nomor_rekening', 'like', '%' . $keyword . '%')
              ->orWhere('deliniasi', 'like', '%' . $keyword . '%')
              ->orWhere('kabupaten', 'like', '%' . $keyword . '%')
              ->orWhere('kecamatan', 'like', '%' . $keyword . '%')
              ->orWhere('desa', 'like', '%' . $keyword . '%');
        });
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
        $prefix = strtolower(auth()->user()->role);
        return view($prefix . '.tracking.index', compact('batches', 'activeBatchId', 'penerima'));
    }

    public function show($id)
    {
        $penerima = PenerimaBantuan::with(['batch', 'tahapan'])->findOrFail($id);
        $prefix = strtolower(auth()->user()->role);
        return view($prefix . '.tracking.show', compact('penerima'));
    }

    public function updateTahapan(Request $request, $id)
    {
        $request->validate([
            'tahap_ke' => 'required|in:1,2,3',
            'status'   => 'required|in:DONE,NOT',
        ]);

        $penerima = PenerimaBantuan::findOrFail($id);
        $tahapKe = (int)$request->tahap_ke;

        if ($request->status == 'DONE' && $tahapKe > 1) {
            $prevTahap = $penerima->tahapan()->where('tahap_ke', $tahapKe - 1)->first();
            if (!$prevTahap || $prevTahap->status !== 'DONE') {
                return redirect()->back()->with('error', "Gagal! Tahap " . ($tahapKe - 1) . " harus selesai dulu.");
            }
        }

        if ($request->status == 'NOT' && $tahapKe < 3) {
            $nextTahap = $penerima->tahapan()->where('tahap_ke', $tahapKe + 1)->first();
            if ($nextTahap && $nextTahap->status == 'DONE') {
                return redirect()->back()->with('error', "Gagal! Tahap " . ($tahapKe + 1) . " harus dibatalkan terlebih dahulu.");
            }
        }

        $defaultNominal = 0;
        if ($tahapKe == 1) $defaultNominal = 10000000;
        elseif ($tahapKe == 2) $defaultNominal = 7500000;
        elseif ($tahapKe == 3) $defaultNominal = 2500000;

        $existingTahap = $penerima->tahapan()->where('tahap_ke', $tahapKe)->first();
        
        $nominalToSave = $request->filled('nominal') 
                            ? $request->nominal 
                            : ($existingTahap ? $existingTahap->nominal : $defaultNominal);

        $penerima->tahapan()->updateOrCreate(
            ['tahap_ke' => $tahapKe],
            [
                'status' => $request->status,
                'nominal' => $nominalToSave,
                'tanggal_transaksi' => ($request->status == 'DONE') ? now() : null 
            ]
        );

        $msg = ($request->status == 'DONE') ? "selesai" : "dibatalkan";
        return redirect()->back()->with('success', "Status Tahap $tahapKe berhasil $msg!");
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