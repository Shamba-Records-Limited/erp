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

        $tickets = DB::select(DB::raw("
            SELECT t.* FROM system_tickets t
            WHERE t.created_by_id = '$user_id'
                AND t.published_at IS NOT NULL
            ORDER BY t.created_at DESC
        "));

        return view("pages.cooperative-admin.support.index", compact('tickets'));
    }

    public function view_add_ticket()
    {
        $user_id = Auth::id();

        // retrieve or create draft
        $ticket = null;
        try {
            $ticket = DB::select(DB::raw("
                SELECT * FROM system_tickets
                WHERE created_by_id = '$user_id'
                    AND status='Draft'
                ORDER BY created_at DESC
                LIMIT 1
            "))[0];
        } catch (\Throwable $th) {
            // create new ticket
            $now = Carbon::now();
            $now_str = strtoupper($now->format('Ymd'));

            $totalTickets = DB::select(DB::raw("
                SELECT COUNT(*) AS total FROM system_tickets
                WHERE DATE(created_at) = CURDATE() 
            "))[0]->total;

            $ticket_number = "T$now_str-$totalTickets";

            $ticket = new SystemTicket();
            $ticket->number = $ticket_number;
            $ticket->created_by_id = $user_id;
            $ticket->save();

            $ticket = DB::select(DB::raw("
                SELECT * FROM system_tickets
                WHERE created_by_id = '$user_id'
                    AND status='Draft'
                ORDER BY created_at DESC
                LIMIT 1
            "))[0];
        }

        return view("pages.cooperative-admin.support.add_ticket", compact('ticket'));
    }

    public function add_ticket(Request $request)
    {
        $request->validate([
            "ticket_number" => "required",
        ]);

        $ticket = SystemTicket::where("number", $request->ticket_number)->first();
        $ticket->title = $request->title;
        $ticket->description = $request->description;
        $ticket->labels = $request->labels;
        $ticket->save();
    }

    public function publish_ticket(Request $request)
    {
        $request->validate([
            "number" => "required",
            "title" => "required",
            "description" => "required",
            "labels" => "sometimes"
        ]);

        $ticket = SystemTicket::where("number", $request->number)->first();
        if (!$ticket) {
            toastr()->error('Ticket not found.');
            return response()->json([
                "success" => false
            ]);
        }

        $ticket->title = $request->title;
        $ticket->description = $request->description;
        $ticket->labels = $request->labels;
        $ticket->status = "open";
        $ticket->published_at = Carbon::now();
        $ticket->save();

        toastr()->success('Ticket published successfully.');
    }

    public function view_ticket($ticket_number){
        $ticket = SystemTicket::where("number", $ticket_number)->first();

        $comments = DB::select(DB::raw("
            SELECT c.*, u.username AS user_name FROM system_ticket_comment c
            JOIN users u ON c.user_id = u.id
            WHERE c.ticket_id = '$ticket->id'
            ORDER BY c.created_at DESC
        "));

        return view("pages.cooperative-admin.support.view_ticket", compact('ticket', 'comments'));
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

    public function delete_ticket($ticket_id)
    {
        $ticket = SystemTicket::find($ticket_id);
        $ticket->delete();
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
