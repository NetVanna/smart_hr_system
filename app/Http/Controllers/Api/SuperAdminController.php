<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function getStats()
    {
        $pendingSubscriptions = \App\Models\Subscription::where('status', 'Pending Approval')->count();
        $openTickets = \App\Models\Ticket::where('status', 'Open')->count();

        return response()->json([
            'data' => [
                'total_companies' => \App\Models\Company::count(),
                'total_employees' => \App\Models\Employee::count(),
                'active_subscriptions' => \App\Models\Subscription::where('status', 'Active')->count(),
                'active_alerts' => $pendingSubscriptions + $openTickets,
                'system_status' => 'Healthy',
            ]
        ]);
    }
    public function getCompanies()
    {
        $companies = \App\Models\Company::select('id', 'name', 'email', 'phone', 'subscription_status', 'created_at')
            ->with(['subscriptions' => function($query) {
                $query->where('status', 'Active')->select('company_id', 'plan', 'status');
            }, 'users' => function($query) {
                $query->where('role', 'Company Admin')->select('id', 'company_id', 'name', 'email', 'telegram_username');
            }])
            ->get()->map(function($company) {
                $admin = $company->users->first();
                return [
                    'id' => $company->id,
                    'name' => $company->name,
                    'email' => $company->email,
                    'phone' => $company->phone,
                    'status' => $company->subscription_status,
                    'plan' => $company->subscriptions->first()->plan ?? 'Free',
                    'created_at' => $company->created_at->format('M d, Y'),
                    'admin_name' => $admin->name ?? 'N/A',
                    'admin_email' => $admin->email ?? 'N/A',
                    'admin_telegram' => $admin->telegram_username ?? 'N/A',
                ];
            });

        return response()->json(['data' => $companies]);
    }

    public function getUsers()
    {
        $users = \App\Models\Employee::with('company:id,name')->get()->map(function($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'email' => $employee->email,
                'designation' => $employee->designation,
                'company_name' => $employee->company->name ?? 'No Company',
                'status' => $employee->status ?? 'Active',
            ];
        });

        return response()->json(['data' => $users]);
    }

    public function getSubscriptions()
    {
        $subscriptions = \App\Models\Subscription::with('company:id,name')->get()->map(function($sub) {
            return [
                'id' => $sub->id,
                'company_name' => $sub->company->name ?? 'No Company',
                'plan_name' => $sub->plan,
                'status' => $sub->status,
                'amount' => $sub->price,
                'start_date' => $sub->start_date ? \Carbon\Carbon::parse($sub->start_date)->format('M d, Y') : 'N/A',
                'end_date' => $sub->end_date ? \Carbon\Carbon::parse($sub->end_date)->format('M d, Y') : 'N/A',
                'receipt_path' => $sub->receipt_path ? asset('storage/' . $sub->receipt_path) : null,
            ];
        });

        return response()->json(['data' => $subscriptions]);
    }

    public function getAlerts()
    {
        $alerts = [];

        // Pending Subscriptions
        $pendingSubs = \App\Models\Subscription::with('company:id,name')
            ->where('status', 'Pending Approval')
            ->get();
        
        foreach ($pendingSubs as $sub) {
            $alerts[] = [
                'id' => $sub->id, // Added id for actions
                'type' => 'Subscription',
                'title' => 'New Subscription Request',
                'message' => 'Company ' . ($sub->company->name ?? 'Unknown') . ' requested ' . $sub->plan . ' plan.',
                'time' => $sub->created_at->diffForHumans(),
                'priority' => 'High',
            ];
        }

        // Open Tickets
        $openTickets = \App\Models\Ticket::with('company:id,name')
            ->where('status', 'Open')
            ->get();

        foreach ($openTickets as $ticket) {
            $alerts[] = [
                'id' => $ticket->id, // Added id for actions
                'type' => 'Support',
                'title' => 'New Support Ticket',
                'message' => '[' . $ticket->priority . '] ' . $ticket->subject,
                'time' => $ticket->created_at->diffForHumans(),
                'priority' => $ticket->priority,
            ];
        }

        return response()->json(['data' => $alerts]);
    }

    public function approveSubscription($id)
    {
        $subscription = \App\Models\Subscription::findOrFail($id);
        
        $subscription->update([
            'status' => 'Active',
            'start_date' => now(),
            'end_date' => now()->addMonth(), // Assuming monthly for now
        ]);

        if ($subscription->company) {
            $subscription->company->update(['subscription_status' => 'Active']);
        }

        return response()->json(['message' => 'Subscription approved successfully']);
    }

    public function rejectSubscription($id)
    {
        $subscription = \App\Models\Subscription::findOrFail($id);
        $subscription->update(['status' => 'Rejected']);

        return response()->json(['message' => 'Subscription rejected']);
    }

    public function updateCompanyStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        $company = \App\Models\Company::findOrFail($id);
        $company->update(['subscription_status' => $request->status]);

        return response()->json(['message' => 'Company status updated to ' . $request->status]);
    }

    public function updateUserStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string']);
        $employee = \App\Models\Employee::findOrFail($id);
        $employee->update(['status' => $request->status]);

        return response()->json(['message' => 'User status updated to ' . $request->status]);
    }

    public function resetAdminPassword(Request $request, $username)
    {
        $request->validate([
            'password' => 'required|string|min:6'
        ]);

        $user = \App\Models\User::where('telegram_username', $username)
            ->where('role', 'Company Admin')
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Admin user not found'], 404);
        }

        $user->update([
            'password' => \Illuminate\Support\Facades\Hash::make($request->password)
        ]);

        return response()->json(['message' => 'Password reset successfully']);
    }

    public function generateMagicLink($username)
    {
        $user = \App\Models\User::where('telegram_username', $username)
            ->where('role', 'Company Admin')
            ->first();

        if (!$user) {
            return response()->json(['error' => 'Admin user not found'], 404);
        }

        $token = \Illuminate\Support\Str::random(64);
        $user->update([
            'magic_login_token' => $token,
            'magic_login_token_expires_at' => \Carbon\Carbon::now()->addHours(24),
        ]);

        $link = route('login.magic', $token);

        return response()->json(['data' => ['link' => $link]]);
    }
}
