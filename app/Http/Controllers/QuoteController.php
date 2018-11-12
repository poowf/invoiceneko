<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use App\Library\Poowf\Unicorn;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Http\Request;

use Log;
use PDF;
use Uuid;
use Carbon\Carbon;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = auth()->user()->company;
        $quotes = $company->quotes()->notarchived()->with(['client'])->get();

        return view('pages.quote.index', compact('quotes'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_archived()
    {
        $company = auth()->user()->company;
        $quotes = $company->quotes()->archived()->with(['client'])->get();

        return view('pages.quote.index_archived', compact('quotes'));
    }

    /**
     * Set the Quote to Archived
     *
     * @param Quote $quote
     * @return \Illuminate\Http\Response
     */
    public function archive(Quote $quote)
    {
        $quote->archived = true;
        $quote->save();
        flash('Quote has been archived successfully', "success");

        return redirect()->route('quote.show', [ 'quote' => $quote->id ]);
    }

    /**
     * Set the Quote Share Token
     *
     * @param Quote $quote
     * @return \Illuminate\Http\Response
     */
    public function share(Quote $quote)
    {
        $token = Uuid::generate(4);
        $quote->share_token = $token;
        $quote->save();

        return $token;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function showwithtoken(Request $request)
    {
        $token = $request->input('token');
        $quote = Quote::where('share_token', $token)->first();
        abort_unless($quote, 404);

        $pdf = $quote->generatePDFView();
        return $pdf->inline(str_slug($quote->nice_quote_id) . '.pdf');
    }

    public function duplicate(Quote $quote)
    {
        $duplicatedQuote = $quote->duplicate();
        flash('Quote has been Cloned Sucessfully', "success");
        return redirect()->route('quote.show', ['quote' => $duplicatedQuote->id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = auth()->user()->company;
        $clients = $company->clients;
        $itemtemplates = $company->itemtemplates;

        if($company)
        {
            $quotenumber = $company->nicequoteid();

            if ($company->clients->count() == 0)
            {
                return view('pages.quote.noclients');
            }
            else
            {
                return view('pages.quote.create', compact('company', 'quotenumber', 'clients', 'itemtemplates'));
            }
        }
        else
        {
            return view('pages.quote.nocompany');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateQuoteRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateQuoteRequest $request)
    {
        $company = auth()->user()->company;

        $quote = new Quote;
        $quote->nice_quote_id = $company->nicequoteid();
        $quote->fill($request->all());
        $quote->client_id = $request->input('client_id');
        $quote->company_id = $company->id;
        $quote->save();

        foreach($request->input('item_name') as $key => $item)
        {
            $quoteitem = new QuoteItem;
            $quoteitem->name = $item;
            $quoteitem->description = $request->input('item_description')[$key];
            $quoteitem->quantity   = $request->input('item_quantity')[$key];
            $quoteitem->price = $request->input('item_price')[$key];
            $quoteitem->quote_id = $quote->id;
            $quoteitem->save();
        }

        $quote->setQuoteTotal();

        flash('Quote Created', 'success');

        return redirect()->route('quote.show', [ 'quote' => $quote->id ]);
    }

    /**
     * @param Quote $quote
     * @return \Illuminate\Http\RedirectResponse
     */
    public function convertToInvoice(Quote $quote)
    {
        $company = auth()->user()->company;

        $invoice = new Invoice;
        $invoice->nice_invoice_id = $company->niceinvoiceid();
        $duedate = Carbon::now()->addDays($quote->netdays)->startOfDay()->toDateTimeString();
        $invoice->date = Carbon::now()->startOfDay()->toDateTimeString();
        $invoice->netdays = $quote->netdays;
        $invoice->duedate = $duedate;
        $invoice->client_id = $quote->client_id;
        $invoice->company_id = $company->id;
        $invoice->notify = false;
        $invoice->save();

        foreach($quote->items as $key => $item)
        {
            $invoiceitem = new InvoiceItem;
            $invoiceitem->name = $item->name;
            $invoiceitem->description = $item->description;
            $invoiceitem->quantity   = $item->quantity;
            $invoiceitem->price = $item->price;
            $invoiceitem->invoice_id = $invoice->id;
            $invoiceitem->save();
        }

        $invoice->setInvoiceTotal();

        $quote->status = Quote::STATUS_COMPLETED;
        $quote->archived = true;
        $quote->save();

        flash('Invoice Created', 'success');

        return redirect()->route('invoice.show', [ 'invoice' => $invoice->id ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function show(Quote $quote)
    {
        $client = $quote->client;

        return view('pages.quote.show', compact('quote', 'client'));
    }

    /**
     * Display the print version specified resource.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function printview(Quote $quote)
    {
        $pdf = $quote->generatePDFView();

        return $pdf->inline(str_slug($quote->nice_quote_id) . 'quote.pdf');
    }

    /**
     * Download the specified resource.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function download(Quote $quote)
    {
        $pdf = $quote->generatePDFView();

        return $pdf->download(str_slug($quote->nice_quote_id) . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function edit(Quote $quote)
    {
        $company = auth()->user()->company;
        $clients = $company->clients;

        return view('pages.quote.edit', compact('quote', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateQuoteRequest $request
     * @param  \App\Models\Quote $quote
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuoteRequest $request, Quote $quote)
    {
        $quote->fill($request->all());
        $quote->save();

        foreach($request->input('item_name') as $key => $itemname)
        {
            if (isset($request->input('item_id')[$key]))
            {
                $quoteitem = QuoteItem::find($request->input('item_id')[$key]);
                if($quoteitem->quote_id != $quote->id)
                {
                    continue;
                }
            }
            else
            {
                $quoteitem = new QuoteItem;
            }
            $quoteitem->name = $itemname;
            $quoteitem->description = $request->input('item_description')[$key];
            $quoteitem->quantity   = $request->input('item_quantity')[$key];
            $quoteitem->price = $request->input('item_price')[$key];
            $quoteitem->quote_id = $quote->id;
            $quoteitem->save();
        }

        $quote = $quote->fresh();
        $quote->setQuoteTotal();

        flash('Quote Updated', 'success');

        return redirect()->route('quote.show', [ 'quote' => $quote->id ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quote $quote
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Quote $quote)
    {
        $quote->delete();

        flash('Quote Deleted', 'success');

        return redirect()->back();
    }
}