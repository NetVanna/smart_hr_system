<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class JobPosting extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'category',
        'type',
        'location',
        'salary_range',
        'status',
        'closing_date'
    ];

    public function applicants()
    {
        return $this->hasMany(Applicant::class);
    }
}
