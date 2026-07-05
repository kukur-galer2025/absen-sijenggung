<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Presensi - Desa Sijenggung</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; }
        
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #2d7a4f; padding-bottom: 10px; position: relative; }
        .header::after { content: ""; position: absolute; left: 0; right: 0; bottom: -5px; border-bottom: 1px solid #2d7a4f; }
        .header h1 { font-size: 18px; color: #166534; margin-bottom: 2px; letter-spacing: 1px; }
        .header h2 { font-size: 14px; color: #333; margin-bottom: 2px; }
        .header p { font-size: 10px; color: #555; }
        .header .subtitle { font-size: 12px; color: #166534; margin-top: 10px; font-weight: bold; text-decoration: underline; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { 
            background-color: #15803d; 
            color: white; 
            padding: 8px 4px; 
            text-align: left; 
            font-size: 9px; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
        }
        td { padding: 6px 4px; border-bottom: 1px solid #e5e7eb; font-size: 9px; vertical-align: middle; }
        tr:nth-child(even) { background-color: #f9fafb; }
        
        .status-hadir { 
            background-color: #d1fae5; 
            color: #065f46; 
            padding: 2px 6px; 
            border-radius: 4px; 
            font-size: 8px; 
            font-weight: bold; 
            display: inline-block;
        }
        .status-terlambat { 
            background-color: #ffedd5; 
            color: #9a3412; 
            padding: 2px 6px; 
            border-radius: 4px; 
            font-size: 8px; 
            font-weight: bold; 
            display: inline-block;
        }
        
        .footer { 
            margin-top: 40px; 
            width: 100%;
            font-size: 10px; 
            color: #333; 
        }
        .footer-content {
            float: right;
            text-align: center;
            width: 250px;
        }
        .footer .sign { margin-top: 60px; }
        .footer .sign-name { font-weight: bold; text-decoration: underline; margin-bottom: 2px; }

        .info-box { 
            background-color: #ecfdf5; 
            border: 1px solid #a7f3d0; 
            border-radius: 5px; 
            padding: 8px 12px; 
            margin-bottom: 15px; 
            font-size: 10px; 
        }
        .info-box span { font-weight: bold; color: #2d7a4f; }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEMERINTAH DESA SIJENGGUNG</h1>
        <h2>Kecamatan Banjarmangu — Kabupaten Banjarnegara</h2>
        <p>Jl. Jenggawur - Banjarmangu No.Km, Sijonggung, Sijenggung, Jawa Tengah 53452</p>
        <div class="subtitle">LAPORAN PRESENSI PEGAWAI</div>
    </div>

    <div class="info-box">
        <span>Periode:</span> {{ $monthName }} &nbsp;&nbsp;|&nbsp;&nbsp;
        <span>Pegawai:</span> {{ $employeeName }} &nbsp;&nbsp;|&nbsp;&nbsp;
        <span>Total Data:</span> {{ $attendances->count() }} record &nbsp;&nbsp;|&nbsp;&nbsp;
        <span>Dicetak:</span> {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Tanggal</th>
                <th>Nama Pegawai</th>
                <th>NIP</th>
                <th>Jabatan</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Status</th>
                <th>Lokasi Masuk</th>
                <th>Lokasi Pulang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $i => $record)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $record->date->translatedFormat('d M Y') }}</td>
                <td>{{ $record->user->name }}</td>
                <td>{{ $record->user->nip ?? '-' }}</td>
                <td>{{ $record->user->jabatan ?? '-' }}</td>
                <td>{{ $record->check_in ?? '-' }}</td>
                <td>{{ $record->check_out ?? '-' }}</td>
                <td><span class="status-{{ strtolower($record->status) == 'hadir' ? 'hadir' : (strtolower($record->status) == 'terlambat' ? 'terlambat' : 'hadir') }}">{{ ucfirst($record->status) }}</span></td>
                <td>
                    @if($record->check_in_latitude && $record->check_in_longitude)
                        {{ number_format($record->check_in_latitude, 5) }}, {{ number_format($record->check_in_longitude, 5) }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($record->check_out_latitude && $record->check_out_longitude)
                        {{ number_format($record->check_out_latitude, 5) }}, {{ number_format($record->check_out_longitude, 5) }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px; color: #999;">Tidak ada data presensi untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div class="footer-content">
            <p>Sijenggung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Mengetahui,</p>
            <div class="sign">
                <p class="sign-name">Kepala Desa Sijenggung</p>
                <p>NIP. .....................................</p>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>
