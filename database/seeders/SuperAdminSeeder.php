<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Check if a super admin already exists
        if (User::where('email', env('SUPER_ADMIN_EMAIL'))->doesntExist()) {
            // Create a new super admin if not exists
            $superAdmin = User::create([
                'name' => 'Super Admin',
                'email' => env('SUPER_ADMIN_EMAIL'),
                'password' => Hash::make(env('SUPER_ADMIN_PASSWORD')),
            ]);

            // Assign the 'super-admin' role to the user
            $superAdmin->assignRole('super-admin');

            //assign 1st work schedule to super admin
            $workSchedule = \App\Models\WorkSchedule::first();
            if ($workSchedule) {
                $superAdmin->workSchedules()->attach($workSchedule->id);
            }
        }
    }
}
