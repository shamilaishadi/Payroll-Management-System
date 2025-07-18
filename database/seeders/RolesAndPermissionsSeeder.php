<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Define permissions without spaces
        $permissions = [
            'view_attendance',
            'mark_attendance',
            'manage_users',
            'manage_roles',
            'add_new_user',
            'view_work_schedule',
            'manage_work_schedule',
            'manage_salary',
            'manage_leaves',
        ];

        // Add permissions if they don't already exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $hrm = Role::firstOrCreate(['name' => 'hr-manager']);
        $supervisor = Role::firstOrCreate(['name' => 'supervisor']);
        $regularUser = Role::firstOrCreate(['name' => 'regular-user']);
        $attendenceMaker = Role::firstOrCreate(['name' => 'attendance-maker']);

        // Get permissions
        $viewAttendance = Permission::firstOrCreate(['name' => 'view_attendance']);
        $markAttendance = Permission::firstOrCreate(['name' => 'mark_attendance']);
        $manageUsers = Permission::firstOrCreate(['name' => 'manage_users']);
        $manageRoles = Permission::firstOrCreate(['name' => 'manage_roles']);
        $addNewUser = Permission::firstOrCreate(['name' => 'add_new_user']);
        $viewWorkSchedule = Permission::firstOrCreate(['name' => 'view_work_schedule']);
        $manageWorkSchedule = Permission::firstOrCreate(['name' => 'manage_work_schedule']);
        $manageSalary = Permission::firstOrCreate(['name' => 'manage_salary']);
        $manageLeaves = Permission::firstOrCreate(['name' => 'manage_leaves']);


        // Assign permissions to roles
        $superAdmin->givePermissionTo(Permission::all());
        // $superAdmin->givePermissionTo([$viewAttendance, $markAttendance, $manageUsers, $manageRoles]);
        $hrm->givePermissionTo([$viewAttendance, $markAttendance, $addNewUser, $viewWorkSchedule, $manageWorkSchedule, $manageSalary, $manageLeaves]);
        // $supervisor->givePermissionTo([$viewAttendance]);
        $regularUser->givePermissionTo([$markAttendance]);
        $attendenceMaker->givePermissionTo([$markAttendance]);
    }
}
