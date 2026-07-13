<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;

class AuditLogController extends Controller
{
    public function index()
    {
        $logs = ActivityLog::with(['user', 'company'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('audit.index', compact('logs'));
    }
}
