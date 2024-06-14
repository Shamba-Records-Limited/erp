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
        // $kenya = Country::where('name', 'Kenya')->first();

        DB::table('counties')->insert([
            [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Mombasa",
               "code" => 1,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Kwale",
                "code" => 2,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Kilifi",
                "code" => 3,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Tana River",
                "code" => 4,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Lamu",
                "code" => 5,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Taita-Taveta",
                "code" => 6,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Garissa",
                "code" => 7,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Wajir",
                "code" => 8,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Mandera",
                "code" => 9,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Marsabit",
                "code" => 10,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Isiolo",
                "code" => 11,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Meru",
                "code" => 12,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Tharaka-Nithi",
                "code" => 13,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Embu",
                "code" => 14,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Kitui",
                "code" => 15,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Machakos",
                "code" => 16,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Makueni",
                "code" => 17,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Nyandarua",
                "code" => 18,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Nyeri",
                "code" => 19,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Kirinyaga",
                "code" => 20,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Murang'a",
                "code" => 21,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Kiambu",
                "code" => 22,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Turkana",
                "code" => 23,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "West Pokot",
                "code" => 24,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Samburu",
                "code" => 25,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Trans-Nzoia",
                "code" => 26,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Uasin Gishu",
                "code" => 27,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Elgeyo-Marakwet",
                "code" => 28,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Nandi",
                "code" => 29,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Baringo",
                "code" => 30,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Laikipia",
                "code" => 31,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Nakuru",
                "code" => 32,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Narok",
                "code" => 33,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Kajiado",
                "code" => 34
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Kericho",
                "code" => 35,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Bomet",
                "code" => 36,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Kakamega",
                "code" => 37,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Vihiga",
                "code" => 38,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Bungoma",
                "code" => 39,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Busia",
                "code" => 40,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Siaya",
                "code" => 41,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Kisumu",
                "code" => 42,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Homa Bay",
                "code" => 43,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Migori",
                "code" => 44,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Kisii",
                "code" => 45,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Nyamira",
                "code" => 46,
            ], [
                "id" => (string) Uuid::generate(4),
                "country_code" => "KE",
                "name" => "Nairobi",
                "code" => 47,
            ]
        ]);
    }
}
