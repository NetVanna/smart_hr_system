<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Leave;
use App\Services\TelegramService;
use Carbon\Carbon;

class TelegramController extends Controller
{
    protected $telegram;

    public function __construct(TelegramService $telegram)
    {
        $this->telegram = $telegram;
    }

    public function handle(Request $request)
    {
        $update = $request->all();

        if (!isset($update['message'])) {
            return response()->json(['status' => 'ok']);
        }

        $message = $update['message'];
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? '';

        if ($text == '/start') {
            $this->handleStart($chatId);
        } elseif (strpos($text, '/balance') === 0) {
            $this->handleBalance($chatId);
        } else {
            $this->handleUnknown($chatId);
        }

        return response()->json(['status' => 'ok']);
    }

    protected function handleStart($chatId)
    {
        $msg = "👋 *Welcome to SmartHR Bot!*\n\n";
        $msg .= "I can notify you about your attendance and provide HR info.\n\n";
        $msg .= "Commands:\n";
        $msg .= "/balance - Check your leave balance\n";
        $msg .= "/status - Check your today's attendance";
        
        $this->telegram->sendMessage($chatId, $msg);
    }

    protected function handleBalance($chatId)
    {
        $user = User::where('telegram_chat_id', $chatId)->first();

        if (!$user) {
            $this->telegram->sendMessage($chatId, "❌ *Error:* Your Telegram account is not linked to any SmartHR user.\n\nPlease contact your HR to link your account.");
            return;
        }

        $employee = $user->employee_id ? \App\Models\Employee::where('employee_id', $user->employee_id)->first() : null;

        if (!$employee) {
            $this->telegram->sendMessage($chatId, "❌ *Error:* No employee profile found for your account.");
            return;
        }

        $totalAllowance = 18; // Standard in Cambodia for demo
        
        $usedLeaves = Leave::where('employee_id', $employee->id)
            ->where('status', 'Approved')
            ->get()
            ->sum(function($leave) {
                return Carbon::parse($leave->start_date)->diffInDays(Carbon::parse($leave->end_date)) + 1;
            });

        $balance = $totalAllowance - $usedLeaves;

        $msg = "📅 *Leave Balance Summary*\n\n";
        $msg .= "Employee: *{$employee->first_name} {$employee->last_name}*\n";
        $msg .= "Total Annual Allowance: *{$totalAllowance} days*\n";
        $msg .= "Used Leaves: *{$usedLeaves} days*\n";
        $msg .= "Current Balance: *{$balance} days*";

        $this->telegram->sendMessage($chatId, $msg);
    }

    protected function handleUnknown($chatId)
    {
        $this->telegram->sendMessage($chatId, "Sorry, I don't understand that command. Try /start for a list of available commands.");
    }
}
