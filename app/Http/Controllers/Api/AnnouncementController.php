<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Employee;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user->employee_id) {
            return response()->json(['message' => 'User not linked to an employee profile'], 400);
        }

        $employee = Employee::where('employee_id', $user->employee_id)->with('department')->first();
        if (!$employee) {
            return response()->json(['message' => 'Employee profile not found'], 404);
        }

        $announcements = Announcement::where('company_id', $employee->company_id)
            ->where(function ($query) use ($employee) {
                $query->where('target_audience', 'All')
                      ->orWhere('target_audience', $employee->department->name ?? '');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['data' => $announcements]);
    }
}
