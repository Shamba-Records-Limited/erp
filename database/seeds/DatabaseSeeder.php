<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
   //   $this->call(RolesNPermissionsSeeder::class);
   //    $this->call(CooperativeSeeder::class);
        $this->call(UserSeeder::class);

    }
}
