<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;
use App\Cooperative;

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

        $cooperative = Cooperative::first();
        if (!$cooperative) {
            throw new \Exception("No cooperative found. Please ensure at least one cooperative exists.");
        }

        $existingUser = DB::table('users')->where('username', 'admin')->first();

        if (!$existingUser) {
            DB::table('users')->insert([
                'id' => (string) Uuid::generate(4),
                'first_name' => 'Admin',
                'other_names' => 'ERP',
                'username' => 'admin',
                'cooperative_id' => $cooperative->id,
                'email' => 'admin@erp.com',
                'password' => Hash::make(env('DEFAULT_PASSWORD', 'password')), // Fallback to 'password' if env variable is missing
                'created_at' => now(), // Use Laravel's helper for the current timestamp
            ]);

            $this->command->info("Admin user created successfully.");
        } else {
            $this->command->warn("Admin user already exists.");
        }


    }
}
