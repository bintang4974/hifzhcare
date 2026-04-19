<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Classes;
use App\Models\HafalanAudio;
use App\Models\SantriProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display certificate list
     */
    public function index(Request $request)
    {
        $query = Certificate::with(['user', 'santri'])
            ->where('pesantren_id', auth()->user()->pesantren_id);

        // Filter by type (translate UI values to DB columns)
        if ($request->filled('type')) {
            if ($request->type === 'per_juz') {
                $query->where('type', 'santri_juz')
                    ->where(function ($q) {
                        $q->where('juz_completed', '<', 30)
                            ->orWhereNull('juz_completed');
                    });
            } elseif ($request->type === 'khatam') {
                $query->where('type', 'santri_juz')
                    ->where('juz_completed', '>=', 30);
            } else {
                $query->where('type', $request->type);
            }
        }

        // Filter by class
        if ($request->filled('class_id')) {
            $query->whereHas('santri.activeClasses', function ($q) use ($request) {
                $q->where('classes.id', $request->class_id);
            });
        }

        // Search by name or NIS
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('santri', function ($q) use ($request) {
                $q->where('nis', 'like', '%' . $request->search . '%');
            });
        }

        $certificates = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'total' => Certificate::where('pesantren_id', auth()->user()->pesantren_id)->count(),
            'this_month' => Certificate::where('pesantren_id', auth()->user()->pesantren_id)
                ->whereMonth('issued_at', now()->month)
                ->whereYear('issued_at', now()->year)
                ->count(),
            'khatam' => Certificate::where('pesantren_id', auth()->user()->pesantren_id)
                ->where('type', 'santri_juz')
                ->where('juz_completed', '>=', 30)
                ->count(),
            'per_juz' => Certificate::where('pesantren_id', auth()->user()->pesantren_id)
                ->where('type', 'santri_juz')
                ->where(function ($q) {
                    $q->where('juz_completed', '<', 30)
                        ->orWhereNull('juz_completed');
                })
                ->count(),
        ];

        // Get classes for filter
        $classes = Classes::where('pesantren_id', auth()->user()->pesantren_id)
            ->orderBy('name')
            ->get();

        return view('certificates.index', compact('certificates', 'stats', 'classes'));
    }

    /**
     * Show certificate
     */
    public function show($id)
    {
        $certificate = Certificate::with(['user', 'santri.activeClasses'])
            ->findOrFail($id);

        $pesantren = $certificate->pesantren;

        return view('certificates.show', compact('certificate', 'pesantren'));
    }

    /**
     * Print certificate
     */
    public function print($id)
    {
        return redirect()->route('certificates.show', $id) . '?auto_print=1';
    }

    /**
     * Download certificate as PDF
     */
    public function download($id)
    {
        $certificate = Certificate::with(['user', 'santri.activeClasses'])
            ->findOrFail($id);

        $pesantren = $certificate->pesantren;

        $pdf = Pdf::loadView('certificates.show', compact('certificate', 'pesantren'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("Sertifikat-{$certificate->certificate_number}.pdf");
    }

    /**
     * Send certificate to wali via email
     */
    public function send($id)
    {
        $certificate = Certificate::with(['santri.user', 'santri.wali.user'])
            ->findOrFail($id);

        if (!$certificate->santri->wali || !$certificate->santri->wali->user->email) {
            return response()->json([
                'success' => false,
                'message' => 'Wali santri tidak memiliki email yang terdaftar!'
            ], 400);
        }

        try {
            // Generate PDF
            $pesantren = $certificate->pesantren;
            $pdf = Pdf::loadView('certificates.show', compact('certificate', 'pesantren'))
                ->setPaper('a4', 'landscape');

            // Send email
            // Mail::to($certificate->santri->wali->user->email)
            //     ->send(new CertificateIssued($certificate, $pdf->output()));

            return response()->json([
                'success' => true,
                'message' => 'Sertifikat berhasil dikirim ke email wali!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete certificate
     */
    public function destroy($id)
    {
        try {
            $certificate = Certificate::findOrFail($id);

            // Only admin can delete
            if (auth()->user()->user_type !== 'admin' && auth()->user()->user_type !== 'super_admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya admin yang dapat menghapus sertifikat!'
                ], 403);
            }

            $certificate->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sertifikat berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate certificate manually
     */
    public function generateForm()
    {
        $santris = SantriProfile::where('pesantren_id', auth()->user()->pesantren_id)
            ->with('user')
            ->where('status', 'active')
            ->orderBy('nis')
            ->get();

        return view('certificates.generate', compact('santris'));
    }

    /**
     * Store manual certificate
     */
    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'santri_id' => 'required|exists:santri_profiles,id',
            'certificate_type' => 'required|in:per_juz,khatam',
            'juz_number' => 'required_if:certificate_type,per_juz|nullable|integer|min:1|max:30',
            'issue_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $santri = SantriProfile::findOrFail($validated['santri_id']);

        // Check if certificate already exists (map form values to DB columns)
        if ($validated['certificate_type'] === 'per_juz') {
            $exists = Certificate::where('santri_id', $santri->id)
                ->where('type', 'santri_juz')
                ->where('juz_completed', $validated['juz_number'])
                ->exists();

            if ($exists) {
                return back()->with('error', 'Sertifikat untuk Juz ini sudah ada!');
            }
        }

        // Generate certificate number
        $certificateNumber = $this->generateCertificateNumber(
            $validated['certificate_type'],
            $validated['juz_number'] ?? null
        );

        // Create certificate (store according to migration: 'type' and 'juz_completed')
        $data = [
            'santri_id' => $santri->id,
            'pesantren_id' => $santri->pesantren_id,
            'certificate_number' => $certificateNumber,
            'issued_at' => $validated['issue_date'],
            'notes' => $validated['notes'],
            'type' => $validated['certificate_type'] === 'per_juz' ? 'santri_juz' : 'santri_juz',
            'juz_completed' => $validated['certificate_type'] === 'per_juz' ? $validated['juz_number'] ?? null : ($validated['certificate_type'] === 'khatam' ? 30 : null),
        ];

        Certificate::create($data);

        return redirect()
            ->route('certificates.index')
            ->with('success', 'Sertifikat berhasil dibuat!');
    }

    /**
     * AUTO-GENERATE CERTIFICATE WHEN JUZ COMPLETED
     * Called from HafalanController when hafalan is verified
     */
    public static function checkAndGenerateCertificate($santriId, $juzNumber)
    {
        $santri = SantriProfile::findOrFail($santriId);

        // Check if all ayat in this juz are verified
        $juzCompleted = self::isJuzCompleted($santriId, $juzNumber);

        if ($juzCompleted) {
            // Check if certificate already exists
            $exists = Certificate::where('santri_id', $santriId)
                ->where('type', 'santri_juz')
                ->where('juz_completed', $juzNumber)
                ->exists();

            if (!$exists) {
                // Generate certificate number
                $pesantren = $santri->pesantren;
                $prefix = $pesantren->settings['certificate_prefix'] ?? $pesantren->code;
                $certificateNumber = $prefix . '/JUZ' . str_pad($juzNumber, 2, '0', STR_PAD_LEFT) . '/' . date('Y') . '/' . str_pad(Certificate::where('pesantren_id', $santri->pesantren_id)->count() + 1, 4, '0', STR_PAD_LEFT);

                // Create certificate (auto-generated per-juz)
                $certificate = Certificate::create([
                    'santri_id' => $santriId,
                    'pesantren_id' => $santri->pesantren_id,
                    'certificate_number' => $certificateNumber,
                    'type' => 'santri_juz',
                    'juz_completed' => $juzNumber,
                    'issued_at' => now(),
                    'notes' => 'Auto-generated upon Juz completion',
                ]);

                // Check if all 30 juz completed for khatam certificate
                self::checkAndGenerateKhatamCertificate($santriId);

                return $certificate;
            }
        }

        return null;
    }

    /**
     * Check if juz is completed (all ayat verified)
     */
    private static function isJuzCompleted($santriId, $juzNumber)
    {
        // Get juz boundaries
        $juzBoundaries = self::getJuzBoundaries();
        $juzInfo = $juzBoundaries[$juzNumber - 1];

        // Count verified ayat in this juz
        $verifiedCount = HafalanAudio::where('santri_id', $santriId)
            ->where('status', 'verified')
            ->where(function ($query) use ($juzInfo) {
                foreach ($juzInfo['surahs'] as $surah) {
                    $query->orWhere(function ($q) use ($surah) {
                        $q->where('surah_number', $surah['surah_number']);
                        if (isset($surah['from_ayat'])) {
                            $q->where('to_ayat', '>=', $surah['from_ayat']);
                        }
                        if (isset($surah['to_ayat'])) {
                            $q->where('from_ayat', '<=', $surah['to_ayat']);
                        }
                    });
                }
            })
            ->count();

        // Calculate total ayat in juz
        $totalAyat = $juzInfo['total_ayat'];

        return $verifiedCount >= $totalAyat;
    }

    /**
     * Check and generate khatam certificate if all 30 juz completed
     */
    private static function checkAndGenerateKhatamCertificate($santriId)
    {
        // Check if all 30 juz have certificates
        $completedJuz = Certificate::where('santri_id', $santriId)
            ->where('type', 'santri_juz')
            ->whereNotNull('juz_completed')
            ->count();

        if ($completedJuz >= 30) {
            // Check if khatam certificate already exists
            $khatamExists = Certificate::where('santri_id', $santriId)
                ->where('type', 'santri_juz')
                ->where('juz_completed', 30)
                ->exists();

            if (!$khatamExists) {
                $santri = SantriProfile::findOrFail($santriId);
                $pesantren = $santri->pesantren;
                $prefix = $pesantren->settings['certificate_prefix'] ?? $pesantren->code;

                $certificateNumber = $prefix . '/KHATAM/' . date('Y') . '/' . str_pad(Certificate::where('pesantren_id', $santri->pesantren_id)->count() + 1, 4, '0', STR_PAD_LEFT);

                Certificate::create([
                    'santri_id' => $santriId,
                    'pesantren_id' => $santri->pesantren_id,
                    'certificate_number' => $certificateNumber,
                    'type' => 'santri_juz',
                    'juz_completed' => 30,
                    'issued_at' => now(),
                    'notes' => 'Khatam 30 Juz Al-Quran - Auto-generated',
                ]);
            }
        }
    }

    /**
     * Generate certificate number
     */
    private function generateCertificateNumber($type, $juzNumber = null)
    {
        $pesantren = auth()->user()->pesantren;
        $prefix = $pesantren->settings['certificate_prefix'] ?? $pesantren->code;

        $year = date('Y');
        $count = Certificate::where('pesantren_id', $pesantren->id)->count() + 1;

        if ($type === 'khatam') {
            return "{$prefix}/KHATAM/{$year}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
        } else {
            $juzPad = str_pad($juzNumber, 2, '0', STR_PAD_LEFT);
            return "{$prefix}/JUZ{$juzPad}/{$year}/" . str_pad($count, 4, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Get juz boundaries (simplified - actual implementation would be more detailed)
     */
    private static function getJuzBoundaries()
    {
        // This is a simplified version. In production, you'd have a complete mapping
        return [
            ['juz' => 1, 'total_ayat' => 148, 'surahs' => [['surah_number' => 1], ['surah_number' => 2, 'to_ayat' => 141]]],
            ['juz' => 2, 'total_ayat' => 111, 'surahs' => [['surah_number' => 2, 'from_ayat' => 142, 'to_ayat' => 252]]],
            // ... rest of juz boundaries
            // For production, implement complete 30 juz mappings
        ];
    }
}
