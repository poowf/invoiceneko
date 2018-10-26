<?php

namespace App\Http\Controllers;

use App\Models\OldInvoice;
use Illuminate\Http\Request;

use PDF;
use Carbon\Carbon;

class OldInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OldInvoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(OldInvoice $invoice)
    {
        $client = $invoice->client;
        $invoice->date = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('j F, Y');
        $invoice->duedate = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->duedate)->format('j F, Y');

        return view('pages.oldinvoice.show', compact('invoice', 'client'));
    }

    /**
     * Display the print version specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function printview(OldInvoice $invoice)
    {
        $invoice->date = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('j F, Y');
        $invoice->duedate = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->duedate)->format('j F, Y');

        $pdf = $invoice->generatePDFView();
        return $pdf->inline(str_slug($invoice->nice_invoice_id . ' - ' . $invoice->created_at) . 'test.pdf');
    }

    /**
     * Download the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function download(OldInvoice $invoice)
    {
        $invoice->date = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date)->format('j F, Y');
        $invoice->duedate = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->duedate)->format('j F, Y');

        $pdf = $invoice->generatePDFView();
        return $pdf->download(str_slug($invoice->nice_invoice_id . ' - ' . $invoice->created_at) . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OldInvoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(OldInvoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\OldInvoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OldInvoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OldInvoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(OldInvoice $invoice)
    {
        //
    }
}
