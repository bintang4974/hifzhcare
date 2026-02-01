<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Hafalan;

interface HafalanRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get paginated hafalans with filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    
    /**
     * Get hafalans for DataTable (server-side).
     */
    public function getForDataTable(array $request): array;
    
    /**
     * Get hafalans by user.
     */
    public function getByUser(int $userId): Collection;
    
    /**
     * Get hafalans by class.
     */
    public function getByClass(int $classId): Collection;
    
    /**
     * Count hafalans by user.
     */
    public function countByUser(int $userId): int;
    
    /**
     * Get total ayat count by user.
     */
    public function getTotalAyatCount(int $userId): int;
    
    /**
     * Get completed juz count by user.
     */
    public function getCompletedJuzCount(int $userId): int;
    
    /**
     * Check if juz is complete.
     */
    public function isJuzComplete(int $userId, int $juzNumber): bool;
    
    /**
     * Get progress by juz.
     */
    public function getProgressByJuz(int $userId): array;
    
    /**
     * Get progress by month.
     */
    public function getProgressByMonth(int $userId, int $year): array;
    
    /**
     * Verify hafalan.
     */
    public function verify(int $hafalanId, int $ustadzId, ?string $notes = null): Hafalan;
    
    /**
     * Reject hafalan.
     */
    public function reject(int $hafalanId, int $ustadzId, string $reason): Hafalan;
    
    /**
     * Get pending hafalans for ustadz.
     */
    public function getPendingForUstadz(int $ustadzId): Collection;
    
    /**
     * Get hafalans by date range.
     */
    public function getByDateRange(int $userId, string $startDate, string $endDate): Collection;
}