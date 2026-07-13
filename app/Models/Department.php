<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;

class Department extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'department_name',
        'description'
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
