<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\GeneralUserProfile;
use App\Models\Hafalan;
use App\Models\Pesantren;
use App\Models\SantriProfile;
use App\Models\StakeholderProfile;
use App\Models\User;
use App\Models\UstadzProfile;
use App\Models\WaliProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Demo Data Seeding...');

        // ============================================
        // 1. CREATE PESANTREN
        // ============================================
        $this->command->info('');
        $this->command->info('📦 Creating Pesantren...');

        $pesantren = Pesantren::create([
            'name' => 'Pesantren Tahfidz Al-Quran',
            'slug' => 'pesantren-tahfidz-al-quran',
            'email' => 'info@pesantrentahfidz.com',
            'phone' => '08123456789',
            'address' => 'Jl. Pendidikan No. 123, Jakarta',
            'status' => 'active',
            'subscription_tier' => 'medium',
            'max_santri' => 200,
            'current_santri_count' => 0,
            'is_appreciation_fund_enabled' => true,
            'subscription_expired_at' => now()->addYear(),
            'activated_at' => now(),
        ]);

        $this->command->info('   ✅ Pesantren created: ' . $pesantren->name);

        // ============================================
        // 2. CREATE SUPER ADMIN
        // ============================================
        $this->command->info('');
        $this->command->info('👤 Creating Super Admin...');

        $superAdmin = User::create([
            'pesantren_id' => null,
            'name' => 'Super Admin',
            'email' => 'superadmin@hifzhcare.com',
            'phone' => '08111111111',
            'password' => Hash::make('password'),
            'user_type' => 'super_admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('Super Admin');

        $this->command->info('   ✅ Super Admin: ' . $superAdmin->email . ' / password');

        // ============================================
        // 3. CREATE ADMIN PESANTREN
        // ============================================
        $this->command->info('');
        $this->command->info('👤 Creating Admin Pesantren...');

        $admin = User::create([
            'pesantren_id' => $pesantren->id,
            'name' => 'Admin Pesantren',
            'email' => 'admin@pesantrentahfidz.com',
            'phone' => '08222222222',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Admin Pesantren');

        $this->command->info('   ✅ Admin: ' . $admin->email . ' / password');

        // ============================================
        // 4. CREATE STAKEHOLDER
        // ============================================
        $this->command->info('');
        $this->command->info('👤 Creating Stakeholder...');

        $stakeholder = User::create([
            'pesantren_id' => $pesantren->id,
            'name' => 'KH. Ahmad Dahlan',
            'email' => 'kyai@pesantrentahfidz.com',
            'phone' => '08333333333',
            'password' => Hash::make('password'),
            'user_type' => 'stakeholder',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $stakeholder->assignRole('Stakeholder');

        StakeholderProfile::create([
            'user_id' => $stakeholder->id,
            'pesantren_id' => $pesantren->id,
            'position' => 'Pengasuh Pesantren',
        ]);

        $this->command->info('   ✅ Stakeholder: ' . $stakeholder->email . ' / password');

        // ============================================
        // 5. CREATE USTADZ (3 ustadz)
        // ============================================
        $this->command->info('');
        $this->command->info('👥 Creating Ustadz...');

        $ustadzData = [
            ['name' => 'Ustadz Abdullah', 'email' => 'ustadz1@pesantrentahfidz.com', 'nip' => 'UST001', 'specialization' => 'Tahfidz Juz 1-10'],
            ['name' => 'Ustadz Muhammad', 'email' => 'ustadz2@pesantrentahfidz.com', 'nip' => 'UST002', 'specialization' => 'Tahfidz Juz 11-20'],
            ['name' => 'Ustadz Ahmad', 'email' => 'ustadz3@pesantrentahfidz.com', 'nip' => 'UST003', 'specialization' => 'Tahfidz Juz 21-30'],
        ];

        $ustadzProfiles = [];
        foreach ($ustadzData as $index => $data) {
            $user = User::create([
                'pesantren_id' => $pesantren->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => '0844444444' . $index,
                'password' => Hash::make('password'),
                'user_type' => 'ustadz',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $user->assignRole('Ustadz');

            $profile = UstadzProfile::create([
                'user_id' => $user->id,
                'pesantren_id' => $pesantren->id,
                'nip' => $data['nip'],
                'specialization' => $data['specialization'],
                'join_date' => now()->subMonths(rand(6, 24)),
            ]);

            $ustadzProfiles[] = $profile;
            $this->command->info('   ✅ Ustadz: ' . $user->email . ' / password');
        }

        // ============================================
        // 6. CREATE WALI (5 wali)
        // ============================================
        $this->command->info('');
        $this->command->info('👥 Creating Wali...');

        $waliData = [
            ['name' => 'Bapak Ahmad', 'email' => 'wali1@example.com', 'nik' => '3201010101010001', 'relation' => 'ayah'],
            ['name' => 'Bapak Budi', 'email' => 'wali2@example.com', 'nik' => '3201010101010002', 'relation' => 'ayah'],
            ['name' => 'Ibu Siti', 'email' => 'wali3@example.com', 'nik' => '3201010101010003', 'relation' => 'ibu'],
            ['name' => 'Bapak Hasan', 'email' => 'wali4@example.com', 'nik' => '3201010101010004', 'relation' => 'wali'],
            ['name' => 'Ibu Fatimah', 'email' => 'wali5@example.com', 'nik' => '3201010101010005', 'relation' => 'ibu'],
        ];

        $waliProfiles = [];
        foreach ($waliData as $index => $data) {
            $user = User::create([
                'pesantren_id' => $pesantren->id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => '0855555555' . $index,
                'password' => Hash::make('password'),
                'user_type' => 'wali',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $user->assignRole('Wali Santri');

            $profile = WaliProfile::create([
                'user_id' => $user->id,
                'pesantren_id' => $pesantren->id,
                'nik' => $data['nik'],
                'relation' => $data['relation'],
                'occupation' => 'Wiraswasta',
                'address' => 'Jakarta',
            ]);

            $waliProfiles[] = $profile;
            $this->command->info('   ✅ Wali: ' . $user->email . ' / password');
        }

        // ============================================
        // 7. CREATE SANTRI (10 santri)
        // ============================================
        $this->command->info('');
        $this->command->info('👥 Creating Santri...');

        $santriData = [
            ['name' => 'Muhammad Rizki', 'nis' => 'SNT001', 'gender' => 'L', 'birth_date' => '2010-05-15'],
            ['name' => 'Ahmad Fadli', 'nis' => 'SNT002', 'gender' => 'L', 'birth_date' => '2010-08-20'],
            ['name' => 'Fatimah Zahra', 'nis' => 'SNT003', 'gender' => 'P', 'birth_date' => '2011-03-10'],
            ['name' => 'Aisyah Nur', 'nis' => 'SNT004', 'gender' => 'P', 'birth_date' => '2011-06-25'],
            ['name' => 'Abdullah Hasan', 'nis' => 'SNT005', 'gender' => 'L', 'birth_date' => '2010-11-05'],
            ['name' => 'Umar Faruq', 'nis' => 'SNT006', 'gender' => 'L', 'birth_date' => '2011-01-18'],
            ['name' => 'Khadijah Amira', 'nis' => 'SNT007', 'gender' => 'P', 'birth_date' => '2010-09-22'],
            ['name' => 'Ali Imran', 'nis' => 'SNT008', 'gender' => 'L', 'birth_date' => '2011-04-30'],
            ['name' => 'Zainab Husna', 'nis' => 'SNT009', 'gender' => 'P', 'birth_date' => '2010-12-14'],
            ['name' => 'Yusuf Ibrahim', 'nis' => 'SNT010', 'gender' => 'L', 'birth_date' => '2011-02-08'],
        ];

        $santriProfiles = [];
        foreach ($santriData as $index => $data) {
            $user = User::create([
                'pesantren_id' => $pesantren->id,
                'name' => $data['name'],
                'email' => 'santri' . ($index + 1) . '@pesantrentahfidz.com',
                'phone' => '0866666666' . $index,
                'password' => Hash::make('password'),
                'user_type' => 'santri',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $user->assignRole('Santri');

            // Assign wali randomly
            $waliProfile = $waliProfiles[array_rand($waliProfiles)];

            $profile = SantriProfile::create([
                'user_id' => $user->id,
                'pesantren_id' => $pesantren->id,
                'wali_id' => $waliProfile->id,
                'nis' => $data['nis'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'address' => 'Jakarta',
                'entry_date' => now()->subMonths(rand(6, 12)),
                'total_juz_completed' => rand(0, 5),
                'total_ayat_completed' => rand(0, 1000),
            ]);

            $santriProfiles[] = $profile;
            $this->command->info('   ✅ Santri: ' . $user->email . ' / password');
        }

        // Update pesantren santri count
        $pesantren->update(['current_santri_count' => count($santriProfiles)]);

        // ============================================
        // 8. CREATE CLASSES
        // ============================================
        $this->command->info('');
        $this->command->info('🏫 Creating Classes...');

        $classes = [
            ['name' => 'Kelas Tahfidz A', 'code' => 'TAHFIDZ-A-2025', 'max_capacity' => 30],
            ['name' => 'Kelas Tahfidz B', 'code' => 'TAHFIDZ-B-2025', 'max_capacity' => 30],
        ];

        $kelasModels = [];
        foreach ($classes as $data) {
            $kelas = Classes::create([
                'pesantren_id' => $pesantren->id,
                'name' => $data['name'],
                'code' => $data['code'],
                'description' => 'Kelas untuk tahfidz Al-Quran',
                'status' => 'active',
                'max_capacity' => $data['max_capacity'],
                'current_student_count' => 0,
            ]);

            $kelasModels[] = $kelas;
            $this->command->info('   ✅ Class: ' . $kelas->name);
        }

        // ============================================
        // 9. ASSIGN USTADZ TO CLASSES
        // ============================================
        $this->command->info('');
        $this->command->info('🔗 Assigning Ustadz to Classes...');

        foreach ($kelasModels as $index => $kelas) {
            $ustadz = $ustadzProfiles[$index % count($ustadzProfiles)];
            $kelas->assignUstadz($ustadz);
            $this->command->info('   ✅ ' . $ustadz->name . ' → ' . $kelas->name);
        }

        // ============================================
        // 10. ENROLL SANTRI TO CLASSES
        // ============================================
        $this->command->info('');
        $this->command->info('🔗 Enrolling Santri to Classes...');

        foreach ($santriProfiles as $index => $santri) {
            $kelas = $kelasModels[$index % count($kelasModels)];
            $kelas->enrollSantri($santri);
            $this->command->info('   ✅ ' . $santri->name . ' → ' . $kelas->name);
        }

        // ============================================
        // 11. CREATE SAMPLE HAFALANS
        // ============================================
        $this->command->info('');
        $this->command->info('📖 Creating Sample Hafalans...');

        foreach ($santriProfiles as $santri) {
            $kelas = $santri->activeClasses->first();
            $ustadz = $kelas ? $kelas->activeUstadz->first() : null;

            if ($ustadz) {
                // Create 3-5 random hafalans per santri
                $count = rand(3, 5);
                for ($i = 0; $i < $count; $i++) {
                    Hafalan::create([
                        'pesantren_id' => $pesantren->id,
                        'class_id' => $kelas->id,
                        'user_id' => $santri->user_id,
                        'created_by_user_id' => $ustadz->user_id,
                        'surah_number' => rand(1, 114),
                        'ayat_start' => 1,
                        'ayat_end' => rand(5, 20),
                        'juz_number' => rand(1, 30),
                        'type' => $i % 2 == 0 ? 'setoran' : 'murajah',
                        'status' => $i < 2 ? 'verified' : 'pending',
                        'verified_by_ustadz_id' => $i < 2 ? $ustadz->id : null,
                        'verified_at' => $i < 2 ? now() : null,
                        'hafalan_date' => now()->subDays(rand(0, 30)),
                    ]);
                }
                $this->command->info('   ✅ Created ' . $count . ' hafalans for ' . $santri->name);
            }
        }

        // ============================================
        // 12. CREATE GENERAL USER (PRO)
        // ============================================
        $this->command->info('');
        $this->command->info('👤 Creating General User (PRO)...');

        $generalUser = User::create([
            'pesantren_id' => null,
            'name' => 'User Umum PRO',
            'email' => 'user@example.com',
            'phone' => '08777777777',
            'password' => Hash::make('password'),
            'user_type' => 'general',
            'status' => 'active',
            'is_pro' => true,
            'pro_expired_at' => now()->addYear(),
            'email_verified_at' => now(),
        ]);
        $generalUser->assignRole('General User');

        GeneralUserProfile::create([
            'user_id' => $generalUser->id,
            'total_juz_completed' => 2,
            'total_ayat_completed' => 500,
            'current_streak_days' => 7,
            'longest_streak_days' => 15,
            'last_hafalan_date' => now(),
        ]);

        $this->command->info('   ✅ General User: ' . $generalUser->email . ' / password (PRO)');

        // ============================================
        // SUMMARY
        // ============================================
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('✅ DEMO DATA SEEDING COMPLETED!');
        $this->command->info('═══════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('   • Pesantren: 1');
        $this->command->info('   • Super Admin: 1');
        $this->command->info('   • Admin Pesantren: 1');
        $this->command->info('   • Stakeholder: 1');
        $this->command->info('   • Ustadz: ' . count($ustadzProfiles));
        $this->command->info('   • Wali: ' . count($waliProfiles));
        $this->command->info('   • Santri: ' . count($santriProfiles));
        $this->command->info('   • Classes: ' . count($kelasModels));
        $this->command->info('   • General User: 1 (PRO)');
        $this->command->info('   • Sample Hafalans: ~40');
        $this->command->info('');
        $this->command->info('🔑 Login Credentials (all use password: password):');
        $this->command->info('   • superadmin@hifzhcare.com (Super Admin)');
        $this->command->info('   • admin@pesantrentahfidz.com (Admin Pesantren)');
        $this->command->info('   • ustadz1@pesantrentahfidz.com (Ustadz)');
        $this->command->info('   • santri1@pesantrentahfidz.com (Santri)');
        $this->command->info('   • wali1@example.com (Wali)');
        $this->command->info('   • user@example.com (General User PRO)');
        $this->command->info('');
    }
}
