<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\OldInvoiceItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OldInvoiceItemPolicy
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
        return $user->can('view-invoice', OldInvoiceItem::class);
    }

    /**
     * Determine whether the user can view the oldInvoiceItem.
     *
     * @param \App\Models\User           $user
     * @param \App\Models\OldInvoiceItem $oldInvoiceItem
     *
     * @return mixed
     */
    public function view(User $user, OldInvoiceItem $oldInvoiceItem)
    {
        $userCompanies = $user->companies()->pluck('companies.id');
        return $userCompanies->contains($oldInvoiceItem->invoice->company_id) && $user->can('view-invoice', Invoice::class);
    }

    /**
     * Determine whether the user can create oldInvoiceItems.
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
     * Determine whether the user can update the oldInvoiceItem.
     *
     * @param \App\Models\User           $user
     * @param \App\Models\OldInvoiceItem $oldInvoiceItem
     *
     * @return mixed
     */
    public function update(User $user, OldInvoiceItem $oldInvoiceItem)
    {
        $userCompanies = $user->companies()->pluck('companies.id');
        return $userCompanies->contains($oldInvoiceItem->invoice->company_id) && $user->can('update-invoice', Invoice::class);
    }

    /**
     * Determine whether the user can delete the oldInvoiceItem.
     *
     * @param \App\Models\User           $user
     * @param \App\Models\OldInvoiceItem $oldInvoiceItem
     *
     * @return mixed
     */
    public function delete(User $user, OldInvoiceItem $oldInvoiceItem)
    {
        $userCompanies = $user->companies()->pluck('companies.id');
        return $userCompanies->contains($oldInvoiceItem->invoice->company_id) && $user->can('delete-invoice', Invoice::class);
    }
}
