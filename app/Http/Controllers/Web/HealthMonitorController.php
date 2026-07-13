<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HealthMonitorController extends Controller
{
    public function index()
    {
        // Database Health
        $dbStatus = 'Healthy';
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $dbStatus = 'Unhealthy';
        }

        // Storage Health
        $storageStatus = Storage::disk('public')->exists('.') ? 'Healthy' : 'Unhealthy';

        // Simulated Server Load (for demo)
        $cpuLoad = rand(10, 60);
        $memoryUsage = rand(30, 80);

        return view('health.index', compact('dbStatus', 'storageStatus', 'cpuLoad', 'memoryUsage'));
    }
}
