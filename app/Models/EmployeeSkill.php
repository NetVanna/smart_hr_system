<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSkill extends Model
{
    protected $fillable = [
        'employee_id',
        'skill_id',
        'proficiency_level'
    ];

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
