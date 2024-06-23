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

    public function index(){
        $tickets = DB::select(DB::raw("
            SELECT t.* FROM system_tickets t
            WHERE t.status != 'draft'
            ORDER BY t.created_at DESC
        "));

        return view("pages.admin.support.index", compact('tickets'));
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