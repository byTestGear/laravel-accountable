<?php

namespace ByTestGear\Accountable\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use ByTestGear\Accountable\AccountableServiceProvider;
use ByTestGear\Accountable\Observer\AccountableObserver;

trait Accountable
{
    /**
     * Boot the accountable trait for a model.
     *
     * @return void
     */
    public static function bootAccountable()
    {
        static::observe(new AccountableObserver);
    }

    /**
     * @return string
     */
    protected function accountableUserClass()
    {
        return get_class(AccountableServiceProvider::accountableUser());
    }

    /**
     * Define the created by relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdByUser()
    {
        return $this->belongsTo($this->accountableUserClass(), config('accountable.column_names.created_by'));
    }

    /**
     * Define the updated by relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedByUser()
    {
        return $this->belongsTo($this->accountableUserClass(), config('accountable.column_names.updated_by'));
    }

    /**
     * Define the deleted by relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deletedByUser()
    {
        return $this->belongsTo($this->accountableUserClass(), config('accountable.column_names.deleted_by'));
    }

    /**
     * Scope a query to only include records created by a given user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedBy(Builder $query, Model $user)
    {
        return $query->where(config('accountable.column_names.created_by'), $user->getKey());
    }

    /**
     * Scope a query to only include records created by the current logged in user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMine(Builder $query)
    {
        return $query->where(config('accountable.column_names.created_by'), auth()->id());
    }

    /**
     * Scope a query to only include records updated by a given user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpdatedBy(Builder $query, Model $user)
    {
        return $query->where(config('accountable.column_names.updated_by'), $user->getKey());
    }

    /**
     * Scope a query to only include records deleted by a given user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeletedBy(Builder $query, Model $user)
    {
        return $query->where(config('accountable.column_names.deleted_by'), $user->getKey());
    }
}
