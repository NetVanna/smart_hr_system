<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;

class Payroll extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'employee_id',
        'basic_salary',
        'allowance',
        'deductions',
        'net_salary',
        'month',
        'year'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
