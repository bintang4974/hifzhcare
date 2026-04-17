<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Data Santri</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 3px solid #3B82F6;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            color: #1F2937;
            margin-bottom: 5px;
        }

        .header .subtitle {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 3px;
        }

        .header .address {
            font-size: 9px;
            color: #9CA3AF;
        }

        .report-info {
            background: #F3F4F6;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .report-info table {
            width: 100%;
        }

        .report-info td {
            padding: 3px 0;
            font-size: 9px;
        }

        .report-info td:first-child {
            width: 150px;
            font-weight: bold;
            color: #4B5563;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data-table thead {
            background: #3B82F6;
            color: white;
        }

        table.data-table th {
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            font-weight: 600;
            border: 1px solid #2563EB;
        }

        table.data-table td {
            padding: 6px 5px;
            border: 1px solid #E5E7EB;
            font-size: 9px;
        }

        table.data-table tbody tr:nth-child(even) {
            background: #F9FAFB;
        }

        table.data-table tbody tr:hover {
            background: #F3F4F6;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 600;
        }

        .badge-active {
            background: #DEF7EC;
            color: #03543F;
        }

        .badge-alumni {
            background: #E1EFFE;
            color: #1E429F;
        }

        .badge-inactive {
            background: #FEE2E2;
            color: #991B1B;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #9CA3AF;
            padding: 10px 0;
            border-top: 1px solid #E5E7EB;
        }

        .page-number:before {
            content: "Halaman " counter(page);
        }

        .summary-box {
            background: #EFF6FF;
            border-left: 4px solid #3B82F6;
            padding: 10px;
            margin-bottom: 15px;
        }

        .summary-box h3 {
            font-size: 11px;
            color: #1F2937;
            margin-bottom: 8px;
        }

        .summary-stats {
            display: flex;
            justify-content: space-around;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #3B82F6;
        }

        .stat-label {
            font-size: 8px;
            color: #6B7280;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $pesantren->name }}</h1>
        <div class="subtitle">LAPORAN DATA SANTRI</div>
        <div class="address">{{ $pesantren->address }}</div>
    </div>

    <!-- Report Info -->
    <div class="report-info">
        <table>
            <tr>
                <td>Tanggal Cetak</td>
                <td>: {{ $generated_at->format('d F Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <td>Total Santri</td>
                <td>: {{ count($santris) }} orang</td>
            </tr>
        </table>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-box">
        <h3>Ringkasan Data</h3>
        <table style="width: 100%; font-size: 9px;">
            <tr>
                <td style="text-align: center;">
                    <div class="stat-value">{{ $santris->where('status', 'active')->count() }}</div>
                    <div class="stat-label">Aktif</div>
                </td>
                <td style="text-align: center;">
                    <div class="stat-value">{{ $santris->where('status', 'alumni')->count() }}</div>
                    <div class="stat-label">Alumni</div>
                </td>
                <td style="text-align: center;">
                    <div class="stat-value">{{ $santris->where('status', 'inactive')->count() }}</div>
                    <div class="stat-label">Keluar</div>
                </td>
                <td style="text-align: center;">
                    <div class="stat-value">{{ number_format($santris->avg('progress_percentage'), 1) }}%</div>
                    <div class="stat-label">Rata-rata Progress</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 10%;">NIS</th>
                <th style="width: 18%;">Nama Lengkap</th>
                <th style="width: 10%;">Kelas</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 15%;">Wali</th>
                <th style="width: 12%;">Kontak Wali</th>
                <th style="width: 8%;">Progress</th>
                <th style="width: 8%;">Hafalan</th>
                <th style="width: 8%;">Sertifikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($santris as $index => $santri)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $santri->nis }}</td>
                    <td><strong>{{ $santri->user->name }}</strong></td>
                    <td>{{ $santri->classModel->name ?? '-' }}</td>
                    <td>
                        @if ($santri->status === 'active')
                            <span class="badge badge-active">Aktif</span>
                        @elseif($santri->status === 'alumni')
                            <span class="badge badge-alumni">Alumni</span>
                        @else
                            <span class="badge badge-inactive">Keluar</span>
                        @endif
                    </td>
                    <td>{{ $santri->wali->user->name ?? '-' }}</td>
                    <td>{{ $santri->wali->user->phone ?? '-' }}</td>
                    <td style="text-align: center;">
                        <strong>{{ number_format($santri->progress_percentage, 1) }}%</strong></td>
                    <td style="text-align: center;">{{ $santri->verified_count ?? 0 }}</td>
                    <td style="text-align: center;">{{ $santri->certificates_count ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div>Dicetak oleh: {{ auth()->user()->name }} | {{ $pesantren->name }}</div>
        <div class="page-number"></div>
    </div>
</body>

</html>
