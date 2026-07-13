<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory, \App\Traits\LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'base_currency',
        'exchange_rate',
        'latitude',
        'longitude',
        'geofence_radius',
        'subscription_plan',
        'subscription_status',
        'onboarding_step',
        'telegram_chat_id'
    ];

    public function users()
    {
        return $this->hasMany(\App\Models\User::class);
    }
}
