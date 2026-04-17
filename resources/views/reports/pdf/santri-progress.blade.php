<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Progress Hafalan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 3px solid #10B981;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            color: #1F2937;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 13px;
            color: #6B7280;
            margin-bottom: 3px;
            font-weight: bold;
        }

        .header .address {
            font-size: 10px;
            color: #9CA3AF;
        }

        .report-info {
            background: #F3F4F6;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .report-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-info td {
            padding: 5px 0;
            font-size: 10px;
        }

        .report-info td:first-child {
            width: 150px;
            font-weight: bold;
            color: #4B5563;
        }

        .stats-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .stats-table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #E5E7EB;
            font-weight: bold;
        }

        .stat-value {
            font-size: 20px;
            margin-bottom: 5px;
            display: block;
        }

        .stat-label {
            font-size: 9px;
            color: #6B7280;
            font-weight: normal;
            display: block;
        }

        .santri-card {
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 15px;
            background: white;
            page-break-inside: avoid;
        }

        .santri-header-row {
            display: block;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #F3F4F6;
        }

        .santri-name {
            font-size: 13px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 3px;
        }

        .ranking-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 600;
            margin-left: 5px;
        }

        .ranking-gold {
            background: #FEF3C7;
            color: #92400E;
        }

        .ranking-silver {
            background: #F3F4F6;
            color: #374151;
        }

        .ranking-bronze {
            background: #FED7AA;
            color: #9A3412;
        }

        .santri-meta {
            font-size: 9px;
            color: #6B7280;
        }

        .progress-details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .progress-details-table td {
            padding: 8px;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            text-align: center;
            font-size: 10px;
        }

        .progress-details-table .value {
            font-size: 14px;
            font-weight: bold;
            color: #1F2937;
            display: block;
            margin-bottom: 3px;
        }

        .progress-details-table .label {
            font-size: 8px;
            color: #6B7280;
            display: block;
        }

        .progress-percentage {
            font-size: 16px;
            font-weight: bold;
            color: #10B981;
            text-align: center;
            margin: 10px 0;
        }

        .progress-bar-container {
            background: #E5E7EB;
            height: 20px;
            border-radius: 10px;
            overflow: hidden;
            margin: 8px 0;
            border: 1px solid #D1D5DB;
        }

        .progress-bar {
            background: linear-gradient(90deg, #10B981, #34D399);
            height: 100%;
            border-radius: 10px;
            text-align: center;
            line-height: 20px;
            color: white;
            font-size: 9px;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #9CA3AF;
            padding-top: 10px;
            border-top: 1px solid #E5E7EB;
        }

        .monthly-data {
            margin-top: 10px;
            padding: 8px;
            background: #F9FAFB;
            border-radius: 6px;
            font-size: 9px;
            color: #374151;
            border: 1px solid #E5E7EB;
        }

        .monthly-data strong {
            display: block;
            margin-bottom: 5px;
            color: #6B7280;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $pesantren->name ?? 'Pesantren' }}</h1>
        <div class="subtitle">LAPORAN PROGRESS HAFALAN SANTRI</div>
        <div class="address">{{ $pesantren->address ?? '' }}</div>
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
            <tr>
                <td>Rata-rata Progress</td>
                <td>: {{ number_format($santris->avg('progress_percentage'), 1) }}%</td>
            </tr>
        </table>
    </div>

    <!-- Overall Statistics -->
    <table class="stats-table">
        <tr>
            <td width="25%">
                <span class="stat-value" style="color: #3B82F6;">{{ number_format($santris->avg('progress_percentage'), 1) }}%</span>
                <span class="stat-label">Rata-rata Progress</span>
            </td>
            <td width="25%">
                <span class="stat-value" style="color: #10B981;">{{ $santris->sum('verified_count') }}</span>
                <span class="stat-label">Total Hafalan Verified</span>
            </td>
            <td width="25%">
                <span class="stat-value" style="color: #F59E0B;">{{ $santris->sum('certificates_count') }}</span>
                <span class="stat-label">Total Sertifikat</span>
            </td>
            <td width="25%">
                <span class="stat-value" style="color: #8B5CF6;">{{ $santris->where('progress_percentage', '>=', 80)->count() }}</span>
                <span class="stat-label">Progress ≥ 80%</span>
            </td>
        </tr>
    </table>

    <!-- Santri Progress List -->
    @foreach ($santris as $index => $santri)
        <div class="santri-card">
            <!-- Header -->
            <div class="santri-header-row">
                <div class="santri-name">
                    {{ $santri->user->name ?? 'Unknown' }}
                    @if ($index < 3)
                        @if ($index == 0)
                            <span class="ranking-badge ranking-gold">🥇 #1</span>
                        @elseif($index == 1)
                            <span class="ranking-badge ranking-silver">🥈 #2</span>
                        @else
                            <span class="ranking-badge ranking-bronze">🥉 #3</span>
                        @endif
                    @endif
                </div>
                <div class="santri-meta">
                    NIS: {{ $santri->nis ?? '-' }} | Kelas: {{ $santri->class_name ?? '-' }} | Ranking: #{{ $index + 1 }} dari {{ count($santris) }}
                </div>
            </div>

            <!-- Progress Stats Table -->
            <table class="progress-details-table">
                <tr>
                    <td width="25%">
                        <span class="value" style="color: #10B981;">{{ $santri->verified_count ?? 0 }}</span>
                        <span class="label">Hafalan Verified</span>
                    </td>
                    <td width="25%">
                        <span class="value" style="color: #3B82F6;">{{ $santri->certificates_count ?? 0 }}</span>
                        <span class="label">Sertifikat</span>
                    </td>
                    <td width="25%">
                        <span class="value" style="color: #F59E0B;">{{ $santri->monthly_progress ? array_sum($santri->monthly_progress) : 0 }}</span>
                        <span class="label">Hafalan Bulan Ini</span>
                    </td>
                    <td width="25%">
                        <span class="value" style="color: #8B5CF6;">{{ floor(($santri->progress_percentage ?? 0) / 3.33) }}</span>
                        <span class="label">Juz Selesai (est.)</span>
                    </td>
                </tr>
            </table>

            <!-- Progress Percentage -->
            <div class="progress-percentage">
                {{ number_format($santri->progress_percentage ?? 0, 1) }}% Complete
            </div>

            <!-- Progress Bar -->
            <div class="progress-bar-container">
                <div class="progress-bar" style="width: {{ min(($santri->progress_percentage ?? 0), 100) }}%">
                </div>
            </div>

            <!-- Monthly Trend -->
            @if ($santri->monthly_progress && count($santri->monthly_progress) > 0)
                <div class="monthly-data">
                    <strong>Trend Bulanan (Hafalan per Bulan):</strong>
                    @foreach ($santri->monthly_progress as $month => $count)
                        Bulan {{ $month }}: {{ $count }} hafalan{{ !$loop->last ? ' | ' : '' }}
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach

    <!-- Footer -->
    <div class="footer">
        <div>Dicetak oleh: {{ auth()->user()->name }} | {{ $pesantren->name ?? 'Pesantren' }}</div>
        <div>Generated at {{ $generated_at->format('d F Y H:i:s') }}</div>
    </div>
</body>

</html>
