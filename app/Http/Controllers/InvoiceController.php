<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\OldInvoice;
use App\Models\OldInvoiceItem;
use Illuminate\Http\Request;

use Log;
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
        $duedate = Carbon::createFromFormat('j F, Y', $request->input('date'))->addDays($request->input('netdays'))->startOfDay()->toDateTimeString();
        $invoice->date = Carbon::createFromFormat('j F, Y', $request->input('date'))->startOfDay()->toDateTimeString();
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
        $invoice->date = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('j F, Y');
        $invoice->duedate = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->duedate)->format('j F, Y');
        $histories = $invoice->history()->orderBy('created_at', 'desc')->get();

        return view('pages.invoice.show', compact('invoice', 'client', 'histories'));
    }

    /**
     * Display the print version specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function printview(Invoice $invoice)
    {
        $invoice->date = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('j F, Y');
        $invoice->duedate = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->duedate)->format('j F, Y');

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
        $invoice->date = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('j F, Y');
        $invoice->duedate = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->duedate)->format('j F, Y');

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
        $duedate = Carbon::createFromFormat('j F, Y', $request->input('date'))->addDays($request->input('netdays'))->startOfDay()->toDateTimeString();
        $invoice->date = Carbon::createFromFormat('j F, Y', $request->input('date'))->startOfDay()->toDateTimeString();
        $invoice->netdays = $request->input('netdays');
        $invoice->duedate = $duedate;

        $ismodified = false;


        foreach($request->input('item_id') as $key => $itemid)
        {
            $invoiceitem = InvoiceItem::find($itemid);
            $ismodified = $invoiceitem->modified($request->input('item_name')[$key], $request->input('item_description')[$key], $request->input('item_quantity')[$key], $request->input('item_price')[$key]);

            if ($ismodified)
            {
                break;
            }
        }


        if($invoice->isDirty() || $ismodified){
            $originalinvoice = $invoice->getOriginal();
            $originalitems = $invoice->items;

            $oldinvoice = new OldInvoice;
            $oldinvoice->fill($originalinvoice);

            $invoice->history()->save($oldinvoice);

            foreach($originalitems as $item)
            {
                $oldinvoiceitem = new OldInvoiceItem;
                $oldinvoiceitem->fill($item->toArray());
                $oldinvoiceitem->old_invoice_id = $oldinvoice->id;
                $oldinvoiceitem->save();
            }
        }

        $invoice->save();

        foreach($request->input('item_name') as $key => $itemname)
        {
            if (isset($request->input('item_id')[$key]))
            {
                $invoiceitem = InvoiceItem::find($request->input('item_id')[$key]);
            }
            else
            {
                $invoiceitem = new InvoiceItem;
            }
            $invoiceitem->name = $itemname;
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

    public function history(Invoice $invoice)
    {
        $client = $invoice->client;
        $invoice->date = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('j F, Y');
        $invoice->duedate = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->duedate)->format('j F, Y');
        $histories = $invoice->history()->orderBy('created_at', 'desc')->get();

        return view('pages.invoice.history', compact('invoice', 'client', 'histories'));
    }
}
