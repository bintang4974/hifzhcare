<?php

namespace App\Repositories\Eloquent;

use App\Models\Classes;
use App\Models\UstadzProfile;
use App\Models\SantriProfile;
use App\Repositories\Contracts\ClassRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ClassRepository extends BaseRepository implements ClassRepositoryInterface
{
    /**
     * Constructor.
     */
    public function __construct(Classes $model)
    {
        parent::__construct($model);
    }

    /**
     * Get active classes.
     */
    public function getActive(): Collection
    {
        return $this->model->where('status', 'active')
            ->with(['ustadzProfiles', 'santriProfiles'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get classes with available seats.
     */
    public function getWithAvailableSeats(): Collection
    {
        return $this->model->where('status', 'active')
            ->whereRaw('current_student_count < max_capacity')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get classes taught by ustadz.
     */
    public function getByUstadz(int $ustadzProfileId): Collection
    {
        return $this->model->whereHas('ustadzProfiles', function ($q) use ($ustadzProfileId) {
            $q->where('ustadz_profile_id', $ustadzProfileId)
                ->where('class_ustadz.status', 'active');
        })
            ->with(['santriProfiles'])
            ->get();
    }

    /**
     * Get classes for santri.
     */
    public function getBySantri(int $santriProfileId): Collection
    {
        return $this->model->whereHas('santriProfiles', function ($q) use ($santriProfileId) {
            $q->where('santri_profile_id', $santriProfileId)
                ->where('class_santri.status', 'active');
        })
            ->with(['ustadzProfiles'])
            ->get();
    }

    /**
     * Assign ustadz to class.
     */
    public function assignUstadz(int $classId, int $ustadzProfileId): Classes
    {
        $class = $this->findOrFail($classId);
        $ustadz = UstadzProfile::findOrFail($ustadzProfileId);

        $class->assignUstadz($ustadz);

        return $class->fresh();
    }

    /**
     * Remove ustadz from class.
     */
    public function removeUstadz(int $classId, int $ustadzProfileId): Classes
    {
        $class = $this->findOrFail($classId);
        $ustadz = UstadzProfile::findOrFail($ustadzProfileId);

        $class->removeUstadz($ustadz);

        return $class->fresh();
    }

    /**
     * Enroll santri to class.
     */
    public function enrollSantri(int $classId, int $santriProfileId): Classes
    {
        $class = $this->findOrFail($classId);
        $santri = SantriProfile::findOrFail($santriProfileId);

        $class->enrollSantri($santri);

        return $class->fresh();
    }

    /**
     * Remove santri from class.
     */
    public function removeSantri(int $classId, int $santriProfileId): Classes
    {
        $class = $this->findOrFail($classId);
        $santri = SantriProfile::findOrFail($santriProfileId);

        $class->removeSantri($santri);

        return $class->fresh();
    }

    /**
     * Graduate santri from class.
     */
    public function graduateSantri(int $classId, int $santriProfileId): Classes
    {
        $class = $this->findOrFail($classId);
        $santri = SantriProfile::findOrFail($santriProfileId);

        $class->graduateSantri($santri);

        return $class->fresh();
    }

    /**
     * Get class statistics.
     */
    public function getStatistics(int $classId): array
    {
        $class = $this->findOrFail($classId);

        return [
            'total_santri' => $class->activeSantri()->count(),
            'total_ustadz' => $class->activeUstadz()->count(),
            'total_hafalan' => $class->hafalans()->count(),
            'verified_hafalan' => $class->hafalans()->where('status', 'verified')->count(),
            'pending_hafalan' => $class->hafalans()->where('status', 'pending')->count(),
            'available_seats' => $class->available_seats,
        ];
    }
}
