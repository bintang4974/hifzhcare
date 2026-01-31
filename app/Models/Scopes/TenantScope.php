<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        // Skip for super admin - they can see all data
        if ($user->hasRole('Super Admin')) {
            return;
        }

        // Skip for general users - they only see their own data (handled by user_id filter)
        if ($user->user_type === 'general') {
            return;
        }

        // Apply tenant filter for pesantren users
        if ($user->pesantren_id) {
            $builder->where($model->getTable() . '.pesantren_id', $user->pesantren_id);
        }
    }

    /**
     * Extend the query builder with additional methods.
     */
    public function extend(Builder $builder): void
    {
        // You can add custom methods here if needed
        $builder->macro('withoutTenant', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });
    }
}
