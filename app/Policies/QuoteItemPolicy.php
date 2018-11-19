<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuoteItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuoteItemPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
    }

    public function before($user, $ability)
    {
        if ($user->isAn('global-administrator')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the quoteItem.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\QuoteItem  $quoteItem
     * @return mixed
     */
    public function view(User $user, QuoteItem $quoteItem)
    {
        return $user->can('view-quote');
    }

    /**
     * Determine whether the user can create quoteItems.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-quote');
    }

    /**
     * Determine whether the user can update the quoteItem.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\QuoteItem  $quoteItem
     * @return mixed
     */
    public function update(User $user, QuoteItem $quoteItem)
    {
        return $user->can('update-quote');
    }

    /**
     * Determine whether the user can delete the quoteItem.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\QuoteItem  $quoteItem
     * @return mixed
     */
    public function delete(User $user, QuoteItem $quoteItem)
    {
        return $user->can('delete-quote');
    }
}
