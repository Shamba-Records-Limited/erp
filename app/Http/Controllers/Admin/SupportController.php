<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SystemTicket;
use App\SystemTicketComment;
use Illuminate\Http\Request;
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
    // Retrieve counts of tickets grouped by status
    $ticketCounts = [
        'open' => SystemTicket::where('status', 'open')->count(),
        'in_progress' => SystemTicket::where('status', 'in_progress')->count(),
        'answered' => SystemTicket::where('status', 'answered')->count(),
        'on_hold' => SystemTicket::where('status', 'on_hold')->count(),
        'closed' => SystemTicket::where('status', 'closed')->count(),
    ];

    // Retrieve all tickets except for those in 'draft' status for the main table
    $tickets = SystemTicket::where('status', '!=', 'draft')
        ->orderBy('created_at', 'DESC')
        ->get();

    // Pass both $ticketCounts and $tickets to the view
    return view("pages.admin.support.index", compact('ticketCounts', 'tickets'));
}



    public function view_ticket($ticket_number){
        $ticket = SystemTicket::where("number", $ticket_number)->first();

        $comments = DB::select(DB::raw("
            SELECT c.*, u.username AS user_name FROM system_ticket_comment c
            JOIN users u ON c.user_id = u.id
            WHERE c.ticket_id = '$ticket->id'
            ORDER BY c.created_at DESC
        "));

        return view("pages.admin.support.view_ticket", compact('ticket', 'comments'));
    }

    public function add_comment(Request $request)
    {
        $user_id = Auth::id();
        $request->validate([
            "ticket_id" => "required",
            "comment" => "required",
        ]);

        $comment = new SystemTicketComment();
        $comment->user_id = $user_id;
        $comment->ticket_id = $request->ticket_id;
        $comment->comment = $request->comment;
        $comment->save();

        toastr()->success('Comment added successfully.');
        return redirect()->back();
    }
  public function update_ticket_status(Request $request, $ticket_number)
{
    $user_id = Auth::id();

    // Validate that a valid status is provided
    $request->validate([
        'status' => 'required|in:open,in_progress,answered,on_hold,closed',
    ]);

    // Find the ticket by its number
    $ticket = SystemTicket::where("number", $ticket_number)->first();

    // Update the ticket status
    $ticket->status = $request->status;
    $ticket->solved_at = $request->status === 'closed' ? now() : null;
    $ticket->solved_by_id = $user_id;
    $ticket->save();

    toastr()->success('Ticket status updated successfully.');
    return redirect()->back();
}


    public function resolve_ticket($ticket_number)
    {
        $user_id = Auth::id();

        $ticket = SystemTicket::where("number", $ticket_number)->first();
        $ticket->status = "solved";
        $ticket->solved_at = now();
        $ticket->solved_by_id = $user_id;
        $ticket->save();

        toastr()->success('Ticket resolved successfully.');
        return redirect()->back();
    }

}