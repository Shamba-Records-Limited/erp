<?php

use App\County;
use Illuminate\Database\Seeder;

class SubCountiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $county_1 = County::where("code", 1)->first();
        $county_2 = County::where("code", 2)->first();
        $county_3 = County::where("code", 3)->first();
        $county_4 = County::where("code", 4)->first();
        $county_5 = County::where("code", 5)->first();
        $county_6 = County::where("code", 6)->first();
        $county_7 = County::where("code", 7)->first();
        $county_8 = County::where("code", 8)->first();
        $county_9 = County::where("code", 9)->first();
        $county_10 = County::where("code", 10)->first();
        $county_11 = County::where("code", 11)->first();
        $county_12 = County::where("code", 12)->first();
        $county_13 = County::where("code", 13)->first();
        $county_14 = County::where("code", 14)->first();
        $county_15 = County::where("code", 15)->first();
        $county_16 = County::where("code", 16)->first();
        $county_17 = County::where("code", 17)->first();
        $county_18 = County::where("code", 18)->first();
        $county_19 = County::where("code", 19)->first();
        $county_20 = County::where("code", 20)->first();
        $county_21 = County::where("code", 21)->first();
        $county_22 = County::where("code", 22)->first();
        $county_23 = County::where("code", 23)->first();
        $county_24 = County::where("code", 24)->first();
        $county_25 = County::where("code", 25)->first();
        $county_26 = County::where("code", 26)->first();
        $county_27 = County::where("code", 27)->first();
        $county_28 = County::where("code", 28)->first();
        $county_29 = County::where("code", 29)->first();
        $county_30 = County::where("code", 30)->first();
        $county_31 = County::where("code", 31)->first();
        $county_32 = County::where("code", 32)->first();
        $county_33 = County::where("code", 33)->first();
        $county_34 = County::where("code", 34)->first();
        $county_35 = County::where("code", 35)->first();
        $county_36 = County::where("code", 36)->first();
        $county_37 = County::where("code", 37)->first();
        $county_38 = County::where("code", 38)->first();
        $county_39 = County::where("code", 39)->first();
        $county_40 = County::where("code", 40)->first();
        $county_41 = County::where("code", 41)->first();
        $county_42 = County::where("code", 42)->first();
        $county_43 = County::where("code", 43)->first();
        $county_44 = County::where("code", 44)->first();
        $county_45 = County::where("code", 45)->first();
        $county_46 = County::where("code", 46)->first();
        $county_47 = County::where("code", 47)->first();

        //
        DB::table('sub_counties')->insert([
            [
                "county_id" => $county_30->id,
                "name" => "Baringo central"
            ],
            [
                "county_id" => $county_30->id,
                "name" => "Baringo north"
            ],
            [
                "county_id" => $county_30->id,
                "name" => "Baringo south"
            ],
            [
                "county_id" => $county_30->id,
                "name" => "Eldama ravine"
            ],
            [
                "county_id" => $county_30->id,
                "name" => "Mogotio"
            ],
            [
                "county_id" => $county_30->id,
                "name" => "Tiaty"
            ],
            [
                "county_id" => $county_36->id,
                "name" => "Bomet central"
            ],
            [
                "county_id" => $county_36->id,
                "name" => "Bomet east"
            ],
            [
                "county_id" => $county_36->id,
                "name" => "Chepalungu"
            ],
            [
                "county_id" => $county_36->id,
                "name" => "Konoin"
            ],
            [
                "county_id" => $county_36->id,
                "name" => "Sotik"
            ],
            [
                "county_id" => $county_39->id,
                "name" => "Bumula"
            ],
            [
                "county_id" => $county_39->id,
                "name" => "Kabuchai"
            ],
            [
                "county_id" => $county_39->id,
                "name" => "Kanduyi"
            ],
            [
                "county_id" => $county_39->id,
                "name" => "Kimilil"
            ],
            [
                "county_id" => $county_39->id,
                "name" => "Mt Elgon"
            ],
            [
                "county_id" => $county_39->id,
                "name" => "Sirisia"
            ],
            [
                "county_id" => $county_39->id,
                "name" => "Tongaren"
            ],
            [
                "county_id" => $county_39->id,
                "name" => "Webuye east"
            ],
            [
                "county_id" => $county_39->id,
                "name" => "Webuye west"
            ],
            [
                "county_id" => $county_40->id,
                "name" => "Budalangi"
            ],
            [
                "county_id" => $county_40->id,
                "name" => "Butula"
            ],
            [
                "county_id" => $county_40->id,
                "name" => "Funyula"
            ],
            [
                "county_id" => $county_40->id,
                "name" => "Nambele"
            ],
            [
                "county_id" => $county_40->id,
                "name" => "Teso North"
            ],
            [
                "county_id" => $county_40->id,
                "name" => "Teso South"
            ],
            [
                "county_id" => $county_28->id,
                "name" => "Keiyo north"
            ],
            [
                "county_id" => $county_28->id,
                "name" => "Keiyo south"
            ],
            [
                "county_id" => $county_28->id,
                "name" => "Marakwet east"
            ],
            [
                "county_id" => $county_28->id,
                "name" => "Marakwet west"
            ],
            [
                "county_id" => $county_14->id,
                "name" => "Manyatta"
            ],
            [
                "county_id" => $county_14->id,
                "name" => "Mbeere north"
            ],
            [
                "county_id" => $county_14->id,
                "name" => "Mbeere south"
            ],
            [
                "county_id" => $county_14->id,
                "name" => "Runyenjes"
            ],
            [
                "county_id" => $county_7->id,
                "name" => "Daadab"
            ],
            [
                "county_id" => $county_7->id,
                "name" => "Fafi"
            ],
            [
                "county_id" => $county_7->id,
                "name" => "Garissa"
            ],
            [
                "county_id" => $county_7->id,
                "name" => "Hulugho"
            ],
            [
                "county_id" => $county_7->id,
                "name" => "Ijara"
            ],
            [
                "county_id" => $county_7->id,
                "name" => "Lagdera balambala"
            ],
            [
                "county_id" => $county_43->id,
                "name" => "Homabay town"
            ],
            [
                "county_id" => $county_43->id,
                "name" => "Kabondo"
            ],
            [
                "county_id" => $county_43->id,
                "name" => "Karachwonyo"
            ],
            [
                "county_id" => $county_43->id,
                "name" => "Kasipul"
            ],
            [
                "county_id" => $county_43->id,
                "name" => "Mbita"
            ],
            [
                "county_id" => $county_43->id,
                "name" => "Ndhiwa"
            ],
            [
                "county_id" => $county_43->id,
                "name" => "Rangwe"
            ],
            [
                "county_id" => $county_43->id,
                "name" => "Suba"
            ],
            [
                "county_id" => $county_11->id,
                "name" => "Isiolo"
            ],
            [
                "county_id" => $county_11->id,
                "name" => "Garba tula"
            ],
            [
                "county_id" => $county_11->id,
                "name" => "Merit"
            ],
            [
                "county_id" => $county_34->id,
                "name" => "Isinya"
            ],
            [
                "county_id" => $county_34->id,
                "name" => "Kajiado Central"
            ],
            [
                "county_id" => $county_34->id,
                "name" => "Kajiado North"
            ],
            [
                "county_id" => $county_34->id,
                "name" => "Loitokitok"
            ],
            [
                "county_id" => $county_34->id,
                "name" => "Mashuuru"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Butere"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Kakamega central"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Kakamega east"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Kakamega north"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Kakamega south"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Khwisero"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Lugari"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Lukuyani"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Lurambi"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Matete"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Mumias"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Mutungu"
            ],
            [
                "county_id" => $county_37->id,
                "name" => "Navakholo"
            ],
            [
                "county_id" => $county_35->id,
                "name" => "Ainamoi"
            ],
            [
                "county_id" => $county_35->id,
                "name" => "Belgut"
            ],
            [
                "county_id" => $county_35->id,
                "name" => "Bureti"
            ],
            [
                "county_id" => $county_35->id,
                "name" => "Kipkelion east"
            ],
            [
                "county_id" => $county_35->id,
                "name" => "Kipkelion west"
            ],
            [
                "county_id" => $county_35->id,
                "name" => "Soin sigowet"
            ],
            [
                "county_id" => $county_22->id,
                "name" => "Gatundu north"
            ],
            [
                "county_id" => $county_22->id,
                "name" => "Gatundu south"
            ],
            [
                "county_id" => $county_22->id,
                "name" => "Githunguri"
            ],
            [
                "county_id" => $county_22->id,
                "name" => "Juja"
            ],
            [
                "county_id" => $county_22->id,
                "name" => "Kabete"
            ],
            [
                "county_id" => $county_22->id,
                "name" => "Kiambaa"
            ],
            [
                "county_id" => $county_22->id,
                "name" => "Kiambu"
            ],
            [
                "county_id" => $county_22->id,
                "name" => "Kikuyu"
            ],
            [
                "county_id" => $county_22->id,
                "name" => "Limuru"
            ],
            [
                "county_id" => $county_22->id,
                "name" => "Ruiru"
            ],
            [
                "county_id" => $county_22->id,
                "name" => "Thika town"
            ],
            [
                "county_id" => $county_22->id,
                "name" => "lari"
            ],
            [
                "county_id" => $county_3->id,
                "name" => "Genzw"
            ],
            [
                "county_id" => $county_3->id,
                "name" => "Kaloleni"
            ],
            [
                "county_id" => $county_3->id,
                "name" => "Kilifi north"
            ],
            [
                "county_id" => $county_3->id,
                "name" => "Kilifi south"
            ],
            [
                "county_id" => $county_3->id,
                "name" => "Magarini"
            ],
            [
                "county_id" => $county_3->id,
                "name" => "Malindi"
            ],
            [
                "county_id" => $county_3->id,
                "name" => "Rabai"
            ],
            [
                "county_id" => $county_20->id,
                "name" => "Kirinyaga central"
            ],
            [
                "county_id" => $county_20->id,
                "name" => "Kirinyaga east"
            ],
            [
                "county_id" => $county_20->id,
                "name" => "Kirinyaga west"
            ],
            [
                "county_id" => $county_20->id,
                "name" => "Mwea east"
            ],
            [
                "county_id" => $county_20->id,
                "name" => "Mwea west"
            ],
            [
                "county_id" => $county_45->id,
                "name" => "Kisii"
            ],
            [
                "county_id" => $county_42->id,
                "name" => "Kisumu central"
            ],
            [
                "county_id" => $county_42->id,
                "name" => "Kisumu east "
            ],
            [
                "county_id" => $county_42->id,
                "name" => "Kisumu west"
            ],
            [
                "county_id" => $county_42->id,
                "name" => "Mohoroni"
            ],
            [
                "county_id" => $county_42->id,
                "name" => "Nyakach"
            ],
            [
                "county_id" => $county_42->id,
                "name" => "Nyando"
            ],
            [
                "county_id" => $county_42->id,
                "name" => "Seme"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Ikutha"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Katulani"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Kisasi"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Kitui central"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Kitui west "
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Lower yatta"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Matiyani"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Migwani"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Mutitu"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Mutomo"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Muumonikyusu"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Mwingi central"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Mwingi east"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Nzambani"
            ],
            [
                "county_id" => $county_15->id,
                "name" => "Tseikuru"
            ],
            [
                "county_id" => $county_2->id,
                "name" => "Kinango"
            ],
            [
                "county_id" => $county_2->id,
                "name" => "Lungalunga"
            ],
            [
                "county_id" => $county_2->id,
                "name" => "Msambweni"
            ],
            [
                "county_id" => $county_2->id,
                "name" => "Mutuga"
            ],
            [
                "county_id" => $county_31->id,
                "name" => "Laikipia east"
            ],
            [
                "county_id" => $county_31->id,
                "name" => "Laikipia north"
            ],
            [
                "county_id" => $county_31->id,
                "name" => "Laikipia central"
            ],
            [
                "county_id" => $county_31->id,
                "name" => "Laikipia west "
            ],
            [
                "county_id" => $county_31->id,
                "name" => "Nyahururu"
            ],
            [
                "county_id" => $county_5->id,
                "name" => "Lamu East"
            ],
            [
                "county_id" => $county_5->id,
                "name" => "Lamu West"
            ],
            [
                "county_id" => $county_16->id,
                "name" => "Kathiani"
            ],
            [
                "county_id" => $county_16->id,
                "name" => "Machakos town"
            ],
            [
                "county_id" => $county_16->id,
                "name" => "Masinga"
            ],
            [
                "county_id" => $county_16->id,
                "name" => "Matungulu"
            ],
            [
                "county_id" => $county_16->id,
                "name" => "Mavoko"
            ],
            [
                "county_id" => $county_16->id,
                "name" => "Mwala"
            ],
            [
                "county_id" => $county_16->id,
                "name" => "Yatta"
            ],
            [
                "county_id" => $county_17->id,
                "name" => "Kaiti"
            ],
            [
                "county_id" => $county_17->id,
                "name" => "Kibwei west"
            ],
            [
                "county_id" => $county_17->id,
                "name" => "Kibwezi east"
            ],
            [
                "county_id" => $county_17->id,
                "name" => "Kilome"
            ],
            [
                "county_id" => $county_17->id,
                "name" => "Makueni"
            ],
            [
                "county_id" => $county_17->id,
                "name" => "Mbooni"
            ],
            [
                "county_id" => $county_9->id,
                "name" => "Banissa"
            ],
            [
                "county_id" => $county_9->id,
                "name" => "Lafey"
            ],
            [
                "county_id" => $county_9->id,
                "name" => "Mandera East"
            ],
            [
                "county_id" => $county_9->id,
                "name" => "Mandera North"
            ],
            [
                "county_id" => $county_9->id,
                "name" => "Mandera South"
            ],
            [
                "county_id" => $county_9->id,
                "name" => "Mandera West"
            ],
            [
                "county_id" => $county_10->id,
                "name" => "Laisamis"
            ],
            [
                "county_id" => $county_10->id,
                "name" => "Moyale"
            ],
            [
                "county_id" => $county_10->id,
                "name" => "North hor"
            ],
            [
                "county_id" => $county_10->id,
                "name" => "Saku"
            ],
            [
                "county_id" => $county_12->id,
                "name" => "Igembe central"
            ],
            [
                "county_id" => $county_12->id,
                "name" => "Igembe north"
            ],
            [
                "county_id" => $county_12->id,
                "name" => "Buuri"
            ],
            [
                "county_id" => $county_12->id,
                "name" => "Igembe south"
            ],
            [
                "county_id" => $county_12->id,
                "name" => "Imenti central"
            ],
            [
                "county_id" => $county_12->id,
                "name" => "Imenti north"
            ],
            [
                "county_id" => $county_12->id,
                "name" => "Imenti south"
            ],
            [
                "county_id" => $county_12->id,
                "name" => "Tigania east"
            ],
            [
                "county_id" => $county_12->id,
                "name" => "Tigania west"
            ],
            [
                "county_id" => $county_44->id,
                "name" => "Awendo"
            ],
            [
                "county_id" => $county_44->id,
                "name" => "Kuria east"
            ],
            [
                "county_id" => $county_44->id,
                "name" => "Kuria west"
            ],
            [
                "county_id" => $county_44->id,
                "name" => "Mabera"
            ],
            [
                "county_id" => $county_44->id,
                "name" => "Ntimaru"
            ],
            [
                "county_id" => $county_44->id,
                "name" => "Rongo"
            ],
            [
                "county_id" => $county_44->id,
                "name" => "Suna east"
            ],
            [
                "county_id" => $county_44->id,
                "name" => "Suna west"
            ],
            [
                "county_id" => $county_44->id,
                "name" => "Uriri"
            ],
            [
                "county_id" => $county_1->id,
                "name" => "Changamwe"
            ],
            [
                "county_id" => $county_1->id,
                "name" => "Jomvu"
            ],
            [
                "county_id" => $county_1->id,
                "name" => "Kisauni"
            ],
            [
                "county_id" => $county_1->id,
                "name" => "Likoni"
            ],
            [
                "county_id" => $county_1->id,
                "name" => "Mvita"
            ],
            [
                "county_id" => $county_1->id,
                "name" => "Nyali"
            ],
            [
                "county_id" => $county_21->id,
                "name" => "Gatanga"
            ],
            [
                "county_id" => $county_21->id,
                "name" => "Kahuro"
            ],
            [
                "county_id" => $county_21->id,
                "name" => "Kandara"
            ],
            [
                "county_id" => $county_21->id,
                "name" => "Kangema"
            ],
            [
                "county_id" => $county_21->id,
                "name" => "Kigumo"
            ],
            [
                "county_id" => $county_21->id,
                "name" => "Kiharu"
            ],
            [
                "county_id" => $county_21->id,
                "name" => "Mathioya"
            ],
            [
                "county_id" => $county_21->id,
                "name" => "Murang’a south"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Dagoretti North Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Dagoretti South Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Embakasi Central Sub Count"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Embakasi East Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Embakasi North Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Embakasi South Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Embakasi West Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Kamukunji Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Kasarani Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Kibra Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Lang'ata Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Makadara Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Mathare Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Roysambu Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Ruaraka Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Starehe Sub County"
            ],
            [
                "county_id" => $county_47->id,
                "name" => "Westlands Sub County"
            ],
            [
                "county_id" => $county_32->id,
                "name" => "Bahati"
            ],
            [
                "county_id" => $county_32->id,
                "name" => "Gilgil"
            ],
            [
                "county_id" => $county_32->id,
                "name" => "Kuresoi north"
            ],
            [
                "county_id" => $county_32->id,
                "name" => "Kuresoi south"
            ],
            [
                "county_id" => $county_32->id,
                "name" => "Molo"
            ],
            [
                "county_id" => $county_32->id,
                "name" => "Naivasha"
            ],
            [
                "county_id" => $county_32->id,
                "name" => "Nakuru town east"
            ],
            [
                "county_id" => $county_32->id,
                "name" => "Nakuru town west"
            ],
            [
                "county_id" => $county_32->id,
                "name" => "Njoro"
            ],
            [
                "county_id" => $county_32->id,
                "name" => "Rongai"
            ],
            [
                "county_id" => $county_32->id,
                "name" => "Subukia"
            ],
            [
                "county_id" => $county_29->id,
                "name" => "Aldai"
            ],
            [
                "county_id" => $county_29->id,
                "name" => "Chesumei"
            ],
            [
                "county_id" => $county_29->id,
                "name" => "Emgwen"
            ],
            [
                "county_id" => $county_29->id,
                "name" => "Mosop"
            ],
            [
                "county_id" => $county_29->id,
                "name" => "Namdi hills"
            ],
            [
                "county_id" => $county_29->id,
                "name" => "Tindiret"
            ],
            [
                "county_id" => $county_33->id,
                "name" => "Narok east"
            ],
            [
                "county_id" => $county_33->id,
                "name" => "Narok north"
            ],
            [
                "county_id" => $county_33->id,
                "name" => "Narok south"
            ],
            [
                "county_id" => $county_33->id,
                "name" => "Narok west"
            ],
            [
                "county_id" => $county_33->id,
                "name" => "Transmara east"
            ],
            [
                "county_id" => $county_33->id,
                "name" => "Transmara west"
            ],
            [
                "county_id" => $county_46->id,
                "name" => "Borabu"
            ],
            [
                "county_id" => $county_46->id,
                "name" => "Manga"
            ],
            [
                "county_id" => $county_46->id,
                "name" => "Masaba north"
            ],
            [
                "county_id" => $county_46->id,
                "name" => "Nyamira north"
            ],
            [
                "county_id" => $county_46->id,
                "name" => "Nyamira south"
            ],
            [
                "county_id" => $county_18->id,
                "name" => "Kinangop"
            ],
            [
                "county_id" => $county_18->id,
                "name" => "Kipipiri"
            ],
            [
                "county_id" => $county_18->id,
                "name" => "Ndaragwa"
            ],
            [
                "county_id" => $county_18->id,
                "name" => "Ol Kalou"
            ],
            [
                "county_id" => $county_18->id,
                "name" => "Ol joro orok"
            ],
            [
                "county_id" => $county_19->id,
                "name" => "Kieni east"
            ],
            [
                "county_id" => $county_19->id,
                "name" => "Kieni west"
            ],
            [
                "county_id" => $county_19->id,
                "name" => "Mathira east"
            ],
            [
                "county_id" => $county_19->id,
                "name" => "Mathira west"
            ],
            [
                "county_id" => $county_19->id,
                "name" => "Mkurweni"
            ],
            [
                "county_id" => $county_19->id,
                "name" => "Nyeri town"
            ],
            [
                "county_id" => $county_19->id,
                "name" => "Othaya"
            ],
            [
                "county_id" => $county_19->id,
                "name" => "Tetu"
            ],
            [
                "county_id" => $county_25->id,
                "name" => "Samburu east"
            ],
            [
                "county_id" => $county_25->id,
                "name" => "Samburu north"
            ],
            [
                "county_id" => $county_25->id,
                "name" => "Samburu west"
            ],
            [
                "county_id" => $county_41->id,
                "name" => "Alego usonga"
            ],
            [
                "county_id" => $county_41->id,
                "name" => "Bondo"
            ],
            [
                "county_id" => $county_41->id,
                "name" => "Gem"
            ],
            [
                "county_id" => $county_41->id,
                "name" => "Rarieda"
            ],
            [
                "county_id" => $county_41->id,
                "name" => "Ugenya"
            ],
            [
                "county_id" => $county_41->id,
                "name" => "Unguja"
            ],
            [
                "county_id" => $county_6->id,
                "name" => "Mwatate"
            ],
            [
                "county_id" => $county_6->id,
                "name" => "Taveta"
            ],
            [
                "county_id" => $county_6->id,
                "name" => "Voi"
            ],
            [
                "county_id" => $county_6->id,
                "name" => "Wundanyi"
            ],
            [
                "county_id" => $county_4->id,
                "name" => "Bura"
            ],
            [
                "county_id" => $county_4->id,
                "name" => "Galole"
            ],
            [
                "county_id" => $county_4->id,
                "name" => "Garsen"
            ],
            [
                "county_id" => $county_13->id,
                "name" => "Chuka"
            ],
            [
                "county_id" => $county_13->id,
                "name" => "Igambangobe"
            ],
            [
                "county_id" => $county_13->id,
                "name" => "Maara"
            ],
            [
                "county_id" => $county_13->id,
                "name" => "Muthambi"
            ],
            [
                "county_id" => $county_13->id,
                "name" => "Tharak north"
            ],
            [
                "county_id" => $county_13->id,
                "name" => "Tharaka south"
            ],
            [
                "county_id" => $county_26->id,
                "name" => "Cherangany"
            ],
            [
                "county_id" => $county_26->id,
                "name" => "Endebess"
            ],
            [
                "county_id" => $county_26->id,
                "name" => "Kiminini"
            ],
            [
                "county_id" => $county_26->id,
                "name" => "Kwanza"
            ],
            [
                "county_id" => $county_26->id,
                "name" => "Saboti"
            ],
            [
                "county_id" => $county_23->id,
                "name" => "Loima"
            ],
            [
                "county_id" => $county_23->id,
                "name" => "Turkana central"
            ],
            [
                "county_id" => $county_23->id,
                "name" => "Turkana east"
            ],
            [
                "county_id" => $county_23->id,
                "name" => "Turkana north"
            ],
            [
                "county_id" => $county_23->id,
                "name" => "Turkana south"
            ],
            [
                "county_id" => $county_27->id,
                "name" => "Ainabkoi"
            ],
            [
                "county_id" => $county_27->id,
                "name" => "Kapseret"
            ],
            [
                "county_id" => $county_27->id,
                "name" => "Kesses"
            ],
            [
                "county_id" => $county_27->id,
                "name" => "Moiben"
            ],
            [
                "county_id" => $county_27->id,
                "name" => "Soy"
            ],
            [
                "county_id" => $county_27->id,
                "name" => "Turbo"
            ],
            [
                "county_id" => $county_38->id,
                "name" => "Emuhaya"
            ],
            [
                "county_id" => $county_38->id,
                "name" => "Hamisi"
            ],
            [
                "county_id" => $county_38->id,
                "name" => "Luanda"
            ],
            [
                "county_id" => $county_38->id,
                "name" => "Sabatia"
            ],
            [
                "county_id" => $county_38->id,
                "name" => "vihiga"
            ],
            [
                "county_id" => $county_8->id,
                "name" => "Eldas"
            ],
            [
                "county_id" => $county_8->id,
                "name" => "Tarbaj"
            ],
            [
                "county_id" => $county_8->id,
                "name" => "Wajir East"
            ],
            [
                "county_id" => $county_8->id,
                "name" => "Wajir North"
            ],
            [
                "county_id" => $county_8->id,
                "name" => "Wajir South"
            ],
            [
                "county_id" => $county_8->id,
                "name" => "Wajir West"
            ],
            [
                "county_id" => $county_24->id,
                "name" => "Central Pokot"
            ],
            [
                "county_id" => $county_24->id,
                "name" => "North Pokot"
            ],
            [
                "county_id" => $county_24->id,
                "name" => "Pokot South"
            ],
            [
                "county_id" => $county_24->id,
                "name" => "West Pokot"
            ],
        ]);
    }
}
