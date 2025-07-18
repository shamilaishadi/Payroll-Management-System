<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder; // Correct import
use Database\Seeders\SuperAdminSeeder; // Correct import
use Database\Seeders\DefaultAttendanceScheduleSeeder; // Correct import

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the seeders to populate the database
        $this->call([
            DefaultAttendanceScheduleSeeder::class,
            RolesAndPermissionsSeeder::class, 
            SuperAdminSeeder::class, 
            
        ]);
    }
}
