<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Summary Sertifikat</title>
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
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 3px solid #F59E0B;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            color: #1F2937;
        }

        table.cert-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table.cert-table thead {
            background: #F59E0B;
            color: white;
        }

        table.cert-table th {
            padding: 8px;
            text-align: left;
            font-size: 9px;
            border: 1px solid #F59E0B;
        }

        table.cert-table td {
            padding: 6px 8px;
            border: 1px solid #E5E7EB;
            font-size: 9px;
        }

        table.cert-table tbody tr:nth-child(even) {
            background: #FFFBEB;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 600;
        }

        .badge-juz {
            background: #E0E7FF;
            color: #3730A3;
        }

        .badge-khatam {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .footer {
            position: fixed;
            bottom: 0;
            text-align: center;
            font-size: 8px;
            color: #9CA3AF;
            padding: 10px 0;
            border-top: 1px solid #E5E7EB;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $pesantren->name }}</h1>
        <div style="font-size: 14px; color: #6B7280;">LAPORAN SUMMARY SERTIFIKAT</div>
    </div>

    <div
        style="background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 12px; margin-bottom: 20px; border-radius: 6px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 33.33%; text-align: center; padding: 10px;">
                    <div style="font-size: 28px; font-weight: bold; color: #F59E0B;">{{ $stats['total'] ?? 0 }}</div>
                    <div style="font-size: 9px; color: #6B7280;">Total Sertifikat</div>
                </td>
                <td style="width: 33.33%; text-align: center; padding: 10px;">
                    <div style="font-size: 28px; font-weight: bold; color: #8B5CF6;">{{ $stats['per_juz'] ?? 0 }}</div>
                    <div style="font-size: 9px; color: #6B7280;">Per Juz</div>
                </td>
                <td style="width: 33.33%; text-align: center; padding: 10px;">
                    <div style="font-size: 28px; font-weight: bold; color: #3B82F6;">{{ $stats['khatam'] ?? 0 }}</div>
                    <div style="font-size: 9px; color: #6B7280;">Khatam</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="cert-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Nomor Sertifikat</th>
                <th style="width: 25%;">Nama Santri</th>
                <th style="width: 10%;">NIS</th>
                <th style="width: 15%;">Kelas</th>
                <th style="width: 10%;">Tipe</th>
                <th style="width: 15%;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($certificates as $index => $cert)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="font-size: 8px;"><strong>{{ $cert->certificate_number ?? '-' }}</strong></td>
                    <td>{{ $cert->user?->name ?? $cert->santri?->user?->name ?? '-' }}</td>
                    <td>{{ $cert->santri?->nis ?? '-' }}</td>
                    <td>{{ $cert->santri?->classes->first()?->name ?? '-' }}</td>
                    <td>
                        @if ($cert->type === 'khatam')
                            <span class="badge badge-khatam">Khatam</span>
                        @elseif ($cert->type === 'santri_juz')
                            <span class="badge badge-juz">Juz {{ $cert->juz_completed ?? '-' }}</span>
                        @else
                            <span class="badge badge-juz">{{ ucfirst($cert->type) }}</span>
                        @endif
                    </td>
                    <td>{{ $cert->issued_at?->format('d M Y') ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div>{{ auth()->user()->name }} | {{ $pesantren->name }}</div>
        <div>{{ $generated_at->format('d F Y, H:i') }}</div>
    </div>
</body>

</html>
