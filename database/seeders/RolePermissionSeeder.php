<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ============================================
        // CREATE PERMISSIONS
        // ============================================

        $permissions = [
            // User Management
            'manage_users',
            'create_users',
            'edit_users',
            'delete_users',
            'activate_users',

            // Pesantren Management
            'manage_pesantren',
            'view_pesantren_settings',
            'edit_pesantren_settings',

            // Class Management
            'manage_classes',
            'create_classes',
            'edit_classes',
            'delete_classes',
            'assign_ustadz',
            'enroll_santri',

            // Hafalan Management
            'create_hafalan',
            'edit_hafalan',
            'delete_hafalan',
            'verify_hafalan',
            'view_own_hafalan',
            'view_all_hafalan',
            'view_class_hafalan',

            // Certificate Management
            'manage_certificates',
            'create_certificate_template',
            'request_certificate',
            'approve_certificate',
            'download_certificate',

            // Appreciation Fund
            'manage_appreciation_funds',
            'donate_appreciation_fund',
            'verify_appreciation_fund',
            'disburse_appreciation_fund',
            'view_fund_reports',

            // Subscription & Payment
            'manage_subscriptions',
            'upgrade_subscription',
            'verify_payments',

            // Reports & Analytics
            'view_reports',
            'view_dashboard',
            'view_statistics',
            'export_reports',

            // General User Features
            'set_targets',
            'set_reminders',
            'upgrade_to_pro',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // ============================================
        // CREATE ROLES & ASSIGN PERMISSIONS
        // ============================================

        // 1. SUPER ADMIN
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all()); // All permissions

        // 2. ADMIN PESANTREN
        $adminPesantren = Role::create(['name' => 'Admin Pesantren']);
        $adminPesantren->givePermissionTo([
            'manage_users',
            'create_users',
            'edit_users',
            'delete_users',
            'activate_users',

            'view_pesantren_settings',
            'edit_pesantren_settings',

            'manage_classes',
            'create_classes',
            'edit_classes',
            'delete_classes',
            'assign_ustadz',
            'enroll_santri',

            'view_all_hafalan',

            'manage_certificates',
            'create_certificate_template',

            'manage_appreciation_funds',
            'verify_appreciation_fund',
            'disburse_appreciation_fund',
            'view_fund_reports',

            'manage_subscriptions',
            'upgrade_subscription',

            'view_reports',
            'view_dashboard',
            'view_statistics',
            'export_reports',
        ]);

        // 3. USTADZ
        $ustadz = Role::create(['name' => 'Ustadz']);
        $ustadz->givePermissionTo([
            'create_hafalan',
            'edit_hafalan',
            'verify_hafalan',
            'view_class_hafalan',

            'approve_certificate',

            'view_fund_reports', // View their own funds only

            'view_dashboard',
            'view_statistics',
        ]);

        // 4. SANTRI
        $santri = Role::create(['name' => 'Santri']);
        $santri->givePermissionTo([
            'view_own_hafalan',

            'request_certificate',
            'download_certificate',

            'view_dashboard',
        ]);

        // 5. WALI SANTRI
        $wali = Role::create(['name' => 'Wali Santri']);
        $wali->givePermissionTo([
            'view_own_hafalan', // View children's hafalan

            'donate_appreciation_fund',

            'view_dashboard',
            'view_statistics', // View children's statistics
        ]);

        // 6. STAKEHOLDER (Kyai, Pengurus Yayasan)
        $stakeholder = Role::create(['name' => 'Stakeholder']);
        $stakeholder->givePermissionTo([
            'view_all_hafalan',

            'view_fund_reports',

            'view_reports',
            'view_dashboard',
            'view_statistics',
            'export_reports',
        ]);

        // 7. GENERAL USER (User Umum)
        $generalUser = Role::create(['name' => 'General User']);
        $generalUser->givePermissionTo([
            'create_hafalan', // Input own hafalan
            'edit_hafalan', // Edit own hafalan
            'delete_hafalan', // Delete own hafalan
            'view_own_hafalan',

            'request_certificate', // For PRO users
            'download_certificate',

            'set_targets',
            'set_reminders',
            'upgrade_to_pro',

            'view_dashboard',
        ]);

        $this->command->info('✅ Roles and Permissions created successfully!');
        $this->command->info('');
        $this->command->info('Created Roles:');
        $this->command->info('1. Super Admin (All permissions)');
        $this->command->info('2. Admin Pesantren');
        $this->command->info('3. Ustadz');
        $this->command->info('4. Santri');
        $this->command->info('5. Wali Santri');
        $this->command->info('6. Stakeholder');
        $this->command->info('7. General User');
        $this->command->info('');
        $this->command->info('Total Permissions: ' . Permission::count());
    }
}
