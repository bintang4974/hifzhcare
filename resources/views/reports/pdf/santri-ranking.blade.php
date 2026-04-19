<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Ranking Santri</title>
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
            border-bottom: 3px solid #F59E0B;
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

        .podium {
            display: table;
            width: 100%;
            margin: 20px 0;
        }

        .podium-row {
            display: table-row;
        }

        .podium-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 15px;
        }

        .medal {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .rank-card {
            background: white;
            border: 2px solid;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .rank-1 {
            border-color: #F59E0B;
            background: #FFFBEB;
        }

        .rank-2 {
            border-color: #94A3B8;
            background: #F8FAFC;
        }

        .rank-3 {
            border-color: #CD7F32;
            background: #FFF7ED;
        }

        .rank-number {
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            line-height: 40px;
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
        }

        .rank-1 .rank-number {
            background: #F59E0B;
            color: white;
        }

        .rank-2 .rank-number {
            background: #94A3B8;
            color: white;
        }

        .rank-3 .rank-number {
            background: #CD7F32;
            color: white;
        }

        .santri-name {
            font-size: 14px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 5px;
        }

        .santri-meta {
            font-size: 8px;
            color: #6B7280;
            margin-bottom: 8px;
        }

        .stats-grid {
            display: table;
            width: 100%;
        }

        .stats-row {
            display: table-row;
        }

        .stat-cell {
            display: table-cell;
            text-align: center;
            padding: 5px;
        }

        .stat-value {
            font-size: 16px;
            font-weight: bold;
            color: #1F2937;
        }

        .stat-label {
            font-size: 7px;
            color: #6B7280;
        }

        .ranking-list {
            margin-top: 20px;
        }

        .ranking-item {
            display: table;
            width: 100%;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            padding: 10px;
            margin-bottom: 8px;
            background: #F9FAFB;
        }

        .ranking-item-row {
            display: table-row;
        }

        .ranking-rank {
            display: table-cell;
            width: 40px;
            text-align: center;
            vertical-align: middle;
            font-size: 16px;
            font-weight: bold;
            color: #6B7280;
        }

        .ranking-info {
            display: table-cell;
            vertical-align: middle;
            padding-left: 10px;
        }

        .ranking-stats {
            display: table-cell;
            width: 200px;
            text-align: right;
            vertical-align: middle;
        }

        .progress-bar {
            background: #E5E7EB;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-fill {
            background: linear-gradient(90deg, #10B981, #34D399);
            height: 100%;
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
        <div class="subtitle">LAPORAN RANKING & PRESTASI SANTRI</div>
    </div>

    <!-- Top 3 Podium -->
    @if (count($santris) >= 3)
        <h3 style="font-size: 14px; margin-bottom: 15px; color: #1F2937; text-align: center;">
            🏆 Top 3 Performers
        </h3>

        <table style="width: 100%; margin-bottom: 30px;">
            <tr>
                <!-- 2nd Place (Silver) -->
                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 10px;">
                    <div class="rank-card rank-2">
                        <div class="medal">🥈</div>
                        <div class="rank-number">#2</div>
                        <div class="santri-name">{{ $santris[1]->user->name }}</div>
                        <div class="santri-meta">{{ $santris[1]->nis }} | {{ $santris[1]->classes->first()?->name ?? '-' }}
                        </div>
                        <div style="font-size: 12px; margin-top: 8px;">
                            <div style="color: #10B981; font-weight: bold; margin-bottom: 3px;">
                                {{ number_format($santris[1]->progress_percentage, 1) }}%</div>
                            <div style="color: #6B7280; font-size: 8px; margin-bottom: 3px;">Progress</div>
                            <div style="color: #3B82F6; font-weight: bold;">
                                {{ $santris[1]->verified_count }}
                            </div>
                            <div style="color: #6B7280; font-size: 8px;">Hafalan</div>
                        </div>
                    </div>
                </td>

                <!-- 1st Place (Gold) -->
                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 10px;">
                    <div class="rank-card rank-1">
                        <div class="medal">🥇</div>
                        <div class="rank-number">#1</div>
                        <div class="santri-name">{{ $santris[0]->user->name }}</div>
                        <div class="santri-meta">{{ $santris[0]->nis }} | {{ $santris[0]->classes->first()?->name ?? '-' }}
                        </div>
                        <div style="font-size: 12px; margin-top: 8px;">
                            <div style="color: #10B981; font-weight: bold; margin-bottom: 3px;">
                                {{ number_format($santris[0]->progress_percentage, 1) }}%</div>
                            <div style="color: #6B7280; font-size: 8px; margin-bottom: 3px;">Progress</div>
                            <div style="color: #3B82F6; font-weight: bold;">
                                {{ $santris[0]->verified_count }}
                            </div>
                            <div style="color: #6B7280; font-size: 8px;">Hafalan</div>
                        </div>
                    </div>
                </td>

                <!-- 3rd Place (Bronze) -->
                <td style="width: 33.33%; text-align: center; vertical-align: top; padding: 10px;">
                    <div class="rank-card rank-3">
                        <div class="medal">🥉</div>
                        <div class="rank-number">#3</div>
                        <div class="santri-name">{{ $santris[2]->user->name }}</div>
                        <div class="santri-meta">{{ $santris[2]->nis }} | {{ $santris[2]->classes->first()?->name ?? '-' }}
                        </div>
                        <div style="font-size: 12px; margin-top: 8px;">
                            <div style="color: #10B981; font-weight: bold; margin-bottom: 3px;">
                                {{ number_format($santris[2]->progress_percentage, 1) }}%</div>
                            <div style="color: #6B7280; font-size: 8px; margin-bottom: 3px;">Progress</div>
                            <div style="color: #3B82F6; font-weight: bold;">
                                {{ $santris[2]->verified_count }}
                            </div>
                            <div style="color: #6B7280; font-size: 8px;">Hafalan</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    @endif

    <!-- Full Ranking List -->
    <h3 style="font-size: 12px; margin: 20px 0 10px; color: #1F2937;">
        📊 Ranking Lengkap (Top {{ min(count($santris), 20) }})
    </h3>

    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #F3F4F6; border-bottom: 2px solid #D1D5DB;">
                <th style="width: 40px; text-align: center; padding: 8px; font-size: 10px; font-weight: bold;">Rank</th>
                <th style="text-align: left; padding: 8px; font-size: 10px; font-weight: bold;">Nama Santri</th>
                <th style="width: 100px; text-align: center; padding: 8px; font-size: 10px; font-weight: bold;">Progress</th>
                <th style="width: 80px; text-align: center; padding: 8px; font-size: 10px; font-weight: bold;">Hafalan</th>
                <th style="width: 80px; text-align: center; padding: 8px; font-size: 10px; font-weight: bold;">Sertifikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($santris as $index => $santri)
                <tr style="border-bottom: 1px solid #E5E7EB; background: {{ $index % 2 == 0 ? '#FFFFFF' : '#F9FAFB' }};">
                    <td style="text-align: center; padding: 8px; font-weight: bold; font-size: 11px;">
                        @if ($index == 0)
                            🥇
                        @elseif($index == 1)
                            🥈
                        @elseif($index == 2)
                            🥉
                        @else
                            #{{ $index + 1 }}
                        @endif
                    </td>
                    <td style="text-align: left; padding: 8px;">
                        <div style="font-size: 10px; font-weight: bold; color: #1F2937;">
                            {{ $santri->user->name }}
                        </div>
                        <div style="font-size: 7px; color: #6B7280;">
                            {{ $santri->nis }} | {{ $santri->classes->first()?->name ?? '-' }}
                        </div>
                    </td>
                    <td style="text-align: center; padding: 8px;">
                        <div style="font-size: 10px; font-weight: bold; color: #10B981;">
                            {{ number_format($santri->progress_percentage, 1) }}%
                        </div>
                    </td>
                    <td style="text-align: center; padding: 8px;">
                        <div style="font-size: 10px; font-weight: bold; color: #3B82F6;">
                            {{ $santri->verified_count }}
                        </div>
                    </td>
                    <td style="text-align: center; padding: 8px;">
                        <div style="font-size: 10px; font-weight: bold; color: #F59E0B;">
                            {{ $santri->certificates_count }}
                        </div>
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
