<?php

namespace App\Services\Hafalan;

use App\Models\Hafalan;
use App\Models\HafalanAudio;
use App\Models\SantriProfile;
use App\Models\GeneralUserProfile;
use App\Repositories\Contracts\HafalanRepositoryInterface;
use App\Support\Helpers\QuranHelper;
use App\Jobs\Audio\CompressAudioJob;
use App\Events\Hafalan\HafalanCreated;
use App\Events\Hafalan\HafalanVerified;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class HafalanService
{
    /**
     * Constructor.
     */
    public function __construct(
        protected HafalanRepositoryInterface $hafalanRepository
    ) {}

    /**
     * Create new hafalan.
     */
    public function createHafalan(array $data, ?UploadedFile $audioFile = null): Hafalan
    {
        DB::beginTransaction();

        try {
            // Auto-calculate juz_number if not provided
            if (empty($data['juz_number'])) {
                $data['juz_number'] = QuranHelper::getJuzNumber(
                    $data['surah_number'],
                    $data['ayat_start']
                );
            }

            // Create hafalan
            $hafalan = $this->hafalanRepository->create($data);

            // Handle audio upload if provided
            if ($audioFile) {
                $this->handleAudioUpload($hafalan, $audioFile);
            }

            // Update user progress
            $this->updateUserProgress($hafalan->user_id);

            DB::commit();

            // Fire event
            event(new HafalanCreated($hafalan));

            return $hafalan->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Update hafalan.
     */
    public function updateHafalan(int $hafalanId, array $data, ?UploadedFile $audioFile = null): Hafalan
    {
        DB::beginTransaction();

        try {
            // Recalculate juz if surah/ayat changed
            if (isset($data['surah_number']) || isset($data['ayat_start'])) {
                $hafalan = $this->hafalanRepository->findOrFail($hafalanId);

                $surahNumber = $data['surah_number'] ?? $hafalan->surah_number;
                $ayatStart = $data['ayat_start'] ?? $hafalan->ayat_start;

                $data['juz_number'] = QuranHelper::getJuzNumber($surahNumber, $ayatStart);
            }

            $hafalan = $this->hafalanRepository->update($hafalanId, $data);

            // Handle audio upload if provided
            if ($audioFile) {
                $this->handleAudioUpload($hafalan, $audioFile);
            }

            // Update user progress
            $this->updateUserProgress($hafalan->user_id);

            DB::commit();

            return $hafalan->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete hafalan.
     */
    public function deleteHafalan(int $hafalanId): bool
    {
        DB::beginTransaction();

        try {
            $hafalan = $this->hafalanRepository->findOrFail($hafalanId);
            $userId = $hafalan->user_id;

            // Delete associated audio files
            foreach ($hafalan->audios as $audio) {
                if (Storage::disk('public')->exists($audio->file_path)) {
                    Storage::disk('public')->delete($audio->file_path);
                }
                $audio->delete();
            }

            // Delete hafalan
            $deleted = $this->hafalanRepository->delete($hafalanId);

            // Update user progress
            $this->updateUserProgress($userId);

            DB::commit();

            return $deleted;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Verify hafalan.
     */
    public function verifyHafalan(int $hafalanId, int $ustadzId, ?string $notes = null): Hafalan
    {
        DB::beginTransaction();

        try {
            $hafalan = $this->hafalanRepository->verify($hafalanId, $ustadzId, $notes);

            // Update user progress
            $this->updateUserProgress($hafalan->user_id);

            // Check if juz is now complete
            $juzComplete = $this->hafalanRepository->isJuzComplete(
                $hafalan->user_id,
                $hafalan->juz_number
            );

            if ($juzComplete) {
                $this->handleJuzCompletion($hafalan->user_id, $hafalan->juz_number);
            }

            DB::commit();

            // Fire event
            event(new HafalanVerified($hafalan));

            return $hafalan->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Reject hafalan.
     */
    public function rejectHafalan(int $hafalanId, int $ustadzId, string $reason): Hafalan
    {
        return $this->hafalanRepository->reject($hafalanId, $ustadzId, $reason);
    }

    /**
     * Get user progress summary.
     */
    public function getProgress(int $userId): array
    {
        $totalHafalan = $this->hafalanRepository->countByUser($userId);
        $totalVerified = $this->hafalanRepository->find($userId)
            ->where('status', 'verified')
            ->count();
        $totalAyat = $this->hafalanRepository->getTotalAyatCount($userId);
        $totalJuz = $this->hafalanRepository->getCompletedJuzCount($userId);

        $progressByJuz = $this->hafalanRepository->getProgressByJuz($userId);
        $progressByMonth = $this->hafalanRepository->getProgressByMonth($userId, date('Y'));

        return [
            'total_hafalan' => $totalHafalan,
            'total_verified' => $totalVerified,
            'total_pending' => $totalHafalan - $totalVerified,
            'total_ayat' => $totalAyat,
            'total_juz' => $totalJuz,
            'progress_percentage' => round(($totalJuz / 30) * 100, 2),
            'by_juz' => $progressByJuz,
            'by_month' => $progressByMonth,
        ];
    }

    /**
     * Handle audio upload.
     */
    protected function handleAudioUpload(Hafalan $hafalan, UploadedFile $file): HafalanAudio
    {
        // Generate unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Determine storage path based on user type
        $path = $hafalan->pesantren_id
            ? "audio/{$hafalan->pesantren_id}/{$hafalan->user_id}"
            : "audio/general/{$hafalan->user_id}";

        // Store file
        $filePath = $file->storeAs($path, $filename, 'public');

        // Create audio record
        $audio = HafalanAudio::create([
            'pesantren_id' => $hafalan->pesantren_id,
            'hafalan_id' => $hafalan->id,
            'original_filename' => $file->getClientOriginalName(),
            'stored_filename' => $filename,
            'file_path' => $filePath,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'status' => 'pending',
        ]);

        // Dispatch compression job
        CompressAudioJob::dispatch($audio->id);

        return $audio;
    }

    /**
     * Update user progress (santri or general user).
     */
    protected function updateUserProgress(int $userId): void
    {
        $user = \App\Models\User::findOrFail($userId);

        $totalAyat = $this->hafalanRepository->getTotalAyatCount($userId);
        $totalJuz = $this->hafalanRepository->getCompletedJuzCount($userId);

        if ($user->user_type === 'santri' && $user->santriProfile) {
            $user->santriProfile->update([
                'total_ayat_completed' => $totalAyat,
                'total_juz_completed' => $totalJuz,
            ]);
        } elseif ($user->user_type === 'general' && $user->generalUserProfile) {
            // Update streak
            $lastHafalan = $this->hafalanRepository->getByUser($userId)
                ->where('status', 'verified')
                ->sortByDesc('hafalan_date')
                ->first();

            $currentStreak = $this->calculateStreak($userId);

            $user->generalUserProfile->update([
                'total_ayat_completed' => $totalAyat,
                'total_juz_completed' => $totalJuz,
                'current_streak_days' => $currentStreak,
                'longest_streak_days' => max(
                    $user->generalUserProfile->longest_streak_days ?? 0,
                    $currentStreak
                ),
                'last_hafalan_date' => $lastHafalan?->hafalan_date,
            ]);
        }
    }

    /**
     * Calculate user streak.
     */
    protected function calculateStreak(int $userId): int
    {
        $hafalans = $this->hafalanRepository->getByUser($userId)
            ->where('status', 'verified')
            ->sortByDesc('hafalan_date');

        if ($hafalans->isEmpty()) {
            return 0;
        }

        $streak = 0;
        $currentDate = now()->startOfDay();

        foreach ($hafalans as $hafalan) {
            $hafalanDate = $hafalan->hafalan_date->startOfDay();
            $daysDiff = $currentDate->diffInDays($hafalanDate);

            if ($daysDiff === $streak || ($streak === 0 && $daysDiff <= 1)) {
                $streak++;
                $currentDate = $hafalanDate->copy()->subDay();
            } else {
                break;
            }
        }

        return $streak;
    }

    /**
     * Handle juz completion.
     */
    protected function handleJuzCompletion(int $userId, int $juzNumber): void
    {
        $user = \App\Models\User::findOrFail($userId);

        // For general PRO users, auto-generate achievement certificate
        if ($user->user_type === 'general' && $user->isProUser()) {
            // Dispatch job to generate certificate
            \App\Jobs\Certificate\GenerateCertificatePdfJob::dispatch(
                userId: $userId,
                type: 'general_achievement',
                metadata: ['juz_completed' => $juzNumber]
            );
        }
    }

    /**
     * Validate ayat range.
     */
    public function validateAyatRange(int $surahNumber, int $ayatStart, int $ayatEnd): bool
    {
        $maxAyat = QuranHelper::getMaxAyat($surahNumber);

        return $ayatStart >= 1
            && $ayatStart <= $maxAyat
            && $ayatEnd >= $ayatStart
            && $ayatEnd <= $maxAyat;
    }
}
