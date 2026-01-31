<?php

namespace App;

use App\Models\AuditLog;

trait Auditable
{
    /**
     * Boot the trait.
     */
    protected static function bootAuditable(): void
    {
        // Log when created
        static::created(function ($model) {
            $model->logAudit('created');
        });
        
        // Log when updated
        static::updated(function ($model) {
            $model->logAudit('updated', $model->getOriginal(), $model->getAttributes());
        });
        
        // Log when deleted
        static::deleted(function ($model) {
            $model->logAudit('deleted', $model->getAttributes());
        });
    }
    
    /**
     * Log audit entry.
     */
    protected function logAudit(string $event, ?array $oldValues = null, ?array $newValues = null): void
    {
        // Dispatch audit log job (async)
        CreateAuditLogJob::dispatch(
            pesantrenId: $this->pesantren_id ?? null,
            userId: auth()->id(),
            event: $event,
            auditableType: get_class($this),
            auditableId: $this->id,
            oldValues: $oldValues,
            newValues: $newValues,
            ipAddress: request()->ip(),
            userAgent: request()->userAgent()
        );
    }
    
    /**
     * Get all audit logs for this model.
     */
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
