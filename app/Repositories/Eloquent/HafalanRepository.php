<?php

namespace App\Repositories\Eloquent;

use App\Models\Hafalan;
use App\Repositories\Contracts\HafalanRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class HafalanRepository extends BaseRepository implements HafalanRepositoryInterface
{
    /**
     * Constructor.
     */
    public function __construct(Hafalan $model)
    {
        parent::__construct($model);
    }

    /**
     * Get paginated hafalans with filters.
     */
    public function getPaginated(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->with(['user:id,name', 'createdBy:id,name', 'verifiedBy', 'audios']);

        // Apply filters
        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['class_id'])) {
            $query->where('class_id', $filters['class_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['juz_number'])) {
            $query->where('juz_number', $filters['juz_number']);
        }

        if (!empty($filters['surah_number'])) {
            $query->where('surah_number', $filters['surah_number']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('hafalan_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('hafalan_date', '<=', $filters['date_to']);
        }

        if (!empty($filters['created_by'])) {
            $query->where('created_by_user_id', $filters['created_by']);
        }

        if (!empty($filters['verified_by'])) {
            $query->where('verified_by_ustadz_id', $filters['verified_by']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'hafalan_date';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        return $query->paginate($perPage);
    }

    /**
     * Get hafalans for DataTable (server-side).
     */
    public function getForDataTable(array $request): array
    {
        $query = $this->model->with(['user:id,name', 'createdBy:id,name', 'verifiedBy']);

        // Total records before filtering
        $totalRecords = $query->count();

        // Search
        if (!empty($request['search']['value'])) {
            $search = $request['search']['value'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('surah_number', 'like', "%{$search}%")
                    ->orWhere('juz_number', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if (!empty($request['status'])) {
            $query->where('status', $request['status']);
        }

        // Filter by class
        if (!empty($request['class_id'])) {
            $query->where('class_id', $request['class_id']);
        }

        // Total records after filtering
        $filteredRecords = $query->count();

        // Ordering
        if (!empty($request['order'])) {
            $columnIndex = $request['order'][0]['column'];
            $columnName = $request['columns'][$columnIndex]['data'];
            $direction = $request['order'][0]['dir'];

            if ($columnName === 'user_name') {
                $query->join('users', 'hafalans.user_id', '=', 'users.id')
                    ->orderBy('users.name', $direction)
                    ->select('hafalans.*');
            } else {
                $query->orderBy($columnName, $direction);
            }
        } else {
            $query->orderBy('hafalan_date', 'desc');
        }

        // Pagination
        $start = $request['start'] ?? 0;
        $length = $request['length'] ?? 10;
        $query->skip($start)->take($length);

        $data = $query->get();

        return [
            'draw' => intval($request['draw'] ?? 1),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
    }

    /**
     * Get hafalans by user.
     */
    public function getByUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)
            ->with(['audios', 'verifiedBy'])
            ->orderBy('hafalan_date', 'desc')
            ->get();
    }

    /**
     * Get hafalans by class.
     */
    public function getByClass(int $classId): Collection
    {
        return $this->model->where('class_id', $classId)
            ->with(['user:id,name', 'audios'])
            ->orderBy('hafalan_date', 'desc')
            ->get();
    }

    /**
     * Count hafalans by user.
     */
    public function countByUser(int $userId): int
    {
        return $this->model->where('user_id', $userId)->count();
    }

    /**
     * Get total ayat count by user.
     */
    public function getTotalAyatCount(int $userId): int
    {
        return $this->model->where('user_id', $userId)
            ->where('status', 'verified')
            ->selectRaw('SUM(ayat_end - ayat_start + 1) as total')
            ->value('total') ?? 0;
    }

    /**
     * Get completed juz count by user.
     */
    public function getCompletedJuzCount(int $userId): int
    {
        $completedJuz = 0;

        for ($juz = 1; $juz <= 30; $juz++) {
            if ($this->isJuzComplete($userId, $juz)) {
                $completedJuz++;
            }
        }

        return $completedJuz;
    }

    /**
     * Check if juz is complete.
     * This is simplified - actual implementation would check all ayat coverage.
     */
    public function isJuzComplete(int $userId, int $juzNumber): bool
    {
        $totalAyat = $this->model->where('user_id', $userId)
            ->where('juz_number', $juzNumber)
            ->where('status', 'verified')
            ->where('type', 'setoran')
            ->selectRaw('SUM(ayat_end - ayat_start + 1) as total')
            ->value('total') ?? 0;

        // Simplified: Consider complete if >= 200 ayat (approximate)
        return $totalAyat >= 200;
    }

    /**
     * Get progress by juz.
     */
    public function getProgressByJuz(int $userId): array
    {
        $progress = [];

        for ($juz = 1; $juz <= 30; $juz++) {
            $totalAyat = $this->model->where('user_id', $userId)
                ->where('juz_number', $juz)
                ->where('status', 'verified')
                ->selectRaw('SUM(ayat_end - ayat_start + 1) as total')
                ->value('total') ?? 0;

            $progress[$juz] = [
                'juz' => $juz,
                'total_ayat' => $totalAyat,
                'is_complete' => $this->isJuzComplete($userId, $juz),
                'percentage' => min(100, round(($totalAyat / 200) * 100, 2)),
            ];
        }

        return $progress;
    }

    /**
     * Get progress by month.
     */
    public function getProgressByMonth(int $userId, int $year): array
    {
        $progress = [];

        for ($month = 1; $month <= 12; $month++) {
            $count = $this->model->where('user_id', $userId)
                ->whereYear('hafalan_date', $year)
                ->whereMonth('hafalan_date', $month)
                ->where('status', 'verified')
                ->count();

            $totalAyat = $this->model->where('user_id', $userId)
                ->whereYear('hafalan_date', $year)
                ->whereMonth('hafalan_date', $month)
                ->where('status', 'verified')
                ->selectRaw('SUM(ayat_end - ayat_start + 1) as total')
                ->value('total') ?? 0;

            $progress[] = [
                'month' => $month,
                'month_name' => date('F', mktime(0, 0, 0, $month, 1)),
                'count' => $count,
                'total_ayat' => $totalAyat,
            ];
        }

        return $progress;
    }

    /**
     * Verify hafalan.
     */
    public function verify(int $hafalanId, int $ustadzId, ?string $notes = null): Hafalan
    {
        $hafalan = $this->findOrFail($hafalanId);

        $hafalan->update([
            'status' => 'verified',
            'verified_by_ustadz_id' => $ustadzId,
            'verified_at' => now(),
            'notes' => $notes ?? $hafalan->notes,
        ]);

        return $hafalan->fresh();
    }

    /**
     * Reject hafalan.
     */
    public function reject(int $hafalanId, int $ustadzId, string $reason): Hafalan
    {
        $hafalan = $this->findOrFail($hafalanId);

        $hafalan->update([
            'status' => 'rejected',
            'verified_by_ustadz_id' => $ustadzId,
            'verified_at' => now(),
            'notes' => $reason,
        ]);

        return $hafalan->fresh();
    }

    /**
     * Get pending hafalans for ustadz.
     */
    public function getPendingForUstadz(int $ustadzId): Collection
    {
        return $this->model->whereHas('class.ustadzProfiles', function ($q) use ($ustadzId) {
            $q->where('ustadz_profile_id', $ustadzId);
        })
            ->where('status', 'pending')
            ->with(['user:id,name', 'class:id,name', 'audios'])
            ->orderBy('hafalan_date', 'asc')
            ->get();
    }

    /**
     * Get hafalans by date range.
     */
    public function getByDateRange(int $userId, string $startDate, string $endDate): Collection
    {
        return $this->model->where('user_id', $userId)
            ->whereBetween('hafalan_date', [$startDate, $endDate])
            ->with(['audios', 'verifiedBy'])
            ->orderBy('hafalan_date', 'desc')
            ->get();
    }
}
