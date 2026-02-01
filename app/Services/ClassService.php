<?php

namespace App\Services\Class;

use App\Models\Classes;
use App\Repositories\Contracts\ClassRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassService
{
    /**
     * Constructor.
     */
    public function __construct(
        protected ClassRepositoryInterface $classRepository
    ) {}

    /**
     * Create new class.
     */
    public function createClass(array $data): Classes
    {
        // Auto-generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = strtoupper(Str::slug($data['name'])) . '-' . date('Y');
        }

        return $this->classRepository->create($data);
    }

    /**
     * Assign ustadz to class.
     */
    public function assignUstadz(int $classId, int $ustadzProfileId): Classes
    {
        return $this->classRepository->assignUstadz($classId, $ustadzProfileId);
    }

    /**
     * Enroll santri to class.
     */
    public function enrollSantri(int $classId, int $santriProfileId): Classes
    {
        $class = $this->classRepository->findOrFail($classId);

        // Check if class is full
        if ($class->is_full) {
            throw new \Exception('Kelas sudah penuh. Tidak dapat menambah santri.');
        }

        return $this->classRepository->enrollSantri($classId, $santriProfileId);
    }

    /**
     * Graduate santri from class.
     */
    public function graduateSantri(int $classId, int $santriProfileId): Classes
    {
        DB::beginTransaction();

        try {
            // Graduate from class
            $class = $this->classRepository->graduateSantri($classId, $santriProfileId);

            // Update santri profile
            $santri = \App\Models\SantriProfile::findOrFail($santriProfileId);
            $santri->update([
                'graduation_date' => now(),
            ]);

            // Update user status
            $santri->user->update([
                'status' => 'graduated',
            ]);

            DB::commit();

            return $class;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
