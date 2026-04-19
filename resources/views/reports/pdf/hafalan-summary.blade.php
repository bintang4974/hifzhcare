<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Summary Hafalan</title>
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
            border-bottom: 3px solid #10B981;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            color: #1F2937;
        }

        .stats-grid {
            width: 100%;
            margin: 20px 0;
        }

        .stat-card {
            width: 25%;
            padding: 15px;
            text-align: center;
            display: inline-block;
            box-sizing: border-box;
        }

        .stat-card.blue {
            background: #EFF6FF;
            border: 1px solid #DBEAFE;
            border-radius: 8px;
        }

        .stat-card.green {
            background: #ECFDF5;
            border: 1px solid #D1FAE5;
            border-radius: 8px;
        }

        .stat-card.yellow {
            background: #FFFBEB;
            border: 1px solid #FEF3C7;
            border-radius: 8px;
        }

        .stat-card.red {
            background: #FEE2E2;
            border: 1px solid #FECACA;
            border-radius: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 9px;
            color: #6B7280;
        }

        .chart-section {
            background: #F9FAFB;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
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
        <div style="font-size: 14px; color: #6B7280;">LAPORAN SUMMARY HAFALAN</div>
    </div>

    <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
        <tr>
            <td style="width: 25%; padding: 5px; text-align: center; vertical-align: middle;">
                <div style="background: #EFF6FF; border: 1px solid #DBEAFE; border-radius: 8px; padding: 15px;">
                    <div style="font-size: 32px; font-weight: bold; color: #3B82F6; margin-bottom: 5px;">{{ $stats['total_submitted'] }}</div>
                    <div style="font-size: 9px; color: #6B7280;">Total Submitted</div>
                </div>
            </td>
            <td style="width: 25%; padding: 5px; text-align: center; vertical-align: middle;">
                <div style="background: #ECFDF5; border: 1px solid #D1FAE5; border-radius: 8px; padding: 15px;">
                    <div style="font-size: 32px; font-weight: bold; color: #10B981; margin-bottom: 5px;">{{ $stats['total_verified'] }}</div>
                    <div style="font-size: 9px; color: #6B7280;">Total Verified</div>
                </div>
            </td>
            <td style="width: 25%; padding: 5px; text-align: center; vertical-align: middle;">
                <div style="background: #FFFBEB; border: 1px solid #FEF3C7; border-radius: 8px; padding: 15px;">
                    <div style="font-size: 32px; font-weight: bold; color: #F59E0B; margin-bottom: 5px;">{{ $stats['total_pending'] }}</div>
                    <div style="font-size: 9px; color: #6B7280;">Total Pending</div>
                </div>
            </td>
            <td style="width: 25%; padding: 5px; text-align: center; vertical-align: middle;">
                <div style="background: #FEE2E2; border: 1px solid #FECACA; border-radius: 8px; padding: 15px;">
                    <div style="font-size: 32px; font-weight: bold; color: #EF4444; margin-bottom: 5px;">{{ $stats['total_rejected'] }}</div>
                    <div style="font-size: 9px; color: #6B7280;">Total Rejected</div>
                </div>
            </td>
        </tr>
    </table>

    <div
        style="background: #F5F3FF; border-left: 4px solid #8B5CF6; padding: 12px; margin: 20px 0; border-radius: 6px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; padding: 10px;">
                    <div style="font-size: 9px; color: #6B7280;">Verification Rate</div>
                    <div style="font-size: 24px; font-weight: bold; color: #8B5CF6;">
                        {{ number_format($stats['verification_rate'], 1) }}%
                    </div>
                </td>
                <td style="width: 50%; padding: 10px;">
                    <div style="font-size: 9px; color: #6B7280;">Avg Verification Time</div>
                    <div style="font-size: 24px; font-weight: bold; color: #8B5CF6;">
                        {{ number_format($stats['avg_verification_time'], 1) }} jam
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="chart-section">
        <h3 style="font-size: 12px; color: #1F2937; margin-bottom: 10px;">Trend Bulanan ({{ date('Y') }})</h3>
        @if ($monthlyTrend && count($monthlyTrend) > 0)
            <table style="width: 100%; font-size: 9px;">
                <tr>
                    @foreach ($monthlyTrend as $data)
                        <td style="text-align: center; padding: 5px;">
                            <div style="background: #10B981; color: white; padding: 5px; border-radius: 4px;">
                                <strong>{{ $data->count }}</strong>
                            </div>
                            <div style="margin-top: 3px; color: #6B7280;">Bln {{ $data->month }}</div>
                        </td>
                    @endforeach
                </tr>
            </table>
        @else
            <p style="font-size: 9px; color: #9CA3AF; text-align: center;">Belum ada data trend</p>
        @endif
    </div>

    <div class="footer">
        <div>{{ auth()->user()->name }} | {{ $pesantren->name }}</div>
        <div>{{ $generated_at->format('d F Y, H:i') }}</div>
    </div>
</body>

</html>
