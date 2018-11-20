<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\QuoteItem;
use Illuminate\Http\Request;

class QuoteItemController extends Controller
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
     * @param  \App\Models\QuoteItem $quoteItem
     * @return void
     */
    public function show(Company $company, QuoteItem $quoteItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     * @param  \App\Models\QuoteItem $quoteItem
     * @return void
     */
    public function edit(Company $company, QuoteItem $quoteItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\QuoteItem $quoteItem
     * @return void
     */
    public function update(Request $request, QuoteItem $quoteItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @param  \App\Models\QuoteItem $quoteItem
     * @return void
     * @throws \Exception
     */
    public function destroy(Company $company, QuoteItem $quoteItem)
    {
        $quote = $quoteItem->quote;
        if($quote->items->count() != 1)
        {
            $quoteItem->delete();
        }
    }
}
