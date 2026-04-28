<?php

namespace App\Traits;

use App\Models\AuditLogs;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable() 
    {
        static::created(function ($model) {
            $model->logAudit('created', null, $model->getAttributes());
        });

        static::updated(function ($model) { 
            $oldValues = array_intersect_key($model->getOriginal(), $model->getChanges());
            $newValues = $model->getChanges(); 

            if (!empty($newValues)) { 
                $model->logAudit('updated', $oldValues, $newValues);
            }
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted', $model->getAttributes(), null);
        });
    }

    /**
     * Fungsi Helper untuk menyimpan ke tabel audit_logs
     */
    protected function logAudit($event, $oldValues, $newValues)
    {
        AuditLogs::create([
            'user_id'        => Auth::id(), 
            'event'          => $event,
            'auditable_id'   => $this->id, 
            'auditable_type' => get_class($this),
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
        ]);
    }
}