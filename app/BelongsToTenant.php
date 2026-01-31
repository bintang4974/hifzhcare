<?php

namespace App;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    /**
     * Boot the trait.
     */
    protected static function bootBelongsToTenant(): void
    {
        // Add global scope to auto-filter by tenant
        static::addGlobalScope(new TenantScope);
        
        // Auto-set pesantren_id when creating
        static::creating(function ($model) {
            if (is_null($model->pesantren_id) && auth()->check()) {
                $user = auth()->user();
                
                // Skip for general users (pesantren_id should remain null)
                if ($user->user_type !== 'general') {
                    $model->pesantren_id = $user->pesantren_id;
                }
            }
        });
    }
    
    /**
     * Get records without tenant scope (use carefully!)
     */
    public static function withoutTenantScope()
    {
        return static::withoutGlobalScope(TenantScope::class);
    }
    
    /**
     * Scope to specific tenant
     */
    public function scopeForTenant(Builder $query, int $pesantrenId)
    {
        return $query->where($this->getTable() . '.pesantren_id', $pesantrenId);
    }
}
