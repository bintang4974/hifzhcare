<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get users by type.
     */
    public function getByType(string $type): Collection;

    /**
     * Get users by pesantren.
     */
    public function getByPesantren(int $pesantrenId): Collection;

    /**
     * Get active users.
     */
    public function getActive(): Collection;

    /**
     * Get pending users.
     */
    public function getPending(): Collection;

    /**
     * Activate user.
     */
    public function activate(int $userId): User;

    /**
     * Deactivate user.
     */
    public function deactivate(int $userId): User;

    /**
     * Create user with profile.
     */
    public function createWithProfile(array $userData, array $profileData, string $userType): User;

    /**
     * Get users for DataTable.
     */
    public function getForDataTable(array $request): array;

    /**
     * Find by email.
     */
    public function findByEmail(string $email): ?User;

    /**
     * Find by phone.
     */
    public function findByPhone(string $phone): ?User;

    /**
     * Count by type.
     */
    public function countByType(string $type): int;

    /**
     * Count by pesantren.
     */
    public function countByPesantren(int $pesantrenId): int;
}
