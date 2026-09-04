<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;

class Employee extends Model
{
    use BelongsToCompany, \App\Traits\LogsActivity;

    protected $fillable = [
        'company_id',
        'employee_id',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'phone',
        'email',
        'address',
        'department_id',
        'branch_id',
        'position',
        'joining_date',
        'salary',
        'status',
        'profile_photo',
        'face_image',
        'authorized_device_id',
        'emergency_contact_name',
        'emergency_contact_phone'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function shiftAssignments()
    {
        return $this->hasMany(ShiftAssignment::class);
    }

    public function trainings()
    {
        return $this->belongsToMany(Training::class, 'training_participants', 'employee_id', 'training_id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'employee_skills')
                    ->withPivot('proficiency_level')
                    ->withTimestamps();
    }

    public function evaluations()
    {
        return $this->hasMany(PerformanceEvaluation::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
