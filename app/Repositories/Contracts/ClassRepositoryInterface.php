<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Classes;
use App\Models\UstadzProfile;
use App\Models\SantriProfile;

interface ClassRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get active classes.
     */
    public function getActive(): Collection;

    /**
     * Get classes with available seats.
     */
    public function getWithAvailableSeats(): Collection;

    /**
     * Get classes taught by ustadz.
     */
    public function getByUstadz(int $ustadzProfileId): Collection;

    /**
     * Get classes for santri.
     */
    public function getBySantri(int $santriProfileId): Collection;

    /**
     * Assign ustadz to class.
     */
    public function assignUstadz(int $classId, int $ustadzProfileId): Classes;

    /**
     * Remove ustadz from class.
     */
    public function removeUstadz(int $classId, int $ustadzProfileId): Classes;

    /**
     * Enroll santri to class.
     */
    public function enrollSantri(int $classId, int $santriProfileId): Classes;

    /**
     * Remove santri from class.
     */
    public function removeSantri(int $classId, int $santriProfileId): Classes;

    /**
     * Graduate santri from class.
     */
    public function graduateSantri(int $classId, int $santriProfileId): Classes;

    /**
     * Get class statistics.
     */
    public function getStatistics(int $classId): array;
}
