<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Performance Kelas</title>
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
            border-bottom: 3px solid #3B82F6;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            color: #1F2937;
            margin-bottom: 5px;
        }

        .class-section {
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .metrics-grid {
            display: table;
            width: 100%;
            margin: 10px 0;
        }

        .metric-cell {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
            background: #F9FAFB;
            border-radius: 6px;
        }

        .metric-value {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .metric-label {
            font-size: 8px;
            color: #6B7280;
        }

        .trend-chart {
            background: #F3F4F6;
            height: 80px;
            border-radius: 6px;
            margin: 10px 0;
            padding: 10px;
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
        <div style="font-size: 14px; color: #6B7280;">LAPORAN PERFORMANCE KELAS</div>
    </div>

    @foreach ($classes as $class)
        <div class="class-section">
            <h3 style="font-size: 14px; color: #1F2937; margin-bottom: 10px;">{{ $class->name }}</h3>
            <p style="font-size: 9px; color: #6B7280; margin-bottom: 15px;">Ustadz:
                {{ $class->ustadz->user->name ?? '-' }}</p>

            <table style="width: 100%;">
                <tr>
                    <td class="metric-cell" style="background: #EFF6FF; margin: 2px;">
                        <div class="metric-value" style="color: #3B82F6;">{{ $class->metrics['total_santri'] }}</div>
                        <div class="metric-label">Total Santri</div>
                    </td>
                    <td class="metric-cell" style="background: #ECFDF5; margin: 2px;">
                        <div class="metric-value" style="color: #10B981;">
                            {{ number_format($class->metrics['avg_progress'], 1) }}%</div>
                        <div class="metric-label">Avg Progress</div>
                    </td>
                    <td class="metric-cell" style="background: #FEF3C7; margin: 2px;">
                        <div class="metric-value" style="color: #F59E0B;">{{ $class->metrics['total_verified'] }}</div>
                        <div class="metric-label">Hafalan Verified</div>
                    </td>
                    <td class="metric-cell" style="background: #FEE2E2; margin: 2px;">
                        <div class="metric-value" style="color: #EF4444;">{{ $class->metrics['total_certificates'] }}
                        </div>
                        <div class="metric-label">Sertifikat</div>
                    </td>
                </tr>
            </table>

            <div style="margin-top: 15px; padding: 10px; background: #F9FAFB; border-radius: 6px;">
                <h4 style="font-size: 10px; color: #1F2937; margin-bottom: 8px;">Trend Bulanan</h4>
                @if ($class->monthly_trend && count($class->monthly_trend) > 0)
                    <div style="font-size: 8px; color: #374151;">
                        @foreach ($class->monthly_trend as $month => $count)
                            Bulan {{ $month }}: {{ $count }} hafalan{{ !$loop->last ? ' | ' : '' }}
                        @endforeach
                    </div>
                @else
                    <p style="font-size: 8px; color: #9CA3AF;">Belum ada data trend</p>
                @endif
            </div>
        </div>
    @endforeach

    <div class="footer">
        <div>{{ auth()->user()->name }} | {{ $pesantren->name }}</div>
        <div>{{ $generated_at->format('d F Y, H:i') }}</div>
    </div>
</body>

</html>
