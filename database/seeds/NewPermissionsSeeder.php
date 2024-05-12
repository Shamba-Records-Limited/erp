<?php

use Illuminate\Database\Seeder;

class NewPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $permissions = [
            ['module' => 'user_management', 'sub_module' => 'user', 'action' => 'view'],
            ['module' => 'user_management', 'sub_module' => 'user', 'action' => 'add'],
            ['module' => 'user_management', 'sub_module' => 'user', 'action' => 'edit'],
            ['module' => 'user_management', 'sub_module' => 'user', 'action' => 'delete'],
            ['module' => 'user_management', 'sub_module' => 'role', 'action' => 'view'],
            ['module' => 'user_management', 'sub_module' => 'role', 'action' => 'add'],
            ['module' => 'user_management', 'sub_module' => 'role', 'action' => 'edit'],
            ['module' => 'user_management', 'sub_module' => 'role', 'action' => 'delete'],
            ['module' => 'user_management', 'sub_module' => 'role_permission', 'action' => 'assign'],
            ['module' => 'user_management', 'sub_module' => 'role_permission', 'action' => 'unassign'],
            ['module' => 'user_management', 'sub_module' => 'user_permission', 'action' => 'assign'],
            ['module' => 'user_management', 'sub_module' => 'user_permission', 'action' => 'unassign'],
            ['module' => 'user_management', 'sub_module' => 'coop_branch', 'action' => 'view'],
            ['module' => 'user_management', 'sub_module' => 'coop_branch', 'action' => 'add'],
            ['module' => 'user_management', 'sub_module' => 'coop_branch', 'action' => 'edit'],
            ['module' => 'user_management', 'sub_module' => 'coop_branch', 'action' => 'delete'],
        ];
    }
}
