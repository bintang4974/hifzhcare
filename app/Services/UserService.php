<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    /**
     * Constructor.
     */
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Create santri with optional wali.
     */
    public function createSantriWithWali(array $santriData, ?array $waliData = null): User
    {
        DB::beginTransaction();

        try {
            // Create wali if provided
            $waliId = null;
            if ($waliData) {
                // Check if wali already exists by email or phone
                $existingWali = null;
                if (!empty($waliData['user']['email'])) {
                    $existingWali = $this->userRepository->findByEmail($waliData['user']['email']);
                }

                if (!$existingWali && !empty($waliData['user']['phone'])) {
                    $existingWali = $this->userRepository->findByPhone($waliData['user']['phone']);
                }

                if ($existingWali) {
                    $waliId = $existingWali->waliProfile->id;
                } else {
                    // Generate random password for wali
                    $waliData['user']['password'] = Str::random(10);
                    $waliData['user']['status'] = 'pending'; // Will be activated later

                    $wali = $this->userRepository->createWithProfile(
                        $waliData['user'],
                        $waliData['profile'],
                        'wali'
                    );

                    // Assign role
                    $wali->assignRole('Wali Santri');

                    $waliId = $wali->waliProfile->id;

                    // TODO: Send email with password to wali
                }
            }

            // Add wali_id to santri profile data
            if ($waliId) {
                $santriData['profile']['wali_id'] = $waliId;
            }

            // Generate random password for santri
            $santriData['user']['password'] = Str::random(10);
            $santriData['user']['status'] = 'pending';

            // Create santri
            $santri = $this->userRepository->createWithProfile(
                $santriData['user'],
                $santriData['profile'],
                'santri'
            );

            // Assign role
            $santri->assignRole('Santri');

            // Increment pesantren santri count
            if ($santri->pesantren) {
                $santri->pesantren->increment('current_santri_count');
            }

            DB::commit();

            // TODO: Send email with password to santri

            return $santri->fresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Activate user account.
     */
    public function activateAccount(int $userId, ?string $password = null): User
    {
        $user = $this->userRepository->findOrFail($userId);

        $data = ['status' => 'active'];

        if ($password) {
            $data['password'] = Hash::make($password);
        }

        return $this->userRepository->update($userId, $data);
    }

    /**
     * Deactivate user account.
     */
    public function deactivateAccount(int $userId): User
    {
        return $this->userRepository->deactivate($userId);
    }

    /**
     * Update user password.
     */
    public function updatePassword(int $userId, string $newPassword): User
    {
        return $this->userRepository->update($userId, [
            'password' => Hash::make($newPassword)
        ]);
    }
}
