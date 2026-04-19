<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Overview Kelas</title>
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
            border-bottom: 3px solid #8B5CF6;
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
        }

        .class-card {
            border: 2px solid #E5E7EB;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            background: white;
            page-break-inside: avoid;
        }

        .class-header {
            border-bottom: 2px solid #F3F4F6;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .class-name {
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 5px;
        }

        .class-teacher {
            font-size: 9px;
            color: #6B7280;
        }

        .stats-grid {
            width: 100%;
            margin-bottom: 15px;
        }

        .stats-row {
            display: block;
        }

        .stat-box {
            background: #F9FAFB;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            margin: 3px;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .stat-label {
            font-size: 7px;
            color: #6B7280;
        }

        .capacity-bar {
            background: #E5E7EB;
            height: 15px;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 10px;
        }

        .capacity-fill {
            height: 100%;
            background: #8B5CF6;
            text-align: center;
            line-height: 15px;
            color: white;
            font-size: 8px;
            font-weight: bold;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .summary-table thead {
            background: #8B5CF6;
            color: white;
        }

        .summary-table th {
            padding: 8px;
            text-align: left;
            font-size: 9px;
        }

        .summary-table td {
            padding: 6px 8px;
            border: 1px solid #E5E7EB;
            font-size: 9px;
        }

        .summary-table tbody tr:nth-child(even) {
            background: #F9FAFB;
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
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ $pesantren->name }}</h1>
        <div class="subtitle">LAPORAN OVERVIEW KELAS</div>
    </div>

    <!-- Overall Summary -->
    <div
        style="background: #F5F3FF; border-left: 4px solid #8B5CF6; padding: 12px; margin-bottom: 20px; border-radius: 6px;">
        <h3 style="font-size: 11px; color: #1F2937; margin-bottom: 10px;">Ringkasan Keseluruhan</h3>
        <table style="width: 100%;">
            <tr>
                <td style="width: 25%; text-align: center;">
                    <div style="font-size: 20px; font-weight: bold; color: #8B5CF6;">{{ count($classes) }}</div>
                    <div style="font-size: 8px; color: #6B7280;">Total Kelas</div>
                </td>
                <td style="width: 25%; text-align: center;">
                    <div style="font-size: 20px; font-weight: bold; color: #10B981;">{{ $classes->sum('total_santri') }}
                    </div>
                    <div style="font-size: 8px; color: #6B7280;">Total Santri</div>
                </td>
                <td style="width: 25%; text-align: center;">
                    <div style="font-size: 20px; font-weight: bold; color: #3B82F6;">
                        {{ number_format($classes->avg('avg_progress'), 1) }}%</div>
                    <div style="font-size: 8px; color: #6B7280;">Rata-rata Progress</div>
                </td>
                <td style="width: 25%; text-align: center;">
                    <div style="font-size: 20px; font-weight: bold; color: #F59E0B;">
                        {{ $classes->sum('total_certificates') }}</div>
                    <div style="font-size: 8px; color: #6B7280;">Total Sertifikat</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Class Cards -->
    @foreach ($classes as $class)
        <div class="class-card">
            <div class="class-header">
                <div class="class-name">{{ $class->name }}</div>
                <div class="class-teacher">
                    <i class="fas fa-user"></i> Ustadz: {{ $class->ustadzProfiles->first()?->user->name ?? '-' }}
                </div>
            </div>

            <!-- Statistics Grid -->
            <table style="width: 100%; margin-bottom: 10px; border-collapse: collapse;">
                <tr>
                    <td style="width: 20%; padding: 5px; text-align: center; vertical-align: middle;">
                        <div style="background: #EFF6FF; padding: 10px; border-radius: 6px;">
                            <div style="font-size: 18px; font-weight: bold; color: #3B82F6; margin-bottom: 3px;">{{ $class->total_santri }}</div>
                            <div style="font-size: 7px; color: #6B7280;">Santri</div>
                        </div>
                    </td>
                    <td style="width: 20%; padding: 5px; text-align: center; vertical-align: middle;">
                        <div style="background: #ECFDF5; padding: 10px; border-radius: 6px;">
                            <div style="font-size: 18px; font-weight: bold; color: #10B981; margin-bottom: 3px;">
                                {{ number_format($class->avg_progress, 1) }}%</div>
                            <div style="font-size: 7px; color: #6B7280;">Avg Progress</div>
                        </div>
                    </td>
                    <td style="width: 20%; padding: 5px; text-align: center; vertical-align: middle;">
                        <div style="background: #FEF3C7; padding: 10px; border-radius: 6px;">
                            <div style="font-size: 18px; font-weight: bold; color: #F59E0B; margin-bottom: 3px;">{{ $class->total_verified }}</div>
                            <div style="font-size: 7px; color: #6B7280;">Hafalan Verified</div>
                        </div>
                    </td>
                    <td style="width: 20%; padding: 5px; text-align: center; vertical-align: middle;">
                        <div style="background: #FEE2E2; padding: 10px; border-radius: 6px;">
                            <div style="font-size: 18px; font-weight: bold; color: #EF4444; margin-bottom: 3px;">{{ $class->total_certificates }}</div>
                            <div style="font-size: 7px; color: #6B7280;">Sertifikat</div>
                        </div>
                    </td>
                    <td style="width: 20%; padding: 5px; text-align: center; vertical-align: middle;">
                        <div style="background: #F5F3FF; padding: 10px; border-radius: 6px;">
                            <div style="font-size: 18px; font-weight: bold; color: #8B5CF6; margin-bottom: 3px;">{{ $class->capacity ?? 30 }}</div>
                            <div style="font-size: 7px; color: #6B7280;">Kapasitas</div>
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Capacity Bar -->
            <div style="margin-top: 10px;">
                <div style="font-size: 8px; color: #6B7280; margin-bottom: 4px;">
                    Utilisasi Kapasitas: {{ $class->total_santri }}/{{ $class->capacity ?? 30 }} 
                    ({{ number_format(min(($class->total_santri / ($class->capacity ?? 30)) * 100, 100), 0) }}%)
                </div>
                <div style="background: #E5E7EB; height: 15px; border-radius: 8px; overflow: hidden;">
                    <div style="width: {{ min(($class->total_santri / ($class->capacity ?? 30)) * 100, 100) }}%; height: 100%; background: #8B5CF6;">
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Summary Table -->
    <h3 style="font-size: 12px; margin: 20px 0 10px; color: #1F2937;">Tabel Ringkasan</h3>

    <table class="summary-table">
        <thead>
            <tr>
                <th>Kelas</th>
                <th>Ustadz</th>
                <th style="text-align: center;">Santri</th>
                <th style="text-align: center;">Progress</th>
                <th style="text-align: center;">Hafalan</th>
                <th style="text-align: center;">Sertifikat</th>
                <th style="text-align: center;">Kapasitas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($classes as $class)
                <tr>
                    <td><strong>{{ $class->name }}</strong></td>
                    <td>{{ $class->ustadzProfiles->first()?->user->name ?? '-' }}</td>
                    <td style="text-align: center;">{{ $class->total_santri }}</td>
                    <td style="text-align: center;"><strong>{{ number_format($class->avg_progress, 1) }}%</strong></td>
                    <td style="text-align: center;">{{ $class->total_verified }}</td>
                    <td style="text-align: center;">{{ $class->total_certificates }}</td>
                    <td style="text-align: center;">
                        {{ number_format(($class->total_santri / ($class->capacity ?? 30)) * 100, 0) }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div>Dicetak oleh: {{ auth()->user()->name }} | {{ $pesantren->name }}</div>
        <div>Tanggal: {{ $generated_at->format('d F Y, H:i') }} WIB</div>
    </div>
</body>

</html>
