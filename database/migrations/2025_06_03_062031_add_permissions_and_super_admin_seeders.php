<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class AddPermissionsAndSuperAdminSeeders extends Migration
{
    public function up()
    {
        // Run specific seeders using Artisan::call()
        Artisan::call('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);
        Artisan::call('db:seed', ['--class' => 'SuperAdminSeeder']);
    }

    public function down()
    {
        // Revert actions performed in the up() method if necessary
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('users')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('password_resets')->truncate();
    }
}