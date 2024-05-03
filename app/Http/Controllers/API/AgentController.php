<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function farmers(): \Illuminate\Http\JsonResponse
    {
        $cooperative_id = auth()->user()->cooperative_id;

        if(!auth()->user()->hasRole("agent"))
        {
            $value= "farmer";
            $farmers  = User::select(['users.first_name','users.other_names','users.username',
                'users.email', 'farmers.county','farmers.phone_no as phone','farmers.location'])
                ->whereHas('roles', function($q) use($value) {
                    $q->where('name', '=', $value);
                })->join('farmers', 'farmers.user_id','=','users.id')
                ->where("users.cooperative_id", $cooperative_id)->get();

            return response()->json([
                "success"=>true,
                "message"=>"Success",
                "data"=>$farmers
            ]);
        }

        return response()->json([
            "success"=>false,
            "message"=>"You are not allowed to perform this operations",
            "data"=>null
        ], 401);

    }
}
