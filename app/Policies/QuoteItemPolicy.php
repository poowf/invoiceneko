<?php

namespace App\Policies;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuoteItemPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
    }

    public function before($user, $ability)
    {
    }

    public function index(User $user)
    {
        return $user->can('view-quote', QuoteItem::class);
    }

    /**
     * Determine whether the user can view the quoteItem.
     *
     * @param \App\Models\User      $user
     * @param \App\Models\QuoteItem $quoteItem
     *
     * @return mixed
     */
    public function view(User $user, QuoteItem $quoteItem)
    {
        $userCompanies = $user->companies()->pluck('companies.id');
        return $userCompanies->contains($quoteItem->quote->company_id) && $user->can('view-quote', Quote::class);
    }

    /**
     * Determine whether the user can create quoteItems.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-quote', Quote::class);
    }

    /**
     * Determine whether the user can update the quoteItem.
     *
     * @param \App\Models\User      $user
     * @param \App\Models\QuoteItem $quoteItem
     *
     * @return mixed
     */
    public function update(User $user, QuoteItem $quoteItem)
    {
        $userCompanies = $user->companies()->pluck('companies.id');
        return $userCompanies->contains($quoteItem->quote->company_id) && $user->can('update-quote', Quote::class);
    }

    /**
     * Determine whether the user can delete the quoteItem.
     *
     * @param \App\Models\User      $user
     * @param \App\Models\QuoteItem $quoteItem
     *
     * @return mixed
     */
    public function delete(User $user, QuoteItem $quoteItem)
    {
        $userCompanies = $user->companies()->pluck('companies.id');
        return $userCompanies->contains($quoteItem->quote->company_id) && $user->can('delete-quote', Quote::class);
    }
}
