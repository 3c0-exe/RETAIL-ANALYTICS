<?php
namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class BranchScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Only apply if user is logged in and is a branch manager
        if (Auth::check() && Auth::user()->role === 'branch_manager') {
            $builder->where($model->getTable() . '.branch_id', Auth::user()->branch_id);
        }
    }
}
