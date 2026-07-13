<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\ShiftAssignment;
use App\Models\User;
use App\Services\TelegramService;
use Carbon\Carbon;

class SendTelegramDailySummary extends Command
{
    protected $signature = 'telegram:daily-summary';
    protected $description = 'Send daily attendance summary to company managers via Telegram';

    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        parent::__construct();
        $this->telegram = $telegram;
    }

    public function handle()
    {
        $today = Carbon::today();
        $companies = Company::all();

        foreach ($companies as $company) {
            $this->sendSummaryForCompany($company, $today);
        }

        $this->info('Daily summaries sent successfully.');
    }

    protected function sendSummaryForCompany($company, $date)
    {
        $dateStr = $date->toDateString();
        
        // 1. Get all shift assignments for today in this company
        $assignments = ShiftAssignment::whereHas('employee', function($q) use ($company) {
            $q->where('company_id', $company->id);
        })->where('date', $dateStr)->with(['employee', 'shift'])->get();

        if ($assignments->isEmpty()) return;

        $lates = 0;
        $absences = 0;
        $present = 0;

        foreach ($assignments as $assignment) {
            $attendance = Attendance::where('employee_id', $assignment->employee_id)
                ->whereDate('date', $dateStr)
                ->first();

            if (!$attendance) {
                $absences++;
            } else {
                $present++;
                $startTime = Carbon::parse($assignment->shift->start_time);
                $checkInTime = Carbon::parse($attendance->check_in);
                
                // Consider late if checked in 15 minutes after shift start
                if ($checkInTime->greaterThan($startTime->addMinutes(15))) {
                    $lates++;
                }
            }
        }

        // 2. Find managers to notify
        $managers = User::where('company_id', $company->id)
            ->whereIn('role', ['Company Admin', 'HR Manager'])
            ->whereNotNull('telegram_chat_id')
            ->get();

        if ($managers->isEmpty()) return;

        $message = "📊 *Daily Attendance Summary*\n";
        $message .= "Date: *{$dateStr}*\n\n";
        $message .= "✅ Present: *{$present}*\n";
        $message .= "⚠️ Late: *{$lates}*\n";
        $message .= "❌ Absent: *{$absences}*\n\n";
        $message .= "Check the dashboard for details.";

        foreach ($managers as $manager) {
            $this->telegram->sendMessage($manager->telegram_chat_id, $message);
        }
    }
}
