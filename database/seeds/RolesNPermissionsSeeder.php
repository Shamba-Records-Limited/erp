<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesNPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles =  array(
            array( 'name'=>'admin', 'guard_name'=>'web','created_at'=>date('Y-m-d h:i:s')),
            array( 'name'=>'cooperative admin', 'guard_name'=>'web','created_at'=>date('Y-m-d h:i:s')),
            array( 'name'=>'farmer', 'guard_name'=>'web','created_at'=>date('Y-m-d h:i:s')),
            array( 'name'=>'vet', 'guard_name'=>'web','created_at'=>date('Y-m-d h:i:s')),
            array( 'name'=>'agent', 'guard_name'=>'web','created_at'=>date('Y-m-d h:i:s')),
            array( 'name'=>'accountant', 'guard_name'=>'web','created_at'=>date('Y-m-d h:i:s')),
            array( 'name'=>'employee', 'guard_name'=>'web','created_at'=>date('Y-m-d h:i:s')),
            array( 'name'=>'miller admin', 'guard_name'=>'web','created_at'=>date('Y-m-d h:i:s')),
            array( 'name'=>'miller warehouse admin', 'guard_name'=>'web','created_at'=>date('Y-m-d h:i:s')),
        );

        $permissions =  array(
            array( 'name'=>'manage system','guard_name'=>'web','created_at'=>date('Y-m-d h:i:s')),
        );

        DB::table('roles')->insert($roles);
        DB::table('permissions')->insert($permissions);
    }
}
