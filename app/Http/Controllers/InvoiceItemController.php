<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     * @return void
     */
    public function index(Company $company)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     * @return void
     */
    public function create(Company $company)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Company $company
     * @return void
     */
    public function store(Request $request, Company $company)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Company $company
     * @param  \App\Models\InvoiceItem $invoiceItem
     * @return void
     */
    public function show(Company $company, InvoiceItem $invoiceItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     * @param  \App\Models\InvoiceItem $invoiceItem
     * @return void
     */
    public function edit(Company $company, InvoiceItem $invoiceItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Company $company
     * @param  \App\Models\InvoiceItem $invoiceItem
     * @return void
     */
    public function update(Request $request, Company $company, InvoiceItem $invoiceItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @param  \App\Models\InvoiceItem $invoiceItem
     * @return void
     * @throws \Exception
     */
    public function destroy(Company $company, InvoiceItem $invoiceItem)
    {
        $invoice = $invoiceItem->invoice;
        if($invoice->items->count() != 1)
        {
            $invoiceItem->delete();
        }
    }
}
