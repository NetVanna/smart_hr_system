<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionRenewalReminder extends Notification
{
    use Queueable;

    protected $subscription;
    protected $daysRemaining;

    public function __construct($subscription, $daysRemaining)
    {
        $this->subscription = $subscription;
        $this->daysRemaining = $daysRemaining;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Subscription Renewal Reminder')
            ->line("Your subscription for {$this->subscription->plan} plan is expiring in {$this->daysRemaining} day(s).")
            ->line("Expiry Date: " . \Carbon\Carbon::parse($this->subscription->end_date)->format('d M Y'))
            ->action('Renew Now', route('subscription.payment'))
            ->line('Please renew your subscription to avoid any service interruption.');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'subscription_reminder',
            'subscription_id' => $this->subscription->id,
            'plan' => $this->subscription->plan,
            'expiry_date' => $this->subscription->end_date,
            'days_remaining' => $this->daysRemaining,
            'message' => "Your {$this->subscription->plan} subscription expires in {$this->daysRemaining} days."
        ];
    }
}
