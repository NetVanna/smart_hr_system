<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\SubscriptionRenewalReminder;
use Carbon\Carbon;

class CheckSubscriptionExpiry extends Command
{
    protected $signature = 'app:check-subscription-expiry';
    protected $description = 'Check for subscriptions expiring soon and notify company admins';

    public function handle()
    {
        $intervals = [7, 3, 1]; // Days before expiry to notify

        foreach ($intervals as $days) {
            $expiryDate = Carbon::today()->addDays($days);
            
            $subscriptions = Subscription::where('status', 'Active')
                ->whereDate('end_date', $expiryDate)
                ->get();

            foreach ($subscriptions as $subscription) {
                $admins = User::where('company_id', $subscription->company_id)
                    ->where('role', 'Company Admin')
                    ->get();

                foreach ($admins as $admin) {
                    $admin->notify(new SubscriptionRenewalReminder($subscription, $days));
                }

                $this->info("Notified admins of company ID {$subscription->company_id} for expiry in {$days} days.");
            }
        }

        return Command::SUCCESS;
    }
}
