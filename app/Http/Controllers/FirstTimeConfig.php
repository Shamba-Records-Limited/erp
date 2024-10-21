<?php

namespace App\Http\Controllers;

use App\Country;
use App\Events\AuditTrailEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class FirstTimeConfig extends Controller
{
    public function __construct()
    {
//        $this->middleware('auth');

    }

    public function save_country_details()
    {
        $file_content = file_get_contents(public_path("/files/cooperative/country-codes.json"));

        $json = json_decode($file_content, true);

        foreach ($json as $data)
        {
            $iso_code = $data["ISO3166-1-Alpha-2"];
            $country_name = $data["CLDR display name"];
            if($iso_code != null and $country_name != null)
            {
                $exists = Country::where('name','=', $country_name)->where('iso_code','=',$iso_code)->count();
                if($exists == 0)
                {
                    Country::create([
                        "name" => $country_name,
                        "iso_code"=> strtolower($iso_code)
                    ]);
                }
            }

        }

        return redirect()->back();
    }

    public function optimize_app(): \Illuminate\Http\RedirectResponse
    {
        Artisan::call('optimize:clear');

        $data = ['user_id' => Auth::user()->id, 'activity' => 'Optimized App', 'cooperative_id'=> Auth::user()->cooperative->id];

        event(new AuditTrailEvent($data));
        return redirect()->back();
    }
}
