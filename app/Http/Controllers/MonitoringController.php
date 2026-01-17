<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Nasabah;
use App\Models\PengajuanRek;
use App\Models\StatusLog;
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
        $query = PengajuanRek::with(['nasabah.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('nasabah', function ($q) use ($search) {
                $q->where('nik_ktp', 'like', "%$search%")
                ->orWhereHas('user', function($u) use ($search) {
                    $u->where('username', 'like', "%$search%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 10);
        
        $pengajuans = $query->latest()->paginate($perPage);

        $pengajuans->appends($request->all());

        return view('cabang.tracking.index', compact('pengajuans'));
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

        return view('cabang.tracking.show', compact('pengajuan'));
    }

    public function cetakPdf()
    {
        $data_nasabah = Nasabah::with(['user', 'pengajuan'])
                        ->whereHas('pengajuan', function($q) {
                            $q->whereIn('status', ['done']);
                        })
                        ->get();

        $pdf = Pdf::loadView('cabang.tracking.pdf', compact('data_nasabah'));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Tanda_Terima_Tabungan_'.date('d-m-Y').'.pdf');
    }

    public function cetakPdfDetail($id)
    {
        $pengajuan = PengajuanRek::with(['nasabah.user'])->findOrFail($id);

        $data_nasabah = collect([$pengajuan->nasabah]);

        $pdf = Pdf::loadView('cabang.tracking.pdf', compact('data_nasabah'));
        $pdf->setPaper('A4', 'portrait');

        $namaFile = 'Tanda_Terima_' . str_replace(' ', '_', $pengajuan->nasabah->user->name) . '.pdf';

        return $pdf->download($namaFile);
    }
}