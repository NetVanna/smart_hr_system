<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToCompany;

class Training extends Model
{
    use BelongsToCompany;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'category',
        'is_mandatory',
        'start_date',
        'end_date'
    ];

    public function participants()
    {
        return $this->hasMany(TrainingParticipant::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'training_participants');
    }
}
