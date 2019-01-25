<?php

namespace App\Policies;

use App\Models\Receipt;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReceiptPolicy
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
        return $user->can('view-receipt', Receipt::class);
    }

    /**
     * Determine whether the user can view the receipt.
     *
     * @param \App\Models\User    $user
     * @param \App\Models\Receipt $receipt
     *
     * @return mixed
     */
    public function view(User $user, Receipt $receipt)
    {
        return $user->can('view-receipt', $receipt);
    }

    /**
     * Determine whether the user can create receipts.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-receipt', Receipt::class);
    }

    /**
     * Determine whether the user can update the receipt.
     *
     * @param \App\Models\User    $user
     * @param \App\Models\Receipt $receipt
     *
     * @return mixed
     */
    public function update(User $user, Receipt $receipt)
    {
        return $user->can('update-receipt', $receipt);
    }

    /**
     * Determine whether the user can delete the receipt.
     *
     * @param \App\Models\User    $user
     * @param \App\Models\Receipt $receipt
     *
     * @return mixed
     */
    public function delete(User $user, Receipt $receipt)
    {
        return $user->can('delete-receipt', $receipt);
    }
}
