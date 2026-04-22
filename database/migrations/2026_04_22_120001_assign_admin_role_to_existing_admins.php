<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all admins that don't have 'Admin Pesantren' role
        $admins = DB::table('users')
            ->where('user_type', 'admin')
            ->get();

        foreach ($admins as $admin) {
            // Check if user already has this role via model_has_roles
            $existingRole = DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('model_has_roles.model_id', $admin->id)
                ->where('model_has_roles.model_type', 'App\\Models\\User')
                ->where('roles.name', 'Admin Pesantren')
                ->exists();

            if (!$existingRole) {
                // Get the role_id for 'Admin Pesantren'
                $role = DB::table('roles')->where('name', 'Admin Pesantren')->first();
                
                if ($role) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $role->id,
                        'model_type' => 'App\\Models\\User',
                        'model_id' => $admin->id,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is for populating data, not creating tables
        // For safety, we'll just leave this empty
    }
};
