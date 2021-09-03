<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\Receipt;
use Illuminate\Http\Request;

class ReceiptController extends Controller
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
        $receipts = $company->receipts;

        return view('pages.receipt.index', compact('receipts'));
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
     * @param Company             $company
     * @param \App\Models\Receipt $receipt
     *
     * @return void
     */
    public function show(Company $company, Receipt $receipt)
    {
        $receipt = $receipt;
        $invoice = $receipt->invoice;

        return view('pages.receipt.show', compact('receipt', 'invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company             $company
     * @param \App\Models\Receipt $receipt
     *
     * @return void
     */
    public function edit(Company $company, Receipt $receipt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Company                  $company
     * @param \App\Models\Receipt      $receipt
     *
     * @return void
     */
    public function update(Request $request, Company $company, Receipt $receipt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company             $company
     * @param \App\Models\Receipt $receipt
     *
     * @return void
     */
    public function destroy(Company $company, Receipt $receipt)
    {
        //
    }

    public function generate(Company $company, Invoice $invoice)
    {
        if (! $invoice->receipt) {
            $receipt = new Receipt();
            $receipt->nice_receipt_id = $company->niceReceiptID();
            $receipt->company_id = $company->id;
            $invoice->receipt()->save($receipt);
        }

        $invoice->refresh();

        return redirect()->route('receipt.show', ['company' => $company, 'receipt' => $invoice->receipt]);
    }

    /**
     * Display the print version specified resource.
     *
     * @param Company             $company
     * @param \App\Models\Receipt $receipt
     *
     * @return Response
     */
    public function printview(Company $company, Receipt $receipt)
    {
        $pdf = $receipt->generatePDFView();

        return $pdf->inline(Str::slug($receipt->nice_receipt_id).'.pdf');
    }

    /**
     * Download the specified resource.
     *
     * @param Company             $company
     * @param \App\Models\Receipt $receipt
     *
     * @return Response
     */
    public function download(Company $company, Receipt $receipt)
    {
        $pdf = $receipt->generatePDFView();

        return $pdf->download(Str::slug($receipt->nice_receipt_id).'.pdf');
    }
}
