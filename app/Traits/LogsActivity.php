<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Boot the trait and hook into Eloquent events.
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('Created');
        });

        static::updated(function ($model) {
            $model->logActivity('Updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('Deleted');
        });
    }

    /**
     * Record the activity in the database.
     */
    protected function logActivity(string $action)
    {
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'company_id'  => Auth::check() ? Auth::user()->company_id : $this->company_id,
            'action'      => $action,
            'model_type'  => get_class($this),
            'model_id'    => $this->id,
            'old_values'  => $action === 'Updated' ? $this->getOriginal() : null,
            'new_values'  => $action !== 'Deleted' ? $this->toArray() : null,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}
