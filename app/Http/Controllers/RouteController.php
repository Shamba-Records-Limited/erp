<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\Exports\RouteExport;
use App\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class RouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $routes = Route::select('name', 'id')->latest()->where('cooperative_id', Auth::user()->cooperative->id)->get();
        return view('pages.cooperative.route.index', compact('routes'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            "name" => "required|string",
        ]);

        try {

            $user = Auth::user();
            DB::beginTransaction();
            Route::create([
                "name" => $request->name,
                "cooperative_id" => $user->cooperative->id
            ]);
            DB::commit();

            $data = ['user_id' => $user->id, 'activity' => 'created  ' . $request->name . ' route',
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Route Created Successfully');
            return redirect()->route('cooperative.routes.show');

        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function edit(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            "name_edit" => "required|string",
        ]);

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $route = Route::findOrFail($id);
            $route->name = $request->name_edit;
            $route->save();
            DB::commit();

            $data = ['user_id' => $user->id, 'activity' => 'updated  ' . $route->id . ' route',
                'cooperative_id' => $user->cooperative->id];
            event(new AuditTrailEvent($data));
            toastr()->success('Route Edited Successfully');
            return redirect()->route('cooperative.routes.show');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            toastr()->error('Oops! Error occurred');
            return redirect()->back();
        }
    }

    public function download_routes($type)
    {
        $cooperative = Auth::user()->cooperative_id;
        $routes = Route::select('name', 'created_at')->where('cooperative_id', $cooperative)
            ->orderBy('name')
            ->get();
        $file_name = 'routes';
        if ($type != env('PDF_FORMAT')) {
            $file_name .= '.' . $type;
            return Excel::download(new RouteExport($routes), $file_name);
        } else {
            $data = [
                'title' => 'Routes',
                'pdf_view' => 'routes',
                'records' => $routes,
                'filename' => $file_name,
                'orientation' => 'portrait'
            ];
            return deprecated_download_pdf($data);
        }
    }
}
