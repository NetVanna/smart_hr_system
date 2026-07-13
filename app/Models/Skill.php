<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class Skill extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'name',
        'category'
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_skills')
                    ->withPivot('proficiency_level')
                    ->withTimestamps();
    }
}
