<?php

namespace App\Models\Traits;

use App\Models\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Builder;

trait HasBranchScope
{
    /**
     * Boot the trait and add the global scope
     */
    protected static function bootHasBranchScope(): void
    {
        static::addGlobalScope(new BranchScope());
    }

    /**
     * Query without branch scope (admin bypass)
     */
    public function scopeWithoutBranchScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope(BranchScope::class);
    }

    /**
     * Query for a specific branch
     */
    public function scopeForBranch(Builder $query, int $branchId): Builder
    {
        return $query->withoutGlobalScope(BranchScope::class)
            ->where('branch_id', $branchId);
    }

    /**
     * Query for all branches (admin use)
     */
    public function scopeAllBranches(Builder $query): Builder
    {
        return $query->withoutGlobalScope(BranchScope::class);
    }
}
