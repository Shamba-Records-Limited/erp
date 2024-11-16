<?php

namespace App\Http\Controllers\CooperativeAdmin;

use App\Http\Controllers\Controller;
use App\SystemTicket;
use App\SystemTicketComment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

public function index()
{
    $user_id = Auth::id();

    // Modified query to properly order tickets
    $tickets = DB::table('system_tickets')
        ->where('created_by_id', $user_id)
        ->orderBy('created_at', 'DESC')
        ->get();

    return view("pages.cooperative-admin.support.index", compact('tickets'));
}

public function view_add_ticket()
{
    $user_id = Auth::id();

    // Get the current date format
    $now = Carbon::now();
    $now_str = strtoupper($now->format('Ymd'));

    // Get the highest ticket number for today
    $lastTicket = DB::select(DB::raw("
        SELECT number 
        FROM system_tickets 
        WHERE number LIKE 'T$now_str%'
        ORDER BY number DESC 
        LIMIT 1
    "));

    // Generate new ticket number
    $ticket_number = "T$now_str-1";
    if (!empty($lastTicket)) {
        $lastNumber = (int)substr($lastTicket[0]->number, strrpos($lastTicket[0]->number, '-') + 1);
        $ticket_number = "T$now_str-" . ($lastNumber + 1);
    }

    // Create new ticket
    $ticket = new SystemTicket();
    $ticket->number = $ticket_number;
    // $ticket->created_by_id = $user_id;
    // $ticket->status = 'Draft';
    // $ticket->save();

    return view("pages.cooperative-admin.support.add_ticket", compact('ticket'));
}

public function add_ticket(Request $request)
{
    $request->validate([
        "ticket_number" => "required",
        "subject" => "required|string",
        "module" => "nullable|string",
        "submodule" => "nullable|string",
        "link" => "nullable|url",          // Separate 'link' validation
        "description" => "required|string",
        "labels" => "sometimes|nullable|string",
        "image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048", // Image validation
    ]);

    $ticket = SystemTicket::where("number", $request->ticket_number)->first() ?: new SystemTicket();

    // Process the image upload
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('ticket_images', 'public');
        $ticket->image = $imagePath; // Save image path
    }

    // Set other ticket fields
    $ticket->title = $request->subject;
    $ticket->module = $request->module;
    $ticket->submodule = $request->submodule;
    $ticket->link = $request->link;   // Store only the link here
    $ticket->description = $request->description;
    $ticket->labels = $request->labels;
    $ticket->status = 'Draft';
    $ticket->created_by_id = Auth::id();
    $ticket->save();

    return response()->json(['success' => true, 'message' => 'Ticket saved successfully.']);
}

public function publish_ticket(Request $request)
{
    $user_id = Auth::id();

    $request->validate([
        "number" => "required",
        "subject" => "required|string",
        "module" => "nullable|string",
        "submodule" => "nullable|string",
        "link" => "nullable|url",
        "description" => "required|string",
        "image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048", // Add validation for image
    ]);

    // if (!$ticket) {
    //     return response()->json(["success" => false, "message" => 'Ticket not found.']);
    // }
    $ticket = new SystemTicket();
    $ticket->created_by_id = $user_id;

   // Process the image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ticket_images', 'public');
            \Log::info('Image uploaded:', ['path' => $imagePath]);
            $ticket->image = $imagePath; // Store the image path
        }
    // Update other ticket fields
    $ticket->title = $request->subject;
    $ticket->module = $request->module;
    $ticket->submodule = $request->submodule;
    $ticket->link = $request->link;
    $ticket->description = $request->description;
    $ticket->number = $request->number;


    $ticket->status = "open";
    $ticket->published_at = Carbon::now();
    $ticket->save();

    return response()->json(["success" => true, "message" => "Ticket published successfully."]);
}

    public function view_ticket($ticket_number)
    {
        $ticket = SystemTicket::where("number", $ticket_number)->first();
    \Log::info('Ticket Data:', ['ticket' => $ticket]);

        $comments = DB::select(DB::raw("
            SELECT c.*, u.username AS user_name FROM system_ticket_comment c
            JOIN users u ON c.user_id = u.id
            WHERE c.ticket_id = '$ticket->id'
            ORDER BY c.created_at ASC
        "));

        return view("pages.cooperative-admin.support.view_ticket", compact('ticket', 'comments'));
    }

    public function add_comment(Request $request)
    {
        $user_id = Auth::id();
        $request->validate([
            "ticket_id" => "required",
            "comment" => "required|string",
        ]);

        $comment = new SystemTicketComment();
        $comment->user_id = $user_id;
        $comment->ticket_id = $request->ticket_id;
        $comment->comment = $request->comment;
        $comment->save();

        toastr()->success('Comment added successfully.');
        return redirect()->back();
    }

    public function delete_ticket($ticket_id)
    {
          $countComments = DB::select(DB::raw("
            SELECT count(*) as count
            FROM system_ticket_comment s 
            WHERE ticket_id = '$ticket_id';
        "));

        if($countComments[0]->count == 0){

             $ticket = SystemTicket::find($ticket_id);
        
                if ($ticket) {
                    $ticket->delete();
                    // toastr()->success("Ticket deleted successfully.");

                    return redirect()->route('cooperative-admin.support.show')->with('success', 'Ticket deleted successfully.');
                }

        }
        // toastr()->error("Ticket cannot be deleted.");

        return redirect()->route('cooperative-admin.support.show')->with('error', 'Ticket cannot be deleted.');
    }

    public function confirm_ticket_resolved($ticket_number)
    {
        $ticket = SystemTicket::where("number", $ticket_number)->first();
        $ticket->status = "closed";
        $ticket->confirmed_at = now();
        $ticket->save();

        toastr()->success("Ticket resolution accepted");
        return redirect()->back();
    }
}