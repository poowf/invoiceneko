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
     * @param  \App\Models\OldInvoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(OldInvoice $invoice)
    {
        $client = $invoice->client;
        return view('pages.oldinvoice.show', compact('invoice', 'client'));
    }

    /**
     * Display the print version specified resource.
     *
     * @param \App\Models\OldInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function printview(OldInvoice $invoice)
    {
        $pdf = $invoice->generatePDFView();
        return $pdf->inline(str_slug($invoice->nice_invoice_id . ' - ' . $invoice->created_at) . 'test.pdf');
    }

    /**
     * Download the specified resource.
     *
     * @param \App\Models\OldInvoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function download(OldInvoice $invoice)
    {
        $pdf = $invoice->generatePDFView();
        return $pdf->download(str_slug($invoice->nice_invoice_id . ' - ' . $invoice->created_at) . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\OldInvoice $invoice
     * @return void
     */
    public function edit(OldInvoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\OldInvoice $invoice
     * @return void
     */
    public function update(Request $request, OldInvoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OldInvoice $invoice
     * @return void
     */
    public function destroy(OldInvoice $invoice)
    {
        //
    }
}
