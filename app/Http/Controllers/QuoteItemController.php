<?php

namespace App\Http\Controllers;

use App\Models\QuoteItem;
use Illuminate\Http\Request;

class QuoteItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuoteItem $quoteItem
     * @return void
     */
    public function show(QuoteItem $quoteItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuoteItem $quoteItem
     * @return void
     */
    public function edit(QuoteItem $quoteItem)
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
     * @param  \App\Models\QuoteItem $quoteItem
     * @return void
     * @throws \Exception
     */
    public function destroy(QuoteItem $quoteItem)
    {
        $quote = $quoteItem->quote;
        if($quote->items->count() != 1)
        {
            $quoteItem->delete();
        }
    }
}
