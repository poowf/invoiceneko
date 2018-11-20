<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
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
        return $user->can('view-payment', Payment::class);
    }

    /**
     * Determine whether the user can view the payment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payment  $payment
     * @return mixed
     */
    public function view(User $user, Payment $payment)
    {
        return $user->can('view-payment', $payment);
    }

    /**
     * Determine whether the user can create payments.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-payment', Payment::class);
    }

    /**
     * Determine whether the user can update the payment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payment  $payment
     * @return mixed
     */
    public function update(User $user, Payment $payment)
    {
        return $user->can('update-payment', $payment);
    }

    /**
     * Determine whether the user can delete the payment.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payment  $payment
     * @return mixed
     */
    public function delete(User $user, Payment $payment)
    {
        return $user->can('delete-payment', $payment);
    }
}
