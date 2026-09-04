<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;

class Attendance extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'branch_id',
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'method',
        'location',
        'latitude',
        'longitude',
        'within_geofence',
        'verification_photo',
        'is_fake_gps',
        'device_id',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
