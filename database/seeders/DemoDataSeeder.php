<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
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
        // 11.5 CREATE KHATAM SANTRI (COMPLETE HAFALAN)
        // ============================================
        $this->command->info('');
        $this->command->info('🎓 Creating Santri with Khatam (Complete 30 Juz)...');

        $khatamSantriData = [
            ['name' => 'Muhammad Al-Hafidz', 'nis' => 'SNT011', 'gender' => 'L', 'birth_date' => '2009-03-15'],
            ['name' => 'Aisha Az-Zahra', 'nis' => 'SNT012', 'gender' => 'P', 'birth_date' => '2009-07-22'],
            ['name' => 'Abdulloh Hasyim', 'nis' => 'SNT013', 'gender' => 'L', 'birth_date' => '2008-11-10'],
        ];

        $khatamSantriProfiles = [];
        foreach ($khatamSantriData as $index => $data) {
            $user = User::create([
                'pesantren_id' => $pesantren->id,
                'name' => $data['name'],
                'email' => 'khatam' . ($index + 1) . '@pesantrentahfidz.com',
                'phone' => '0898888888' . $index,
                'password' => Hash::make('password'),
                'user_type' => 'santri',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $user->assignRole('Santri');

            // Assign to first wali
            $waliProfile = $waliProfiles[0];

            $profile = SantriProfile::create([
                'user_id' => $user->id,
                'pesantren_id' => $pesantren->id,
                'wali_id' => $waliProfile->id,
                'nis' => $data['nis'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'address' => 'Jakarta',
                'entry_date' => now()->subMonths(36), // Entered 3 years ago
                'total_juz_completed' => 30,
                'total_ayat_completed' => 6236, // Total ayat in Quran
            ]);

            $khatamSantriProfiles[] = $profile;
            $this->command->info('   ✅ Created: ' . $user->email);
        }

        // Enroll khatam santri to first class
        $khatamClass = $kelasModels[0];
        foreach ($khatamSantriProfiles as $khatamSantri) {
            $khatamClass->enrollSantri($khatamSantri);
            $this->command->info('   ✅ Enrolled: ' . $khatamSantri->user->name . ' → ' . $khatamClass->name);
        }

        // Create complete hafalan records (all 30 juz) for khatam santri
        $ustadzForKhatam = $ustadzProfiles[0];
        
        foreach ($khatamSantriProfiles as $khatamSantri) {
            $this->command->info('');
            $this->command->info('   📖 Creating 30 Juz Hafalan Records for ' . $khatamSantri->user->name . '...');
            
            // Array of Surahs grouped by Juz (simplified - just using start surah per juz)
            $juzSurahs = [
                1 => ['start' => 1, 'end' => 1],      // Al-Fatihah - Al-Baqarah (partial)
                2 => ['start' => 2, 'end' => 2],      // Al-Baqarah (cont)
                3 => ['start' => 3, 'end' => 3],      // Ali Imran (partial)
                4 => ['start' => 3, 'end' => 4],      // Ali Imran (cont) - An-Nisa (partial)
                5 => ['start' => 4, 'end' => 5],      // An-Nisa (cont) - Al-Ma'idah (partial)
                6 => ['start' => 5, 'end' => 6],      // Al-Ma'idah (cont) - Al-An'am (partial)
                7 => ['start' => 6, 'end' => 7],      // Al-An'am (cont) - Al-A'raf (partial)
                8 => ['start' => 7, 'end' => 8],      // Al-A'raf (cont) - Al-Anfal (partial)
                9 => ['start' => 8, 'end' => 9],      // Al-Anfal (cont) - At-Taubah (partial)
                10 => ['start' => 9, 'end' => 11],    // At-Taubah (cont) - Hud (partial)
                11 => ['start' => 11, 'end' => 12],   // Hud (cont) - Yusuf (partial)
                12 => ['start' => 12, 'end' => 13],   // Yusuf (cont) - Ar-Ra'd (partial)
                13 => ['start' => 13, 'end' => 14],   // Ar-Ra'd (cont) - Ibrahim (partial)
                14 => ['start' => 14, 'end' => 16],   // Ibrahim (cont) - An-Nahl (partial)
                15 => ['start' => 16, 'end' => 17],   // An-Nahl (cont) - Al-Isra (partial)
                16 => ['start' => 17, 'end' => 18],   // Al-Isra (cont) - Al-Kahf (partial)
                17 => ['start' => 18, 'end' => 21],   // Al-Kahf (cont) - Al-Anbiya (partial)
                18 => ['start' => 21, 'end' => 23],   // Al-Anbiya (cont) - Al-Mu'minun (partial)
                19 => ['start' => 23, 'end' => 25],   // Al-Mu'minun (cont) - Al-Furqan (partial)
                20 => ['start' => 25, 'end' => 27],   // Al-Furqan (cont) - An-Naml (partial)
                21 => ['start' => 27, 'end' => 29],   // An-Naml (cont) - Al-Ankabut (partial)
                22 => ['start' => 29, 'end' => 33],   // Al-Ankabut (cont) - Al-Ahzab (partial)
                23 => ['start' => 33, 'end' => 36],   // Al-Ahzab (cont) - Ya-Sin (partial)
                24 => ['start' => 36, 'end' => 39],   // Ya-Sin (cont) - Az-Zumar (partial)
                25 => ['start' => 39, 'end' => 41],   // Az-Zumar (cont) - Fussilat (partial)
                26 => ['start' => 41, 'end' => 46],   // Fussilat (cont) - Al-Ahqaf (partial)
                27 => ['start' => 46, 'end' => 51],   // Al-Ahqaf (cont) - Az-Zariyat (partial)
                28 => ['start' => 51, 'end' => 57],   // Az-Zariyat (cont) - Al-Hadid (partial)
                29 => ['start' => 57, 'end' => 66],   // Al-Hadid (cont) - At-Tahrim (partial)
                30 => ['start' => 66, 'end' => 114],  // At-Tahrim (cont) - An-Nas
            ];
            
            for ($juzNum = 1; $juzNum <= 30; $juzNum++) {
                $surahInfo = $juzSurahs[$juzNum];
                
                Hafalan::create([
                    'pesantren_id' => $pesantren->id,
                    'class_id' => $khatamClass->id,
                    'user_id' => $khatamSantri->user_id,
                    'created_by_user_id' => $ustadzForKhatam->user_id,
                    'surah_number' => $surahInfo['start'],
                    'ayat_start' => 1,
                    'ayat_end' => rand(100, 200), // Variable ayat per surah
                    'juz_number' => $juzNum,
                    'type' => 'setoran',
                    'status' => 'verified',
                    'verified_by_ustadz_id' => $ustadzForKhatam->id,
                    'verified_at' => now()->subMonths(36 - ceil($juzNum / 10)), // Spread over 3 years
                    'hafalan_date' => now()->subMonths(36 - ceil($juzNum / 10)),
                ]);
            }
            
            $this->command->info('   ✅ Created 30 Juz Hafalan Records for ' . $khatamSantri->user->name);
        }

        // ============================================
        // 11.7 CREATE CERTIFICATES FOR KHATAM SANTRI
        // ============================================
        $this->command->info('');
        $this->command->info('🏆 Creating Certificates for Khatam Santri...');

        // First, create a certificate template
        $certificateTemplate = CertificateTemplate::create([
            'pesantren_id' => $pesantren->id,
            'name' => 'Template Sertifikat Khatam Al-Quran',
            'type' => 'santri_juz',
            'file_path' => 'certificates/template_khatam.pdf',
            'placeholders_json' => [
                'name' => '{santri_name}',
                'date' => '{certificate_date}',
                'number' => '{certificate_number}',
                'juz' => '{juz_completed}',
            ],
            'status' => 'active',
        ]);
        
        $this->command->info('   ✅ Certificate Template Created: ' . $certificateTemplate->name);

        // Now create certificates for each khatam santri
        foreach ($khatamSantriProfiles as $index => $khatamSantri) {
            $certificate = Certificate::create([
                'pesantren_id' => $pesantren->id,
                'certificate_template_id' => $certificateTemplate->id,
                'user_id' => $khatamSantri->user_id,
                'certificate_number' => 'CERT-' . date('Y') . '-' . str_pad($khatamSantri->id, 4, '0', STR_PAD_LEFT),
                'type' => 'santri_juz',
                'juz_completed' => 30,
                'metadata_json' => [
                    'completion_date' => now()->subMonths(rand(1, 6))->format('Y-m-d'),
                    'total_days' => rand(300, 1000),
                    'notes' => 'Hafalan lengkap 30 Juz dengan verifikasi ustadz',
                ],
                'status' => 'issued',
                'approved_by_ustadz_id' => $ustadzForKhatam->id,
                'approved_at' => now()->subMonths(rand(1, 6)),
                'issued_at' => now()->subMonths(rand(1, 6)),
            ]);
            
            $this->command->info('   ✅ Certificate Created: ' . $khatamSantri->user->name . ' (Cert #' . $certificate->certificate_number . ')');
        }

        // ============================================
        // 12. CREATE SANTRI WITH PER-JUZ COMPLETION
        // ============================================
        $this->command->info('');
        $this->command->info('🎓 Creating Santri with Per-Juz Completion...');

        $perJuzSantriData = [
            ['name' => 'Badriyah Siti', 'nis' => 'SNT014', 'birth_date' => '2010-08-15', 'gender' => 'P', 'juz' => 5],
            ['name' => 'Ridho Prasetya', 'nis' => 'SNT015', 'birth_date' => '2009-12-10', 'gender' => 'L', 'juz' => 10],
            ['name' => 'Nur Azizah', 'nis' => 'SNT016', 'birth_date' => '2010-05-20', 'gender' => 'P', 'juz' => 15],
            ['name' => 'Irfan Maulana', 'nis' => 'SNT017', 'birth_date' => '2009-09-08', 'gender' => 'L', 'juz' => 20],
        ];

        $perJuzSantriProfiles = [];
        foreach ($perJuzSantriData as $index => $data) {
            $user = User::create([
                'pesantren_id' => $pesantren->id,
                'name' => $data['name'],
                'email' => 'perjuz' . ($index + 1) . '@pesantrentahfidz.com',
                'phone' => '0899999999' . $index,
                'password' => Hash::make('password'),
                'user_type' => 'santri',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
            $user->assignRole('Santri');

            // Assign to first wali
            $waliProfile = $waliProfiles[0];

            $profile = SantriProfile::create([
                'user_id' => $user->id,
                'pesantren_id' => $pesantren->id,
                'wali_id' => $waliProfile->id,
                'nis' => $data['nis'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'address' => 'Jakarta',
                'entry_date' => now()->subMonths(24), // Entered 2 years ago
                'total_juz_completed' => $data['juz'],
                'total_ayat_completed' => $data['juz'] * 200, // Approximate
            ]);

            $perJuzSantriProfiles[] = [
                'profile' => $profile,
                'juz' => $data['juz']
            ];
            $this->command->info('   ✅ Created: ' . $user->email . ' (Juz ' . $data['juz'] . ')');
        }

        // Enroll per-juz santri to second class
        $perJuzClass = $kelasModels[1] ?? $kelasModels[0];
        foreach ($perJuzSantriProfiles as $item) {
            $perJuzClass->enrollSantri($item['profile']);
            $this->command->info('   ✅ Enrolled: ' . $item['profile']->user->name . ' → ' . $perJuzClass->name);
        }

        // Create hafalan records for per-juz santri
        $ustadzForPerJuz = $ustadzProfiles[1] ?? $ustadzProfiles[0];
        
        foreach ($perJuzSantriProfiles as $item) {
            $santri = $item['profile'];
            $juzCompleted = $item['juz'];
            
            $this->command->info('');
            $this->command->info('   📖 Creating ' . $juzCompleted . ' Juz Hafalan Records for ' . $santri->user->name . '...');
            
            // Create hafalan records
            for ($juzNum = 1; $juzNum <= $juzCompleted; $juzNum++) {
                Hafalan::create([
                    'pesantren_id' => $pesantren->id,
                    'class_id' => $perJuzClass->id,
                    'user_id' => $santri->user_id,
                    'created_by_user_id' => $ustadzForPerJuz->user_id,
                    'surah_number' => $juzNum,
                    'ayat_start' => 1,
                    'ayat_end' => rand(100, 200),
                    'juz_number' => $juzNum,
                    'type' => 'setoran',
                    'status' => 'verified',
                    'verified_by_ustadz_id' => $ustadzForPerJuz->id,
                    'verified_at' => now()->subMonths(24 - ceil($juzNum / 5)),
                    'hafalan_date' => now()->subMonths(24 - ceil($juzNum / 5)),
                ]);
            }
            
            $this->command->info('   ✅ Created ' . $juzCompleted . ' Juz Hafalan Records for ' . $santri->user->name);
        }

        // Create certificates for per-juz santri
        $this->command->info('');
        $this->command->info('🏆 Creating Certificates for Per-Juz Santri...');

        foreach ($perJuzSantriProfiles as $item) {
            $santri = $item['profile'];
            $juzCompleted = $item['juz'];
            
            $certificate = Certificate::create([
                'pesantren_id' => $pesantren->id,
                'certificate_template_id' => $certificateTemplate->id,
                'user_id' => $santri->user_id,
                'certificate_number' => 'CERT-' . date('Y') . '-' . str_pad($santri->id + 100, 4, '0', STR_PAD_LEFT),
                'type' => 'santri_juz',
                'juz_completed' => $juzCompleted,
                'metadata_json' => [
                    'completion_date' => now()->subMonths(rand(1, 6))->format('Y-m-d'),
                    'total_days' => rand(60, 400),
                    'notes' => 'Hafalan ' . $juzCompleted . ' Juz Al-Quran dengan verifikasi ustadz',
                ],
                'status' => 'issued',
                'approved_by_ustadz_id' => $ustadzForPerJuz->id,
                'approved_at' => now()->subMonths(rand(1, 6)),
                'issued_at' => now()->subMonths(rand(1, 6)),
            ]);
            
            $this->command->info('   ✅ Certificate Created: ' . $santri->user->name . ' (' . $juzCompleted . ' Juz) - Cert #' . $certificate->certificate_number);
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
        $this->command->info('   • Regular Santri: ' . count($santriProfiles));
        $this->command->info('   • Khatam Santri (30 Juz Complete): ' . count($khatamSantriProfiles) . ' 🎓');
        $this->command->info('   • Per-Juz Santri: ' . count($perJuzSantriProfiles) . ' 📖');
        $this->command->info('   • Classes: ' . count($kelasModels));
        $this->command->info('   • General User: 1 (PRO)');
        $this->command->info('   • Sample Hafalans: ~' . (count($santriProfiles) * 4 + 90 + (count($perJuzSantriProfiles) * 10)) . ' records');
        $this->command->info('   • Certificate Templates: 1 (Khatam/Per-Juz)');
        $this->command->info('   • Certificates: ' . (count($khatamSantriProfiles) + count($perJuzSantriProfiles)) . ' Total (Khatam + Per-Juz) 🏆');
        $this->command->info('');
        $this->command->info('🎓 Khatam Santri (Ready for Certificate Testing):');
        foreach ($khatamSantriProfiles as $khatamSantri) {
            $this->command->info('   ✓ ' . $khatamSantri->user->name . ' (' . $khatamSantri->user->email . ')');
        }
        $this->command->info('');
        $this->command->info('📖 Per-Juz Santri (Ready for Certificate Testing):');
        foreach ($perJuzSantriProfiles as $item) {
            $this->command->info('   ✓ ' . $item['profile']->user->name . ' (' . $item['profile']->user->email . ') - ' . $item['juz'] . ' Juz');
        }
        $this->command->info('');
        $this->command->info('🔑 Login Credentials (all use password: password):');
        $this->command->info('   • superadmin@hifzhcare.com (Super Admin)');
        $this->command->info('   • admin@pesantrentahfidz.com (Admin Pesantren)');
        $this->command->info('   • ustadz1@pesantrentahfidz.com (Ustadz)');
        $this->command->info('   • santri1@pesantrentahfidz.com (Santri)');
        $this->command->info('   • khatam1@pesantrentahfidz.com (Khatam 30 Juz - with certificate) 🎓');
        $this->command->info('   • perjuz1@pesantrentahfidz.com (Per-Juz 5 - with certificate) 📖');
        $this->command->info('   • perjuz2@pesantrentahfidz.com (Per-Juz 10 - with certificate) 📖');
        $this->command->info('   • perjuz3@pesantrentahfidz.com (Per-Juz 15 - with certificate) 📖');
        $this->command->info('   • perjuz4@pesantrentahfidz.com (Per-Juz 20 - with certificate) 📖');
        $this->command->info('   • wali1@example.com (Wali)');
        $this->command->info('   • user@example.com (General User PRO)');
        $this->command->info('');
        $this->command->info('💡 Testing Tips:');
        $this->command->info('   • Login as admin@pesantrentahfidz.com to manage all certificates');
        $this->command->info('   • Test 30 Juz (Khatam) certificates with khatam1@pesantrentahfidz.com');
        $this->command->info('   • Test Per-Juz certificates with perjuz1-4@pesantrentahfidz.com:');
        $this->command->info('     - perjuz1@pesantrentahfidz.com: 5 Juz');
        $this->command->info('     - perjuz2@pesantrentahfidz.com: 10 Juz');
        $this->command->info('     - perjuz3@pesantrentahfidz.com: 15 Juz');
        $this->command->info('     - perjuz4@pesantrentahfidz.com: 20 Juz');
        $this->command->info('   • Filter certificates by type (30 Juz vs Per Juz) in admin panel');
        $this->command->info('');
    }
}
