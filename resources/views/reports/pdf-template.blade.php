<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1f2937;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            color: #1f2937;
            font-size: 24px;
            font-weight: bold;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        
        .info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f3f4f6;
            border-left: 4px solid #3b82f6;
        }
        
        .info-item {
            display: inline-block;
            margin-right: 30px;
            font-size: 12px;
        }
        
        .info-label {
            font-weight: bold;
            color: #374151;
        }
        
        .info-value {
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11px;
        }
        
        table thead {
            background-color: #1f2937;
            color: white;
        }
        
        table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #e5e7eb;
        }
        
        table td {
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        table tbody tr:hover {
            background-color: #f3f4f6;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 10px;
            text-align: center;
            color: #999;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #f0f9ff !important;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $pesantren }}</h1>
        <h2 style="margin: 10px 0 0 0; font-size: 18px; color: #1f2937;">{{ $title }}</h2>
        <p>Laporan Otomatis</p>
    </div>

    <!-- Info -->
    <div class="info">
        <div class="info-item">
            <span class="info-label">Tanggal Cetak:</span>
            <span class="info-value">{{ $generated_at }}</span>
        </div>
        <div class="info-item">
            <span class="info-label">Total Baris:</span>
            <span class="info-value">{{ count($data) }}</span>
        </div>
    </div>

    <!-- Table -->
    @if(count($data) > 0)
        <table>
            <thead>
                <tr>
                    @foreach($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="info" style="border-left: 4px solid #ef4444; margin-top: 30px;">
            <p style="margin: 0; color: #ef4444;">Tidak ada data yang ditemukan untuk filter yang dipilih.</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini adalah dokumen resmi dari {{ $pesantren }}.</p>
        <p>Dicetak oleh Sistem Informasi HifzhCare pada {{ $generated_at }}</p>
    </div>
</body>
</html>
