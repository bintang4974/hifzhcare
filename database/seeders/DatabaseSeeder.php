<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->command->info('');
        $this->command->info('╔═══════════════════════════════════════════════════════════╗');
        $this->command->info('║          🚀 HIFZHCARE DATABASE SEEDING                    ║');
        $this->command->info('╚═══════════════════════════════════════════════════════════╝');
        $this->command->info('');

        // Order matters!
        $this->call([
            RolePermissionSeeder::class,  // Must run first
            DemoDataSeeder::class,         // Sample data for testing
        ]);

        $this->command->info('');
        $this->command->info('╔═══════════════════════════════════════════════════════════╗');
        $this->command->info('║          ✅ ALL SEEDERS COMPLETED SUCCESSFULLY!           ║');
        $this->command->info('╚═══════════════════════════════════════════════════════════╝');
        $this->command->info('');
        $this->command->info('🎯 Your application is ready to use!');
        $this->command->info('');
        $this->command->info('📝 Test Login:');
        $this->command->info('   URL: http://localhost:8000/login');
        $this->command->info('   Email: superadmin@hifzhcare.com');
        $this->command->info('   Password: password');
        $this->command->info('');
    }
}
