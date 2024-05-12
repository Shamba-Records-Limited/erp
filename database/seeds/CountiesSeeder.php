<?php

use App\Country;
use Illuminate\Database\Seeder;

class CountiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $kenya = Country::where('name', 'Kenya')->first();

        DB::table('counties')->insert([
            [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Mombasa",
               "code" => 1,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Kwale",
                "code" => 2,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Kilifi",
                "code" => 3,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Tana River",
                "code" => 4,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Lamu",
                "code" => 5,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Taita-Taveta",
                "code" => 6,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Garissa",
                "code" => 7,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Wajir",
                "code" => 8,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Mandera",
                "code" => 9,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Marsabit",
                "code" => 10,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Isiolo",
                "code" => 11,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Meru",
                "code" => 12,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Tharaka-Nithi",
                "code" => 13,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Embu",
                "code" => 14,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Kitui",
                "code" => 15,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Machakos",
                "code" => 16,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Makueni",
                "code" => 17,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Nyandarua",
                "code" => 18,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Nyeri",
                "code" => 19,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Kirinyaga",
                "code" => 20,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Murang'a",
                "code" => 21,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Kiambu",
                "code" => 22,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Turkana",
                "code" => 23,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "West Pokot",
                "code" => 24,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Samburu",
                "code" => 25,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Trans-Nzoia",
                "code" => 26,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Uasin Gishu",
                "code" => 27,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Elgeyo-Marakwet",
                "code" => 28,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Nandi",
                "code" => 29,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Baringo",
                "code" => 30,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Laikipia",
                "code" => 31,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Nakuru",
                "code" => 32,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Narok",
                "code" => 33,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Kajiado",
                "code" => 34
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Kericho",
                "code" => 35,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Bomet",
                "code" => 36,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Kakamega",
                "code" => 37,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Vihiga",
                "code" => 38,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Bungoma",
                "code" => 39,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Busia",
                "code" => 40,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Siaya",
                "code" => 41,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Kisumu",
                "code" => 42,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Homa Bay",
                "code" => 43,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Migori",
                "code" => 44,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Kisii",
                "code" => 45,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Nyamira",
                "code" => 46,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_id" => $kenya->id,
                "name" => "Nairobi",
                "code" => 47,
            ]
        ]);
    }
}
