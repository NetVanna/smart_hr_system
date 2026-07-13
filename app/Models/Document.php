<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'employee_id',
        'title',
        'category',
        'description',
        'file_path'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
