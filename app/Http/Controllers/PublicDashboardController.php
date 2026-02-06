<?php

namespace App\Http\Controllers;

use App\Models\{Pesantren, SantriProfile, Certificate};
use Illuminate\Http\Request;

class PublicDashboardController extends Controller
{
    /**
     * Display public landing page
     */
    public function index($pesantrenCode = null)
    {
        // Get pesantren (either from code or first active)
        if ($pesantrenCode) {
            $pesantren = Pesantren::where('code', $pesantrenCode)
                ->where('status', 'active')
                ->firstOrFail();
        } else {
            $pesantren = Pesantren::where('status', 'active')->first();
        }

        if (!$pesantren) {
            abort(404, 'Pesantren tidak ditemukan');
        }

        // Public Statistics
        $statistics = [
            'total_santri' => SantriProfile::where('pesantren_id', $pesantren->id)->count(),
            'total_graduates' => Certificate::where('pesantren_id', $pesantren->id)->count(),
            'completion_rate' => $this->getCompletionRate($pesantren->id),
            'years_established' => now()->year - ($pesantren->established_year ?? now()->year),
        ];

        // Recent Graduates (with photos if available)
        $recentGraduates = Certificate::where('pesantren_id', $pesantren->id)
            ->with(['user', 'user.santriProfile'])
            ->latest('issue_date')
            ->take(6)
            ->get();

        // Achievements & Awards
        $achievements = $this->getAchievements($pesantren->id);

        // Public Announcements (placeholder)
        $announcements = [];

        // Testimonials
        $testimonials = $this->getTestimonials($pesantren->id);

        // Programs Offered
        $programs = [
            [
                'name' => 'Tahfidz Al-Quran 30 Juz',
                'description' => 'Program menghafal Al-Quran 30 juz dengan metode terbukti efektif',
                'icon' => 'book-quran',
                'color' => 'blue'
            ],
            [
                'name' => 'Tahsin & Tajwid',
                'description' => 'Perbaikan bacaan dan pembelajaran tajwid yang benar',
                'icon' => 'microphone',
                'color' => 'green'
            ],
            [
                'name' => 'Muraja\'ah Berkala',
                'description' => 'Sistem muraja\'ah terjadwal untuk menjaga hafalan',
                'icon' => 'sync-alt',
                'color' => 'purple'
            ],
            [
                'name' => 'Sertifikasi Resmi',
                'description' => 'Sertifikat resmi untuk santri yang telah menyelesaikan program',
                'icon' => 'certificate',
                'color' => 'yellow'
            ],
        ];

        return view('dashboards.public', compact(
            'pesantren',
            'statistics',
            'recentGraduates',
            'achievements',
            'announcements',
            'testimonials',
            'programs'
        ));
    }

    /**
     * Calculate completion rate
     */
    protected function getCompletionRate($pesantrenId)
    {
        $totalSantri = SantriProfile::where('pesantren_id', $pesantrenId)->count();

        if ($totalSantri == 0) return 0;

        $completedSantri = SantriProfile::where('pesantren_id', $pesantrenId)
            ->where('progress_percentage', '>=', 100)
            ->count();

        return round(($completedSantri / $totalSantri) * 100, 2);
    }

    /**
     * Get achievements
     */
    protected function getAchievements($pesantrenId)
    {
        // This could come from a dedicated Achievement model
        // For now, we'll generate from available data

        $graduates = Certificate::where('pesantren_id', $pesantrenId)->count();
        $topStudents = SantriProfile::where('pesantren_id', $pesantrenId)
            ->where('progress_percentage', '>=', 100)
            ->count();

        return [
            [
                'title' => "{$graduates} Lulusan Hafidz/Hafidzah",
                'description' => 'Santri yang telah menyelesaikan program tahfidz',
                'icon' => 'graduation-cap',
                'color' => 'green'
            ],
            [
                'title' => "{$topStudents} Hafidz Berprestasi",
                'description' => 'Santri dengan pencapaian luar biasa',
                'icon' => 'trophy',
                'color' => 'yellow'
            ],
            [
                'title' => 'Metode Pembelajaran Terbukti',
                'description' => 'Sistem pembelajaran yang telah teruji efektif',
                'icon' => 'check-circle',
                'color' => 'blue'
            ],
        ];
    }

