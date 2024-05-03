<?php

namespace App\Http\Controllers;

use App\Events\AuditTrailEvent;
use App\Events\NewUserRegisteredEvent;
use App\Exports\VetBookingsExport;
use App\Exports\VetExtensionServicesVetsExport;
use App\User;
use App\Vet;
use App\VetBooking;
use App\VetItem;
use App\VetService;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Role;

class VetController extends Controller
{
    use \App\Http\Traits\Vet;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $vets = User::select("users.first_name", 'users.other_names', 'users.id', 'users.email', 'users.username')
            ->join("model_has_roles", "model_has_roles.model_id", "users.id")
            ->join("roles", "roles.id", "model_has_roles.role_id")
            ->where("roles.name", "vet")
            ->where('users.cooperative_id', $user->cooperative_id)
            ->with(['vet'])->get();
        return view('pages.cooperative.vets.index', compact('vets'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'first_name' => 'required|string',
            'other_names' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone_no' => 'required|regex:/^(07\d{8})$/|unique:users,username',
            'id_no' => 'required|regex:/^[0-9]{7,8}$/|unique:vets,id_no',
            'image' => 'sometimes|mimes:jpeg,jpg,png|max:5000',
            'gender' => 'required',
            'category' => 'required'
        ]);

        $password = generate_password();
        $user = new User();
        $this->persist_user($request, $user, $password);
        $user = User::where('email', '=', $request->email)->first();
        $this->save($request, $user->id, new Vet());
        $role = Role::select('id', 'name')->where('name', 'vet')->first();
        $user->assignRole($role->name);

        $role_created_audit = ['user_id' => Auth::user()->id, 'activity' => 'Assigned ' . $role->name .
            ' to  ' . $user->username, 'cooperative_id' => Auth::user()->cooperative->id];
        event(new AuditTrailEvent($role_created_audit));
        $data = [
            "name" => ucwords(strtolower($request->first_name)) . ' ' . ucwords(strtolower($request->other_names)),
            "email" => $request->email, "password" => $password
        ];
        $audit_trail_data = ['user_id' => Auth::user()->id, 'activity' => 'Created ' . $user->username . 'account', 'cooperative_id' => Auth::user()->cooperative->id];
        event(new AuditTrailEvent($audit_trail_data));
        event(new NewUserRegisteredEvent($data));

