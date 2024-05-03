<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => (string) Uuid::generate(4),
            'first_name' => 'Admin',
            'other_names' => 'ERP',
            'username' => 'admin',
            'cooperative_id' => \App\Cooperative::first()->id,
            'email' => 'admin@erp.com',
            'password' => bcrypt(env('DEFAULT_PASSWORD')),
            'created_at'=> date('Y-m-d h:i:s')
        ]);
    }
}