    /**
     * Get testimonials
     */
    protected function getTestimonials($pesantrenId)
    {
        // This could come from a Testimonial model
        // For now, returning sample data

        return [
            [
                'name' => 'Ahmad Rizki',
                'role' => 'Orang Tua Santri',
                'photo' => null,
                'message' => 'Alhamdulillah, anak saya sangat berkembang di pesantren ini. Metode pembelajaran yang diterapkan sangat baik.',
                'rating' => 5
            ],
            [
                'name' => 'Siti Nurhaliza',
                'role' => 'Alumni',
                'photo' => null,
                'message' => 'Pengalaman berharga selama menghafal di sini. Ustadz dan ustadzah sangat sabar dan perhatian.',
                'rating' => 5
            ],
            [
                'name' => 'Muhammad Fauzan',
                'role' => 'Wali Santri',
                'photo' => null,
                'message' => 'Fasilitas lengkap dan suasana kondusif untuk menghafal Al-Quran. Sangat direkomendasikan!',
                'rating' => 5
            ],
        ];
    }

    /**
     * About page
     */
    public function about($pesantrenCode)
    {
        $pesantren = Pesantren::where('code', $pesantrenCode)
            ->where('status', 'active')
            ->firstOrFail();

        return view('dashboards.public-about', compact('pesantren'));
    }

    /**
     * Contact page
     */
    public function contact($pesantrenCode)
    {
        $pesantren = Pesantren::where('code', $pesantrenCode)
            ->where('status', 'active')
            ->firstOrFail();

        return view('dashboards.public-contact', compact('pesantren'));
    }

    /**
     * Submit inquiry form
     */
    public function submitInquiry(Request $request)
    {
        $validated = $request->validate([
            'pesantren_code' => 'required|exists:pesantrens,code',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'inquiry_type' => 'required|in:registration,information,complaint,other',
        ]);

        // Store inquiry (you might want to create an Inquiry model)
        // For now, we'll just send email

        $pesantren = Pesantren::where('code', $validated['pesantren_code'])->first();

        // Send email notification to pesantren admin
        // Mail::to($pesantren->email)->send(new InquiryReceived($validated));

        // Send confirmation to user
        // Mail::to($validated['email'])->send(new InquiryConfirmation($validated));

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Pesan Anda telah diterima. Kami akan segera menghubungi Anda.'
        ]);
    }

    /**
     * Register interest form
     */
    public function registerInterest(Request $request)
    {
        $validated = $request->validate([
            'pesantren_code' => 'required|exists:pesantrens,code',
            'parent_name' => 'required|string|max:255',
            'parent_phone' => 'required|string|max:20',
            'parent_email' => 'nullable|email|max:255',
            'student_name' => 'required|string|max:255',
            'student_age' => 'required|integer|min:5|max:25',
            'student_gender' => 'required|in:L,P',
            'current_memorization' => 'nullable|string',
            'expected_start' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        // Store registration interest
        // Could be stored in a ProspectiveStudent or RegistrationInterest model

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran minat berhasil! Tim kami akan menghubungi Anda untuk informasi lebih lanjut.'
        ]);
    }

    /**
     * Gallery page
     */
    public function gallery($pesantrenCode)
    {
        $pesantren = Pesantren::where('code', $pesantrenCode)
            ->where('status', 'active')
            ->firstOrFail();

        // Get photos from graduates, events, etc.
        $photos = $this->getGalleryPhotos($pesantren->id);

        return view('dashboards.public-gallery', compact('pesantren', 'photos'));
    }

    /**
     * Get gallery photos
     */
    protected function getGalleryPhotos($pesantrenId)
    {
        // This could come from a Photo/Gallery model
        // For now, get graduate photos

        $graduatePhotos = Certificate::where('pesantren_id', $pesantrenId)
            ->with(['santri.user'])
            ->whereNotNull('photo_path')
            ->latest()
            ->take(12)
            ->get()
            ->map(function ($cert) {
                return [
                    'url' => $cert->photo_path,
                    'title' => $cert->santri->user->name,
                    'description' => 'Lulusan ' . $cert->issue_date->format('Y'),
                    'type' => 'graduate'
                ];
            });

        return $graduatePhotos;
    }

    /**
     * News & Events page
     */
    public function news($pesantrenCode)
    {
        $pesantren = Pesantren::where('code', $pesantrenCode)
            ->where('status', 'active')
            ->firstOrFail();

        // $announcements = Announcement::where('pesantren_id', $pesantren->id)
        //     ->where('is_public', true)
        //     ->where('published_at', '<=', now())
        //     ->latest('published_at')
        //     ->paginate(9);

        return view('dashboards.public-news', compact('pesantren', 'announcements'));
    }

    /**
     * Single news/announcement page
     */
    public function newsDetail($pesantrenCode, $announcementId)
    {
        $pesantren = Pesantren::where('code', $pesantrenCode)
            ->where('status', 'active')
            ->firstOrFail();

        // $announcement = Announcement::where('pesantren_id', $pesantren->id)
        //     ->where('is_public', true)
        //     ->findOrFail($announcementId);

        return view('dashboards.public-news-detail', compact('pesantren', 'announcement'));
    }
}