        toastr()->success('Vet Created Successfully');
        return redirect()->back();
    }


    private function save($request, $user_id, $vet)
    {
        $vet->phone_no = $request->phone_no;
        $vet->gender = $request->gender;
        $vet->id_no = $request->id_no;
        $vet->user_id = $user_id;
        $vet->category = $request->category;
        $vet->profile_image = store_image($request, "image", $request->image, "images/vets", 250, 250);
        $vet->save();
    }


    private function persist_user($request, $user, $password)
    {
        $user->first_name = ucwords(strtolower($request->first_name));
        $user->other_names = ucwords(strtolower($request->other_names));
        $user->cooperative_id = Auth::user()->cooperative->id;
        $user->email = $request->email;
        $user->username = $request->phone_no;
        $user->password = Hash::make($password);
        $user->save();
    }


    public function booking_index()
    {
        $user = Auth::user();
        $users = User::select("users.first_name", 'users.other_names', 'users.id')
            ->join("model_has_roles", "model_has_roles.model_id", "users.id")
            ->join("roles", "roles.id", "model_has_roles.role_id")
            ->where("roles.name", "farmer")
            ->where('users.cooperative_id', $user->cooperative_id)
            ->get();
        $vet_items = VetItem::vet_items($user->cooperative_id);
        $services = VetService::services($user->cooperative_id);
        $bookings = VetBooking::bookings($user);
        return view('pages.cooperative.vets.bookings', compact('users', 'services', 'bookings', 'vet_items'));
    }

    public function get_vets_by_category($category): \Illuminate\Support\Collection
    {
        $user = Auth::user();
        return User::select("users.first_name", 'users.other_names', 'users.id')
            ->join("vets", "vets.user_id", "users.id")
            ->join("model_has_roles", "model_has_roles.model_id", "users.id")
            ->join("roles", "roles.id", "model_has_roles.role_id")
            ->where("roles.name", "vet")
            ->where("vets.category", $category)
            ->where('users.cooperative_id', $user->cooperative_id)
            ->get();
    }

    public function get_bookings()
    {
        $bookings = VetBooking::where('cooperative_id', Auth::user()->cooperative->id)->latest()->limit(100)->get();
        $data = [];
        foreach ($bookings as $b) {
            $res = [
                "start" => $b->event_start,
                "end" => $b->event_end,
                "title" => ucwords(strtolower($b->vet->first_name) . ' ' . strtolower($b->vet->other_names)) . ': '
                    . ucwords(strtolower($b->farmer->first_name) . ' ' . strtolower($b->farmer->other_names)) . ': ' . $b->event_name
            ];

            array_push($data, $res);
        }

        return response()->json($data);
    }

    public function add_bookings(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'vet' => 'required',
            'farmer' => 'required',
            'start' => 'required',
            'duration' => 'required|integer|min:1|max:12',
            'booking_details' => 'required|string',
            'type' => 'required|string',
            'service' => 'required|string',
        ]);
        $user = Auth::user();
        return $this->addBooking($request, $request->farmer, $user);
    }

    public function edit_bookings(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'edit_vet' => 'required',
            'edit_farmer' => 'required',
            'edit_duration' => 'required|integer|min:1|max:12',
            'edit_booking_details' => 'required|string',
            'edit_type' => 'required|string',
            'edit_service' => 'required|string',
        ]);

        $booking = VetBooking::findOrFail($id);
        $user = Auth::user();
        return $this->editVetBooking($booking, $request, $user, $id, $request->edit_farmer);
    }

    public function edit_booking_status(Request $request, $bookingId): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'status' => 'required',
            'charges' => 'required|min:0|regex:/^\d+(\.\d{1,2})?$/'
        ]);
        $user = Auth::user();
        $booking = VetBooking::findOrFail($bookingId);
        try {
            DB::beginTransaction();
            return $this->updateStatus($user, $request->charges, $request->status, $booking, $bookingId);
            //
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back();
        }
    }


    public function add_vet_items_to_bookings(Request $request, $bookingId): \Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            'item' => 'required',
            'quantity' => 'required|min:0|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        $user = Auth::user();

        $booking = VetBooking::findOrFail($bookingId);
        $item = VetItem::findOrFail($request->item);
        if ($request->quantity > $item->quantity) {
            toastr()->error('Quantity is more than the available quantity');
            return redirect()->back();
        }

        try {
            DB::beginTransaction();
            $item->quantity -= $request->quantity;
            $item->sold_quantity += $request->quantity;
            $item->save();
            $amount = $request->quantity * $item->sp;

            $trx = create_account_transaction('Vet Charges', $amount, 'Sold vet items');
            if ($trx) {
                $booking->vet_items()->attach($request->items, ['quantity' => $request->quantity]);
                $booking->save();
                DB::commit();
                $audit_trail_data = ['user_id' => $user->id, 'activity' => 'Added vet items to booking id ' . $bookingId, 'cooperative_id' => $user->cooperative->id];
                event(new AuditTrailEvent($audit_trail_data));
                toastr()->success('Vet items added');
            } else {
                DB::rollback();
                toastr()->error('Oops! Operation failed');
            }

            return redirect()->back();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            DB::rollback();
            toastr()->error('Oops! Operation failed');
            return redirect()->back();
        }
    }
    public function export_vet_bookings($type)
    {
        $user = Auth::user();
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('vet_bookings_' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new VetBookingsExport($user), $file_name);
        } else {
            $data = [
                'title' => 'Vet Bookings',
                'pdf_view' => 'vet_bookings',
                'records' => VetBooking::bookings($user),
                'filename' => strtolower('vet_bookings_' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }

    public function download_vets($type)
    {
        $cooperative_id = Auth::user()->cooperative_id;
        if ($type != env('PDF_FORMAT')) {
            $file_name = strtolower('vets' . date('d_m_Y')) . '.' . $type;
            return Excel::download(new VetExtensionServicesVetsExport($cooperative_id), $file_name);
        } else {
            $data = [
                'title' => 'Vets',
                'pdf_view' => 'vets',
                'records' => User::select("users.first_name", 'users.other_names', 'users.id', 'users.email', 'users.username')
                    ->join("model_has_roles", "model_has_roles.model_id", "users.id")
                    ->join("roles", "roles.id", "model_has_roles.role_id")
                    ->where("roles.name", "vet")
                    ->where('users.cooperative_id', $cooperative_id)
                    ->with(['vet'])->get(),
                'filename' => strtolower('vets' . date('d_m_Y')),
                'orientation' => 'landscape'
            ];
            return download_pdf($data);
        }
    }
}
