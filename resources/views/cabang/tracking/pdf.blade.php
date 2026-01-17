<!DOCTYPE html>
<html>
<head>
    <title>Tanda Terima Distribusi Tabungan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 12px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table, th, td { border: 1px solid #000; }
        th { background-color: #f2f2f2; padding: 8px; text-align: left; font-weight: bold; }
        td { padding: 6px 8px; }
        
        .footer { margin-top: 40px; width: 100%; }
        .ttd-box { float: right; width: 200px; text-align: center; }
        .ttd-line { margin-top: 60px; border-bottom: 1px solid #000; }
    </style>
</head>
<body>

    <div class="header">
        <h1>BSI Funding System</h1>
        <p>Laporan Serah Terima Buku Tabungan</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <p>Berikut adalah daftar nasabah yang buku tabungannya telah selesai diproses dan siap/telah diserahkan:</p>

    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 20%;">Nama Nasabah</th>
                <th style="width: 15%;">NIK</th>
                <th style="width: 15%;">No. Rekening</th>
                <th style="width: 15%;">Jenis Produk</th>
                <th style="width: 15%;">No. HP</th>
                <th style="width: 15%; text-align: center;">Paraf</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data_nasabah as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->user->name ?? $item->user->username ?? '-' }}</td>
                <td>{{ $item->nik_ktp }}</td>
                <td>{{ $item->pengajuan->first()->no_rek ?? '-' }}</td>
                <td>{{ $item->pengajuan->first()->jenis_produk ?? '-' }}</td>
                <td>{{ $item->no_hp }}</td>
                <td></td> </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Belum ada data nasabah yang selesai.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="ttd-box">
            <p>Mengetahui,<br>Funding Officer</p>
            <div class="ttd-line"></div>
            <p>( ................................. )</p>
        </div>
    </div>

</body>
</html>