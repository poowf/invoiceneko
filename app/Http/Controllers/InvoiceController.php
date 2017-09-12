<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

use PDF;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = auth()->user()->company;
        $invoices = $company->invoices;

        return view('pages.invoice.index', compact('invoices'));
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
        $invoicenumber = $company->invoices()->count();
        $invoicenumber = sprintf('%06d', ++$invoicenumber);

        if ($company->clients->count() == 0)
        {
            return view('pages.invoice.noclients');
        }
        else
        {
            return view('pages.invoice.create', compact('company', 'invoicenumber', 'clients'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $invoice = new Invoice;
        $invoice->invoiceid = $request->input('invoiceid');
        $duedate = Carbon::createFromFormat('j F, Y', $request->input('date'))->addDays($request->input('netdays'));
        $invoice->date = Carbon::createFromFormat('j F, Y', $request->input('date'))->toDateTimeString();
        $invoice->netdays = $request->input('netdays');
        $invoice->duedate = $duedate;
        $invoice->client_id = $request->input('client_id');
        $invoice->company_id = auth()->user()->company_id;
        $invoice->save();

        foreach($request->input('item_name') as $key => $item)
        {
            $invoiceitem = new InvoiceItem;
            $invoiceitem->name = $item;
            $invoiceitem->description = $request->input('item_description')[$key];
            $invoiceitem->quantity   = $request->input('item_quantity')[$key];
            $invoiceitem->price = $request->input('item_price')[$key];
            $invoiceitem->invoice_id = $invoice->id;
            $invoiceitem->save();
        }

        flash('Invoice Created', 'success');

        return redirect()->route('invoice.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $client = $invoice->client;
        $invoice->date = Carbon::createFromFormat('Y-m-d H:i:s',$invoice->date)->format('j F, Y');
        $invoice->duedate = Carbon::createFromFormat('Y-m-d H:i:s',$invoice->duedate)->format('j F, Y');

        return view('pages.invoice.show', compact('invoice', 'client'));
    }

    /**
     * Display the print version specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function printview(Invoice $invoice)
    {
        $invoice->date = Carbon::createFromFormat('Y-m-d H:i:s',$invoice->date)->format('j F, Y');
        $invoice->duedate = Carbon::createFromFormat('Y-m-d H:i:s',$invoice->duedate)->format('j F, Y');

        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
        return $pdf->inline(str_slug($invoice->invoiceid) . 'test.pdf');
    }

    /**
     * Download the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function download(Invoice $invoice)
    {
        $invoice->date = Carbon::createFromFormat('Y-m-d H:i:s',$invoice->date)->format('j F, Y');
        $invoice->duedate = Carbon::createFromFormat('Y-m-d H:i:s',$invoice->duedate)->format('j F, Y');

        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
        return $pdf->download(str_slug($invoice->invoiceid) . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        $company = auth()->user()->company;
        $clients = $company->clients;

        return view('pages.invoice.edit', compact('invoice', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        $duedate = Carbon::createFromFormat('j F, Y', $request->input('date'))->addDays($request->input('netdays'));
        $invoice->date = Carbon::createFromFormat('j F, Y', $request->input('date'))->toDateTimeString();
        $invoice->netdays = $request->input('netdays');
        $invoice->duedate = $duedate;
        $invoice->save();


        //Need to rewrite this to check for id instead of force deleting.
        $items = $invoice->items;

        foreach($items as $item)
        {
            $item->delete();
        }


        foreach($request->input('item_name') as $key => $item)
        {
            $invoiceitem = new InvoiceItem;
            $invoiceitem->name = $item;
            $invoiceitem->description = $request->input('item_description')[$key];
            $invoiceitem->quantity   = $request->input('item_quantity')[$key];
            $invoiceitem->price = $request->input('item_price')[$key];
            $invoiceitem->invoice_id = $invoice->id;
            $invoiceitem->save();
        }

        flash('Invoice Updated', 'success');

        return redirect()->route('invoice.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        flash('Invoice Deleted', 'success');

        return redirect()->back();
    }
}
