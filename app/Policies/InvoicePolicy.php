<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
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
        return $user->can('view-invoice', Invoice::class);
    }

    /**
     * Determine whether the user can view the invoice.
     *
     * @param \App\Models\User    $user
     * @param \App\Models\Invoice $invoice
     *
     * @return mixed
     */
    public function view(User $user, Invoice $invoice)
    {
        $userCompanies = $user->companies()->pluck('companies.id');
        return $userCompanies->contains($invoice->company_id) && $user->can('view-invoice', $invoice);
    }

    /**
     * Determine whether the user can create invoices.
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
     * Determine whether the user can update the invoice.
     *
     * @param \App\Models\User    $user
     * @param \App\Models\Invoice $invoice
     *
     * @return mixed
     */
    public function update(User $user, Invoice $invoice)
    {
        $userCompanies = $user->companies()->pluck('companies.id');
        return $userCompanies->contains($invoice->company_id) && $user->can('update-invoice', $invoice);
    }

    /**
     * Determine whether the user can delete the invoice.
     *
     * @param \App\Models\User    $user
     * @param \App\Models\Invoice $invoice
     *
     * @return mixed
     */
    public function delete(User $user, Invoice $invoice)
    {
        $userCompanies = $user->companies()->pluck('companies.id');
        return $userCompanies->contains($invoice->company_id) && $user->can('delete-invoice', $invoice);
    }
}
