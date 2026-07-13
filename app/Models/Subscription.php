<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;

class Subscription extends Model
{
    use BelongsToCompany, \App\Traits\LogsActivity;

    protected $fillable = [
        'company_id',
        'plan',
        'price',
        'start_date',
        'end_date',
        'status',
        'receipt_path'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
