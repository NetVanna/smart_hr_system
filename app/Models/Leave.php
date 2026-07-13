<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;

class Leave extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'employee_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'attachment',
        'status'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
