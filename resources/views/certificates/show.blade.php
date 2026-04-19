<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat {{ $certificate->certificate_number }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .certificate-container {
            width: 297mm;
            height: 210mm;
            background: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        /* Border Design */
        .border-design {
            position: absolute;
            inset: 15mm;
            border: 3px solid #d4af37;
            box-shadow: inset 0 0 0 2px #d4af37;
        }

        .border-design::before {
            content: '';
            position: absolute;
            inset: -10px;
            border: 1px solid #d4af37;
        }

        /* Corner Decorations */
        .corner-decoration {
            position: absolute;
            width: 60px;
            height: 60px;
            border: 3px solid #d4af37;
        }

        .corner-decoration.top-left {
            top: 10mm;
            left: 10mm;
            border-right: none;
            border-bottom: none;
        }

        .corner-decoration.top-right {
            top: 10mm;
            right: 10mm;
            border-left: none;
            border-bottom: none;
        }

        .corner-decoration.bottom-left {
            bottom: 10mm;
            left: 10mm;
            border-right: none;
            border-top: none;
        }

        .corner-decoration.bottom-right {
            bottom: 10mm;
            right: 10mm;
            border-left: none;
            border-top: none;
        }

        /* Content */
        .certificate-content {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 25mm 30mm;
        }

        .certificate-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
        }

        .certificate-title {
            font-size: 48px;
            color: #d4af37;
            font-weight: bold;
            letter-spacing: 3px;
            margin: 15px 0;
            text-transform: uppercase;
        }

        .certificate-subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 20px;
            font-style: italic;
        }

        .certificate-presented {
            font-size: 16px;
            color: #333;
            margin: 20px 0 10px;
        }

        .certificate-name {
            font-size: 42px;
            color: #333;
            font-weight: bold;
            margin: 15px 0;
            border-bottom: 3px solid #d4af37;
            display: inline-block;
            padding: 5px 40px;
        }

        .certificate-achievement {
            font-size: 18px;
            color: #555;
            margin: 20px auto;
            max-width: 600px;
            line-height: 1.6;
        }

        .certificate-achievement strong {
            color: #d4af37;
            font-size: 22px;
        }

        .certificate-details {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin: 25px 0;
            font-size: 14px;
            color: #666;
        }

        .certificate-details div {
            text-align: center;
        }

        .certificate-details strong {
            display: block;
            color: #333;
            font-size: 16px;
            margin-top: 5px;
        }

        .certificate-signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            padding: 0 50px;
        }

        .signature-block {
            text-align: center;
        }

        .signature-line {
            width: 200px;
            height: 80px;
            border-bottom: 2px solid #333;
            margin: 0 auto 10px;
            position: relative;
        }

        .signature-name {
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }

        .signature-title {
            font-size: 13px;
            color: #666;
            font-style: italic;
        }

        .certificate-number {
            position: absolute;
            bottom: 12mm;
            left: 20mm;
            font-size: 12px;
            color: #999;
        }

        .certificate-date {
            position: absolute;
            bottom: 12mm;
            right: 20mm;
            font-size: 12px;
            color: #999;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(212, 175, 55, 0.05);
            font-weight: bold;
            z-index: 0;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .certificate-container {
                box-shadow: none;
                page-break-after: always;
            }

            @page {
                margin: 0;
            }
        }
    </style>
</head>

<body>
    <div class="certificate-container">
        <!-- Watermark -->
        <div class="watermark">{{ $pesantren->code }}</div>

        <!-- Border Design -->
        <div class="border-design"></div>

        <!-- Corner Decorations -->
        <div class="corner-decoration top-left"></div>
        <div class="corner-decoration top-right"></div>
        <div class="corner-decoration bottom-left"></div>
        <div class="corner-decoration bottom-right"></div>

        <!-- Content -->
        <div class="certificate-content">
            <!-- Logo -->
            <div class="certificate-logo">
                <i class="fas fa-mosque"></i>
            </div>

            <!-- Pesantren Name -->
            <div style="font-size: 20px; font-weight: bold; color: #333; margin-bottom: 5px;">
                {{ $pesantren->name }}
            </div>
            <div style="font-size: 12px; color: #666; margin-bottom: 20px;">
                {{ $pesantren->address }}
            </div>

            <!-- Title -->
            <h1 class="certificate-title">Sertifikat</h1>
            <p class="certificate-subtitle">Hafalan Al-Quran</p>

            <!-- Presented To -->
            <p class="certificate-presented">Diberikan kepada:</p>

            <!-- Santri Name -->
            <h2 class="certificate-name">{{ $certificate->santri->user->name }}</h2>

            <!-- Achievement -->
            <p class="certificate-achievement">
                Telah berhasil menyelesaikan dan menghafal
                @if ($certificate->type === 'santri_juz' && ($certificate->juz_completed ?? 0) >= 30)
                    <strong>30 JUZ AL-QURAN (KHATAM)</strong><br>
                    dengan baik dan benar
                @elseif ($certificate->type === 'santri_juz' && $certificate->juz_completed)
                    <strong>JUZ {{ $certificate->juz_completed }} AL-QURAN</strong><br>
                    dengan lancar dan baik
                @else
                    -
                @endif
            </p>

            <!-- Details -->
            <div class="certificate-details">
                <div>
                    <span>NIS</span>
                    <strong>{{ $certificate->santri->nis }}</strong>
                </div>
                <div>
                    <span>Kelas</span>
                    <strong>{{ $certificate->santri->firstActiveClass()?->name ?? '-' }}</strong>
                </div>
                <div>
                    <span>Tanggal</span>
                    <strong>{{ $certificate->issued_at?->format('d F Y') ?? '-' }}</strong>
                </div>
            </div>

            <!-- Signatures -->
            <div class="certificate-signatures">
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $pesantren->name }}</div>
                    <div class="signature-title">Pimpinan Pesantren</div>
                </div>

                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ $certificate->santri->firstActiveClass()?->ustadz->user->name ?? 'Ustadz' }}
                    </div>
                    <div class="signature-title">Ustadz Pembimbing</div>
                </div>
            </div>
        </div>

        <!-- Certificate Number -->
        <div class="certificate-number">
            No: {{ $certificate->certificate_number }}
        </div>

        <!-- Date -->
        <div class="certificate-date">
            {{ $certificate->issued_at?->format('d F Y') ?? '-' }}
        </div>
    </div>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        // Auto print when opened
        window.onload = function() {
            if (window.location.search.includes('auto_print=1')) {
                setTimeout(() => window.print(), 500);
            }
        }
    </script>
</body>

</html>
