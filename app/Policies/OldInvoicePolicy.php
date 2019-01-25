<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\OldInvoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OldInvoicePolicy
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
        return $user->can('view-invoice', OldInvoice::class);
    }

    /**
     * Determine whether the user can view the oldInvoice.
     *
     * @param \App\Models\User       $user
     * @param \App\Models\OldInvoice $oldInvoice
     *
     * @return mixed
     */
    public function view(User $user, OldInvoice $oldInvoice)
    {
        return $user->can('view-invoice', Invoice::class);
    }

    /**
     * Determine whether the user can create oldInvoices.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-invoice', Invoice::class);
    }

    /**
     * Determine whether the user can update the oldInvoice.
     *
     * @param \App\Models\User       $user
     * @param \App\Models\OldInvoice $oldInvoice
     *
     * @return mixed
     */
    public function update(User $user, OldInvoice $oldInvoice)
    {
        return $user->can('update-invoice', Invoice::class);
    }

    /**
     * Determine whether the user can delete the oldInvoice.
     *
     * @param \App\Models\User       $user
     * @param \App\Models\OldInvoice $oldInvoice
     *
     * @return mixed
     */
    public function delete(User $user, OldInvoice $oldInvoice)
    {
        return $user->can('delete-invoice', Invoice::class);
    }
}
