<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Hafalan Per Juz</title>
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

        table.juz-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.juz-table thead {
            background: #F59E0B;
            color: white;
        }

        table.juz-table th {
            padding: 8px;
            text-align: left;
            font-size: 9px;
            border: 1px solid #F59E0B;
        }

        table.juz-table td {
            padding: 6px 8px;
            border: 1px solid #E5E7EB;
            font-size: 9px;
        }

        table.juz-table tbody tr:nth-child(even) {
            background: #FFFBEB;
        }

        .progress-container {
            width: 100%;
            background: #E5E7EB;
            border: 1px solid #D1D5DB;
            padding: 0;
            margin: 0;
        }

        .progress-fill {
            background: #10B981;
            height: 12px;
            padding: 0;
            margin: 0;
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
        <div style="font-size: 14px; color: #6B7280;">LAPORAN HAFALAN PER JUZ</div>
    </div>

    <table class="juz-table">
        <thead>
            <tr>
                <th style="width: 8%;">Juz</th>
                <th style="width: 12%;">Total Santri</th>
                <th style="width: 15%;">Completion Rate</th>
                <th style="width: 20%;">Progress</th>
                <th style="width: 45%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($juzStats as $juz => $stats)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $juz }}</td>
                    <td style="text-align: center;">{{ $stats['total_santri'] }}</td>
                    <td style="text-align: center;">
                        <strong>{{ number_format($stats['completion_rate'], 1) }}%</strong>
                    </td>
                    <td style="padding: 0; vertical-align: middle;">
                        <table style="width: 100%; border-collapse: collapse; margin: 0; padding: 0;">
                            <tr style="height: 14px;">
                                <td style="width: {{ $stats['completion_rate'] }}%; background: #10B981; border: 1px solid #059669; padding: 0; margin: 0;"></td>
                                <td style="width: {{ 100 - $stats['completion_rate'] }}%; background: #E5E7EB; border: 1px solid #D1D5DB; padding: 0; margin: 0;"></td>
                            </tr>
                        </table>
                    </td>
                    <td style="font-size: 8px; color: #6B7280;">
                        {{ $stats['santri_completed'] ?? 0 }} dari {{ $stats['total_santri'] }} santri telah menyelesaikan Juz {{ $juz }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div
        style="margin-top: 20px; background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 12px; border-radius: 6px;">
        <h4 style="font-size: 10px; color: #1F2937; margin-bottom: 5px;">Analisis:</h4>
        <ul style="font-size: 9px; color: #374151; margin-left: 15px;">
            <li>Juz paling banyak diselesaikan: Juz
                {{ array_search(max(array_column($juzStats, 'total_santri')), array_column($juzStats, 'total_santri')) + 1 }}
            </li>
            <li>Total sertifikat yang diterbitkan: {{ array_sum(array_column($juzStats, 'total_santri')) }}</li>
        </ul>
    </div>

    <div class="footer">
        <div>{{ auth()->user()->name }} | {{ $pesantren->name }}</div>
        <div>{{ $generated_at->format('d F Y, H:i') }}</div>
    </div>
</body>

</html>
