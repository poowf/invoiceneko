<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Quote;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotePolicy
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

    public function index(User $user)
    {
        return $user->can('view-quote');
    }

    /**
     * Determine whether the user can view the quote.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quote  $quote
     * @return mixed
     */
    public function view(User $user, Quote $quote)
    {
        return $user->can('view-quote');
    }

    /**
     * Determine whether the user can create quotes.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-quote');
    }

    /**
     * Determine whether the user can update the quote.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quote  $quote
     * @return mixed
     */
    public function update(User $user, Quote $quote)
    {
        return $user->can('update-quote');
    }

    /**
     * Determine whether the user can delete the quote.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Quote  $quote
     * @return mixed
     */
    public function delete(User $user, Quote $quote)
    {
        return $user->can('delete-quote');
    }
}