<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\SantriProfile;
use App\Models\UstadzProfile;
use App\Models\WaliProfile;
use App\Models\StakeholderProfile;
use App\Models\GeneralUserProfile;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * Constructor.
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Get users by type.
     */
    public function getByType(string $type): Collection
    {
        return $this->model->where('user_type', $type)
            ->with(['pesantren:id,name'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get users by pesantren.
     */
    public function getByPesantren(int $pesantrenId): Collection
    {
        return $this->model->where('pesantren_id', $pesantrenId)
            ->orderBy('user_type')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get active users.
     */
    public function getActive(): Collection
    {
        return $this->model->where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get pending users.
     */
    public function getPending(): Collection
    {
        return $this->model->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Activate user.
     */
    public function activate(int $userId): User
    {
        $user = $this->findOrFail($userId);
        $user->update(['status' => 'active']);
        return $user->fresh();
    }

    /**
     * Deactivate user.
     */
    public function deactivate(int $userId): User
    {
        $user = $this->findOrFail($userId);
        $user->update(['status' => 'inactive']);
        return $user->fresh();
    }

    /**
     * Create user with profile.
     */
    public function createWithProfile(array $userData, array $profileData, string $userType): User
    {
        $this->beginTransaction();

        try {
            // Hash password if provided
            if (isset($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            }

            // Set user type
            $userData['user_type'] = $userType;

            // Create user
            $user = $this->model->create($userData);

            // Create appropriate profile
            match ($userType) {
                'santri' => SantriProfile::create([
                    'user_id' => $user->id,
                    'pesantren_id' => $user->pesantren_id,
                    ...$profileData
                ]),
                'ustadz' => UstadzProfile::create([
                    'user_id' => $user->id,
                    'pesantren_id' => $user->pesantren_id,
                    ...$profileData
                ]),
                'wali' => WaliProfile::create([
                    'user_id' => $user->id,
                    'pesantren_id' => $user->pesantren_id,
                    ...$profileData
                ]),
                'stakeholder' => StakeholderProfile::create([
                    'user_id' => $user->id,
                    'pesantren_id' => $user->pesantren_id,
                    ...$profileData
                ]),
                'general' => GeneralUserProfile::create([
                    'user_id' => $user->id,
                    ...$profileData
                ]),
                default => null,
            };

            $this->commit();

            return $user->fresh();
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    /**
     * Get users for DataTable.
     */
    public function getForDataTable(array $request): array
    {
        $query = $this->model->with(['pesantren:id,name']);

        // Total records
        $totalRecords = $query->count();

        // Search
        if (!empty($request['search']['value'])) {
            $search = $request['search']['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by type
        if (!empty($request['user_type'])) {
            $query->where('user_type', $request['user_type']);
        }

        // Filter by status
        if (!empty($request['status'])) {
            $query->where('status', $request['status']);
        }

        // Filter by pesantren
        if (!empty($request['pesantren_id'])) {
            $query->where('pesantren_id', $request['pesantren_id']);
        }

        // Filtered records
        $filteredRecords = $query->count();

        // Ordering
        if (!empty($request['order'])) {
            $columnIndex = $request['order'][0]['column'];
            $columnName = $request['columns'][$columnIndex]['data'];
            $direction = $request['order'][0]['dir'];
            $query->orderBy($columnName, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
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
     * Find by email.
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Find by phone.
     */
    public function findByPhone(string $phone): ?User
    {
        return $this->model->where('phone', $phone)->first();
    }

    /**
     * Count by type.
     */
    public function countByType(string $type): int
    {
        return $this->model->where('user_type', $type)->count();
    }

    /**
     * Count by pesantren.
     */
    public function countByPesantren(int $pesantrenId): int
    {
        return $this->model->where('pesantren_id', $pesantrenId)->count();
    }
}
