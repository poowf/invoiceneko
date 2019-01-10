<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\OldInvoice;
use Illuminate\Http\Request;
use PDF;

class OldInvoiceController extends Controller
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
     * @param Company                $company
     * @param \App\Models\OldInvoice $invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, OldInvoice $invoice)
    {
        $client = $invoice->getClient();

        return view('pages.oldinvoice.show', compact('invoice', 'client'));
    }

    /**
     * Display the print version specified resource.
     *
     * @param Company                $company
     * @param \App\Models\OldInvoice $invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function printview(Company $company, OldInvoice $invoice)
    {
        $pdf = $invoice->generatePDFView();

        return $pdf->inline(str_slug($invoice->nice_invoice_id.' - '.$invoice->created_at).'.pdf');
    }

    /**
     * Download the specified resource.
     *
     * @param Company                $company
     * @param \App\Models\OldInvoice $invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function download(Company $company, OldInvoice $invoice)
    {
        $pdf = $invoice->generatePDFView();

        return $pdf->download(str_slug($invoice->nice_invoice_id.' - '.$invoice->created_at).'.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company                $company
     * @param \App\Models\OldInvoice $invoice
     *
     * @return void
     */
    public function edit(Company $company, OldInvoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Company                  $company
     * @param \App\Models\OldInvoice   $invoice
     *
     * @return void
     */
    public function update(Request $request, Company $company, OldInvoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company                $company
     * @param \App\Models\OldInvoice $invoice
     *
     * @return void
     */
    public function destroy(Company $company, OldInvoice $invoice)
    {
        //
    }
}
