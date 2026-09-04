<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'phone',
        'address',
        'latitude',
        'longitude',
        'geofence_radius',
        'is_active',
    ];

    protected $casts = [
        'latitude'        => 'float',
        'longitude'       => 'float',
        'geofence_radius' => 'float',
        'is_active'       => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
