<?php

namespace App\Http\Controllers;


use App\Http\Traits\Disease;
use Auth;
use Illuminate\Support\Facades\DB;

class DiseaseMiniDashboardController extends Controller
{
    use Disease;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cooperative = Auth::user()->cooperative_id;
        $disease_cases = $this->dashboard($cooperative);
        return view('pages.cooperative.minidashboards.disease-management', compact('disease_cases'));
    }

    public function stats(): array
    {
        $cooperative = Auth::user()->cooperative_id;
        return $this->dashboard_stats($cooperative);
    }

    public function disease_map_data(): array
    {
        $cooperative = Auth::user()->cooperative_id;
        return $this->map_data($cooperative);
    }
}
