<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    public function index(Request $request)
    {
        $query = Attendance::with('employee');
        
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('date', $request->date);
        } else {
            $query->whereDate('date', today());
        }

        $attendances = $query->orderBy('created_at', 'desc')->get();

        return view('attendances.index', compact('attendances'));
    }

    public function showQr(Employee $employee)
    {
        return view('employees.qr', compact('employee'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_fake_gps' => 'nullable|boolean',
            'device_id' => 'nullable|string',
            'photo' => 'nullable|image|max:5120', // Verification photo
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $company = $employee->company;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        // Device Verification (Phase 18.3)
        if ($request->device_id) {
            if (!$employee->authorized_device_id) {
                $employee->authorized_device_id = $request->device_id;
                $employee->save();
            } elseif ($employee->authorized_device_id !== $request->device_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance failed: This device is not authorized for this employee.'
                ], 403);
            }
        }

        $withinGeofence = true;
        $branchId = $employee->branch_id;
        $targetLat = null;
        $targetLng = null;
        $targetRadius = 100;

        // Prioritize employee's assigned branch GPS if active and configured
        if ($employee->branch && $employee->branch->is_active && $employee->branch->latitude && $employee->branch->longitude) {
            $targetLat = $employee->branch->latitude;
            $targetLng = $employee->branch->longitude;
            $targetRadius = $employee->branch->geofence_radius ?? 100;
        } elseif ($company->latitude && $company->longitude) {
            // Fallback to company central GPS
            $targetLat = $company->latitude;
            $targetLng = $company->longitude;
            $targetRadius = $company->geofence_radius ?? 100;
        }

        if ($latitude && $longitude && $targetLat && $targetLng) {
            $distance = $this->calculateDistance(
                $latitude, $longitude,
                $targetLat, $targetLng
            );
            $withinGeofence = $distance <= $targetRadius;
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('attendance_photos', 'public');
        }

        $existing = Attendance::where('employee_id', $employee->id)->whereDate('date', today())->first();
        
        $attendance = Attendance::updateOrCreate(
            [
                'employee_id' => $employee->id,
                'date' => today(),
                'company_id' => $employee->company_id,
            ],
            [
                'branch_id' => $branchId,
                'check_in' => $existing ? $existing->check_in : now(),
                'check_out' => $existing ? now() : null,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'within_geofence' => $withinGeofence,
                'is_fake_gps' => $request->is_fake_gps ?? false,
                'device_id' => $request->device_id,
                'verification_photo' => $photoPath ?? ($existing->verification_photo ?? null),
                'method' => 'App',
            ]
        );

        // Send Telegram Alert (Phase 17.3)
        if ($company->telegram_chat_id) {
            $status = $existing ? "Checked Out" : "Checked In";
            $geofenceStatus = $withinGeofence ? "✅ Within Range" : "❌ OUT OF RANGE";
            $branchInfo = $employee->branch ? "🏢 Branch: {$employee->branch->name}\n" : "";
            $message = "🔔 *Attendance Alert*\n\n" .
                       "👤 Employee: {$employee->first_name} {$employee->last_name}\n" .
                       $branchInfo .
                       "📍 Status: {$status}\n" .
                       "🎯 Geofence: {$geofenceStatus}\n" .
                       "📱 Method: App Verification\n" .
                       "⏰ Time: " . now()->format('H:i:s');
            
            if ($attendance->is_fake_gps) {
                $message .= "\n\n⚠️ *WARNING*: Fake GPS Detected!";
            }

            $this->telegram->sendMessage($company->telegram_chat_id, $message);
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance logged successfully',
            'within_geofence' => $withinGeofence,
            'is_fake_gps' => $attendance->is_fake_gps
        ]);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
