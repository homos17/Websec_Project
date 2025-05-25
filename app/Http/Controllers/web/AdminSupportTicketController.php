<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSupportTicketController extends Controller{

    public function index(){
        if (!auth()->user()->hasPermissionTo('Complaints'))
            abort(401);

        $tickets = SupportTicket::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.index', compact('tickets'));
    }

    public function show(SupportTicket $ticket){
        if (!auth()->user()->hasPermissionTo('Complaints'))
            abort(401);



        return view('admin.show', compact('ticket'));
    }

    public function reply(Request $request, SupportTicket $ticket){
        if (!auth()->user()->hasPermissionTo('Complaints'))
            abort(401);

        if ($ticket->status === 'done') {
            return redirect()->back()->with('error', 'Cannot reply to a closed ticket.');
        }

        $request->validate([
            'admin_reply' => 'required|string'
        ]);

        $ticket->update([
            'admin_reply' => $request->admin_reply,
            'admin_id' => Auth::id(),
            'status' => 'in_progress'
        ]);

        return redirect()->back()
            ->with('success', 'Reply sent successfully!');
    }

    public function close(SupportTicket $ticket){
        if (!auth()->user()->hasPermissionTo('Complaints'))
            abort(401);

        $ticket->update(['status' => 'done']);

        return redirect()->back()
            ->with('success', 'Complaint closed successfully!');
    }
}
