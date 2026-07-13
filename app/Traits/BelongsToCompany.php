<?php

namespace App\Traits;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToCompany
{
    /**
     * Boot the BelongsToCompany trait for a model.
     *
     * @return void
     */
    protected static function bootBelongsToCompany()
    {
        // Global scope to always fetch records for the current user's company
        static::addGlobalScope('company', function (Builder $builder) {
            if (auth()->hasUser()) {
                $user = auth()->user();
                if ($user && $user->company_id && $user->role !== 'Super Admin') {
                    $builder->where($builder->getModel()->getTable() . '.company_id', $user->company_id);
                }
            }
        });

        // Automatically set company_id when creating new records
        static::creating(function ($model) {
            if (auth()->hasUser() && auth()->user()->company_id && !$model->company_id) {
                $model->company_id = auth()->user()->company_id;
            }
        });
    }

    /**
     * Get the company that owns the model.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
