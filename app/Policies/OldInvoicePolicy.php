<?php

namespace App\Policies;

use App\Models\User;
use App\Models\OldInvoice;
use Illuminate\Auth\Access\HandlesAuthorization;

class OldInvoicePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
    }

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the oldInvoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OldInvoice  $oldInvoice
     * @return mixed
     */
    public function view(User $user, OldInvoice $oldInvoice)
    {
        //
    }

    /**
     * Determine whether the user can create oldInvoices.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the oldInvoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OldInvoice  $oldInvoice
     * @return mixed
     */
    public function update(User $user, OldInvoice $oldInvoice)
    {
        //
    }

    /**
     * Determine whether the user can delete the oldInvoice.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\OldInvoice  $oldInvoice
     * @return mixed
     */
    public function delete(User $user, OldInvoice $oldInvoice)
    {
        //
    }
}
