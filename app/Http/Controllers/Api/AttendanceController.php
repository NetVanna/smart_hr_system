<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Services\TelegramService;

class AttendanceController extends Controller
{
    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    public function scanQr(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string',
            'location' => 'nullable|string',
            'device_id' => 'nullable|string'
        ]);

        $employee = Employee::where('employee_id', $request->employee_id)->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        // Device Fingerprinting Check
        if ($request->device_id) {
            if (!$employee->authorized_device_id) {
                // Link the first device used
                $employee->update(['authorized_device_id' => $request->device_id]);
            } elseif ($employee->authorized_device_id !== $request->device_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized device. Please use your primary device for attendance.'
                ], 403);
            }
        }

        $today = today()->toDateString();
        $currentTime = now()->toTimeString();

        // Check if attendance already exists for today
        $attendance = Attendance::where('employee_id', $employee->id)->whereDate('date', $today)->first();

        if ($attendance) {
            // If already checked in but no check out, perform check out
            if (!$attendance->check_out) {
                $attendance->update([
                    'check_out' => $currentTime,
                    'location' => $request->location ?? $attendance->location
                ]);

                $this->sendTelegramNotification($employee, "Checked out", $currentTime, $request->location ?? $attendance->location);

                return response()->json(['message' => 'Checked out successfully', 'data' => $attendance]);
            } else {
                return response()->json(['message' => 'Already checked out for today', 'data' => $attendance], 400);
            }
        }

        // Create new check-in
        $attendance = Attendance::create([
            'company_id'  => $employee->company_id,
            'branch_id'   => $employee->branch_id,
            'employee_id' => $employee->id,
            'date'        => $today,
            'check_in'    => $currentTime,
            'method'      => 'QR',
            'location'    => $request->location
        ]);

        $this->sendTelegramNotification($employee, "Checked in", $currentTime, $request->location);

        return response()->json(['message' => 'Checked in successfully', 'data' => $attendance]);
    }

    protected function sendTelegramNotification($employee, $type, $time, $location)
    {
        $user = $employee->users()->whereNotNull('telegram_chat_id')->first();
        if ($user) {
            $message = "📍 *Attendance Alert*\n\n";
            $message .= "Hello *{$employee->first_name}*,\n";
            $message .= "You have successfully *{$type}*.\n\n";
            $message .= "🕒 *Time:* {$time}\n";
            $message .= "🌐 *Location:* " . ($location ?? 'Mobile App') . "\n\n";
            $message .= "_System generated notice._";

            $this->telegram->sendMessage($user->telegram_chat_id, $message);
        }
    }

    public function myAttendance(Request $request)
    {
        // Get attendance for the logged-in user's linked employee profile
        $user = $request->user();
        if (!$user->employee_id) {
            return response()->json(['message' => 'User not linked to an employee profile'], 400);
        }

        $employee = Employee::where('employee_id', $user->employee_id)->first();
        if (!$employee) {
            return response()->json(['message' => 'Employee profile not found'], 404);
        }

        $attendances = Attendance::where('employee_id', $employee->id)
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();

        return response()->json(['data' => $attendances]);
    }

    public function verifyFace(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'face_image' => 'required|image'
        ]);
        
        // In a real scenario, we send this image to the Python Microservice
        // e.g., Http::attach('image', ...)->post('http://localhost:5000/verify')
        
        return response()->json(['message' => 'Face verified successfully (Mock)']);
    }
    public function todayAttendance(Request $request)
    {
        $user = $request->user();
        $query = Attendance::with('employee')
            ->whereDate('date', today());

        // If not super admin, filter by company
        if ($user->role !== 'Super Admin') {
            $query->where('company_id', $user->company_id);
        }

        $attendances = $query->orderBy('check_in', 'desc')->get();

        return response()->json(['data' => $attendances]);
    }
}
