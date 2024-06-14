<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class CooperativeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cooperatives')->insert([
            'id' => (string) Uuid::generate(4),
            'default_coop' => 1,
            'country_code' => "KE",
            'name' => 'Shamba Equity',
            'abbreviation' => 'ERP',
            'address' => "Nairobi",
            'location' => "Nairobi",
            'email'=> "erp@shambaequity.co.ke",
            'contact_details'=> "254716345621",
            'currency'=> "KSH",
            'created_at'=> date('Y-m-d h:i:s')
        ]);
    }
}
