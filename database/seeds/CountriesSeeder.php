<?php


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Webpatser\Uuid\Uuid;

class CountriesSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFile = base_path('countries.json');
        
        // Read the file
        if (File::exists($jsonFile)) {
            $countriesJson = File::get($jsonFile);
            $countries = json_decode($countriesJson, true);
            
            foreach ($countries as $code => $country) {
                DB::table('countries')->insert([
                    'id' => (string) Uuid::generate(4),
                    'iso_code' => $code,
                    'name' => $country['name'],
                    'flag' => $country['flag'],
                    'dial_code' => $country['dial_code']
                ]);
            }
        } else {
            $this->command->info("The file {$jsonFile} does not exist.");
        }
    }
}
