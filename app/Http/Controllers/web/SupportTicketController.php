<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller{

    public function show(SupportTicket $ticket){
        if(!auth()->user()) return redirect('login');
        return view('support.show', compact('ticket'));
    }

    public function list(){
        if (!auth()->user()->hasPermissionTo('Complaints')) {
        abort(401);
    }

        $tickets = SupportTicket::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('support.list', compact('tickets'));
    }

    public function add(){
        if (!auth()->user()->hasPermissionTo('Complaints')) {
        abort(401);
    }

        return view('support.add');
    }

    public function store(Request $request){
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        SupportTicket::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'sent'
        ]);

        return redirect()->route('support.list')
            ->with('success', 'Complaint created successfully!');
    }

}
