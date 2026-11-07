<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peternakan</title>
    <style>
        body { font-family: sans-serif; font-size: 10pt; color: #333; margin: 0; padding: 0; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .header { margin-bottom: 20px; border-bottom: 2px solid #0e6251; padding-bottom: 10px; }
        .title { font-size: 14pt; color: #0e6251; font-weight: bold; margin: 0; }
        .subtitle { font-size: 11pt; font-weight: bold; margin-top: 5px; }
        
        .stats-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .stats-box { background: #f8f9fa; border: 1px solid #ddd; padding: 8px; text-align: left; }
        .stats-label { font-size: 7.5pt; color: #666; font-weight: bold; text-transform: uppercase; }
        .stats-val { font-size: 11pt; font-weight: bold; margin-top: 3px; }

        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #dee2e6; padding: 7px 5px; text-align: center; font-size: 8.5pt; }
        .table th { background-color: #f1f3f5; font-size: 7.5pt; text-transform: uppercase; }

        .badge-layak { color: #15803d; font-weight: bold; }
        .badge-belum { color: #b91c1c; font-weight: bold; }

        .signature-section { margin-top: 30px; float: right; width: 250px; text-align: center; }
    </style>
</head>
<body>

    <div class="header text-center">
        <div class="title">PT. SUMBER MAKANAN SEHAT</div>
        <div class="subtitle">LAPORAN KONDISI DAN NILAI JUAL HEWAN TERNAK</div>
        <small style="color: #666;">Periode Cetak: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</small>
    </div>

    <!-- Stats Ringkasan -->
    <table class="stats-table">
        <tr>
            <td width="25%">
                <div class="stats-box">
                    <div class="stats-label">Total Ternak</div>
                    <div class="stats-val">{{ $totalTernak }} ekor</div>
                </div>
            </td>
            <td width="25%">
                <div class="stats-box">
                    <div class="stats-label">Layak Jual</div>
                    <div class="stats-val">{{ $totalLayak }} ekor</div>
                </div>
            </td>
            <td width="25%">
                <div class="stats-box">
                    <div class="stats-label">Belum Layak</div>
                    <div class="stats-val">{{ $totalTidakLayak }} ekor</div>
                </div>
            </td>
            <td width="25%">
                <div class="stats-box">
                    <div class="stats-label">Estimasi Nilai Total</div>
                    <div class="stats-val">Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tabel Data -->
    <table class="table">
        <thead>
            <tr>
                <th width="4%">NO</th>
                <th width="12%">ID TERNAK</th>
                <th width="15%">NAMA</th>
                <th width="15%">JENIS</th>
                <th width="10%">UMUR</th>
                <th width="10%">BOBOT</th>
                <th width="12%">KELAYAKAN</th>
                <th width="10%">PROBABILITAS</th>
                <th width="12%">ESTIMASI HARGA</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                @php 
                    $isLayak = ($item->kelayakan == 1 || strtolower($item->kelayakan ?? '') == 'layak');
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><b>{{ $item->kode_ternak ?? $item->id_ternak ?? '-' }}</b></td>
                    <td>{{ $item->nama_ternak ?? $item->nama ?? '-' }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>{{ $item->umur ?? '-' }} bulan</td>
                    <td>{{ $item->bobot ?? $item->berat ?? '-' }} kg</td>
                    <td>
                        <span class="{{ $isLayak ? 'badge-layak' : 'badge-belum' }}">
                            {{ $isLayak ? 'Layak' : 'Belum Layak' }}
                        </span>
                    </td>
                    <td><b>{{ $item->probabilitas ?? $item->persentase ?? '0' }}%</b></td>
                    <td><b>Rp {{ number_format($item->estimasi_harga ?? $item->harga_jual ?? 0, 0, ',', '.') }}</b></td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="padding: 20px; color: #888;">Belum ada data laporan ternak.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan Manajer -->
    <div class="signature-section">
        <p style="margin-bottom: 50px;">Subang, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}<br><b>Manajer Peternakan</b></p>
        <p>( ____________________ )</p>
    </div>

</body>
</html>