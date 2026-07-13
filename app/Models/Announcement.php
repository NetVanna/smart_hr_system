<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\BelongsToCompany;

class Announcement extends Model
{
    use BelongsToCompany;

    protected $fillable = ['company_id', 'title', 'content', 'target_audience'];
}
