<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdhocQuoteRequest;
use App\Http\Requests\UpdateAdhocQuoteRequest;
use App\Models\Company;
use App\Models\Quote;
use App\Models\QuoteItem;
use PragmaRX\Countries\Package\Countries;

class AdhocQuoteController extends Controller
{
    public function __construct ()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index ()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     *
     * @return Response
     */
    public function create (Company $company)
    {
        if ($company) {
            $quotenumber = $company->nicequoteid();
            $itemtemplates = $company->itemtemplates;
            $countries = countries();

            return view('pages.quote.adhoc.create', compact('company', 'quotenumber', 'countries', 'itemtemplates'));
        } else {
            return view('pages.quote.nocompany');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAdhocQuoteRequest $request
     * @param Company                 $company
     *
     * @return Response
     */
    public function store (CreateAdhocQuoteRequest $request, Company $company)
    {
        $quote = new Quote();
        $quote->nice_quote_id = $company->nicequoteid();
        $quote->fill($request->all());
        $quote->company_id = $company->id;

        $client = [
            'companyname'  => $request->input('companyname'),
            'country_code' => $request->input('country_code'),
            'block'        => $request->input('block'),
            'street'       => $request->input('street'),
            'unitnumber'   => $request->input('unitnumber'),
            'postalcode'   => $request->input('postalcode'),
        ];
        $quote->client_data = json_encode($client);

        $quote->save();

        foreach ($request->input('item_name') as $key => $item) {
            $quoteitem = new QuoteItem();
            $quoteitem->name = $item;
            $quoteitem->description = $request->input('item_description')[$key];
            $quoteitem->quantity = $request->input('item_quantity')[$key];
            $quoteitem->price = $request->input('item_price')[$key];
            $quoteitem->quote_id = $quote->id;
            $quoteitem->save();
        }

        $quote->setQuoteTotal();

        flash('Quote Created', 'success');

        return redirect()->route('quote.show', ['quote' => $quote, 'company' => $company]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show ($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit (Company $company, Quote $quote)
    {
        $client = $quote->getClient();
        $countries = countries();
        $itemtemplates = $company->itemtemplates;

        return view('pages.quote.adhoc.edit', compact('quote', 'client', 'countries', 'itemtemplates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdhocQuoteRequest $request
     * @param Company                 $company
     * @param Quote                   $quote
     *
     * @return Response
     */
    public function update (UpdateAdhocQuoteRequest $request, Company $company, Quote $quote)
    {
        $quote->fill($request->all());

        $client = [
            'companyname'  => $request->input('companyname'),
            'country_code' => $request->input('country_code'),
            'block'        => $request->input('block'),
            'street'       => $request->input('street'),
            'unitnumber'   => $request->input('unitnumber'),
            'postalcode'   => $request->input('postalcode'),
        ];

        $quote->client_data = json_encode($client);

        $quote->save();

        foreach ($request->input('item_name') as $key => $itemname) {
            if (isset($request->input('item_id')[$key])) {
                $quoteitem = QuoteItem::find($request->input('item_id')[$key]);
                if ($quoteitem->quote_id != $quote->id) {
                    continue;
                }
            } else {
                $quoteitem = new QuoteItem();
            }
            $quoteitem->name = $itemname;
            $quoteitem->description = $request->input('item_description')[$key];
            $quoteitem->quantity = $request->input('item_quantity')[$key];
            $quoteitem->price = $request->input('item_price')[$key];
            $quoteitem->quote_id = $quote->id;
            $quoteitem->save();
        }

        $quote = $quote->fresh();
        $quote->setQuoteTotal();

        flash('Quote Updated', 'success');

        return redirect()->route('quote.show', ['quote' => $quote, 'company' => $company]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy ($id)
    {
        //
    }
}
