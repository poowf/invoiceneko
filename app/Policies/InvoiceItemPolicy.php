<?php

namespace App\Policies;

use App\Models\User;
use App\Models\InvoiceItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoiceItemPolicy
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
     * Determine whether the user can view the invoiceItem.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InvoiceItem  $invoiceItem
     * @return mixed
     */
    public function view(User $user, InvoiceItem $invoiceItem)
    {
        return $user->can('view-invoice');
    }

    /**
     * Determine whether the user can create invoiceItems.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-invoice');
    }

    /**
     * Determine whether the user can update the invoiceItem.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InvoiceItem  $invoiceItem
     * @return mixed
     */
    public function update(User $user, InvoiceItem $invoiceItem)
    {
        return $user->can('update-invoice');
    }

    /**
     * Determine whether the user can delete the invoiceItem.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\InvoiceItem  $invoiceItem
     * @return mixed
     */
    public function delete(User $user, InvoiceItem $invoiceItem)
    {
        return $user->can('delete-invoice');
    }
}
