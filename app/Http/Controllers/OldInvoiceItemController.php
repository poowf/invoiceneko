<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\OldInvoiceItem;
use Illuminate\Http\Request;

class OldInvoiceItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     *
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
     *
     * @return void
     */
    public function create(Company $company)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Company                  $company
     *
     * @return void
     */
    public function store(Request $request, Company $company)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Company                    $company
     * @param \App\Models\OldInvoiceItem $oldInvoiceItem
     *
     * @return void
     */
    public function show(Company $company, OldInvoiceItem $oldInvoiceItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company                    $company
     * @param \App\Models\OldInvoiceItem $oldInvoiceItem
     *
     * @return void
     */
    public function edit(Company $company, OldInvoiceItem $oldInvoiceItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request   $request
     * @param Company                    $company
     * @param \App\Models\OldInvoiceItem $oldInvoiceItem
     *
     * @return void
     */
    public function update(Request $request, Company $company, OldInvoiceItem $oldInvoiceItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company                    $company
     * @param \App\Models\OldInvoiceItem $oldInvoiceItem
     *
     * @return void
     */
    public function destroy(Company $company, OldInvoiceItem $oldInvoiceItem)
    {
        //
    }
}
