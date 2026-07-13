<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role === 'Super Admin') {
            $tickets = Ticket::with(['user', 'company'])->latest()->paginate(10);
            return view('superadmin.tickets.index', compact('tickets'));
        }

        $tickets = Ticket::where('company_id', $user->company_id)->latest()->paginate(10);
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'category' => 'required|in:Technical,Billing,Feature'
        ]);

        $user = auth()->user();
        $ticket = Ticket::create([
            'user_id' => $user->id,
            'company_id' => $user->company_id,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority' => $validated['priority'],
            'category' => $validated['category']
        ]);

        // Notify Super Admin
        $superAdmins = \App\Models\User::where('role', 'Super Admin')->whereNotNull('fcm_token')->get();
        $notificationService = new \App\Services\NotificationService();
        foreach ($superAdmins as $admin) {
            $notificationService->sendPushNotification(
                $admin->fcm_token,
                'New Support Ticket',
                "New {$validated['priority']} ticket from {$user->name}: {$validated['subject']}",
                ['type' => 'ticket', 'ticket_id' => $ticket->id]
            );
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket submitted successfully.');
    }

    public function show(Ticket $ticket)
    {
        $user = auth()->user();
        if ($user->role !== 'Super Admin' && $ticket->company_id !== $user->company_id) {
            abort(403);
        }

        return view('tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        if (auth()->user()->role !== 'Super Admin') {
            abort(403);
        }

        $request->validate(['status' => 'required|string']);
        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'Ticket status updated.');
    }
}
