<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Http\Requests\CreateSoloPaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Company $company)
    {
        $payments = $company
            ->payments()
            ->with(['invoice', 'client'])
            ->get();

        return view('pages.payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Company $company)
    {
        if ($company) {
            if ($company->invoices->count() <= 0) {
                return view('pages.payment.noinvoices');
            } else {
                return view('pages.payment.create');
            }
        } else {
            return view('pages.invoice.nocompany');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePaymentRequest $request
     * @param Company              $company
     * @param Invoice              $invoice
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePaymentRequest $request, Company $company, Invoice $invoice)
    {
        $payment = new Payment();
        $payment->fill($request->all());
        $payment->receiveddate = Carbon::createFromFormat('j F, Y', $request->input('receiveddate'))->startOfDay();
        $payment->invoice_id = $invoice->id;
        $payment->client_id = $invoice->client_id;
        $payment->company_id = $invoice->company_id;
        $payment->save();

        $invoice = $invoice->fresh();

        if ($invoice->calculateremainder() <= '0.0') {
            $invoice->payment_complete_date = Carbon::now();
            $invoice->status = Invoice::STATUS_CLOSED;
            $invoice->save();
        }

        flash('Payment Created', 'success');

        return redirect()->route('invoice.show', ['invoice' => $invoice, 'company' => $company]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function createsolo(Company $company)
    {
        if ($company) {
            $invoices = $company->invoices;

            if ($invoices->count() <= 0) {
                return view('pages.payment.noinvoices');
            } else {
                return view('pages.payment.createsolo', compact('invoices'));
            }
        } else {
            return view('pages.invoice.nocompany');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSoloPaymentRequest $request
     * @param Company                  $company
     *
     * @return \Illuminate\Http\Response
     */
    public function storesolo(CreateSoloPaymentRequest $request, Company $company)
    {
        $payment = new Payment();
        $payment->fill($request->all());
        $payment->receiveddate = Carbon::createFromFormat('j F, Y', $request->input('receiveddate'))->startOfDay();

        $invoice = Invoice::find($request->input('invoice_id'));

        if ($invoice->company_id != $company->id) {
            flash('You do not have the authorisation to create a payment for the selected invoice', 'error');

            return redirect()->back();
        }

        $payment->invoice_id = $request->input('invoice_id');
        $payment->client_id = $invoice->client_id;
        $payment->company_id = $invoice->company_id;
        $payment->save();

        $invoice = $invoice->fresh();

        if ($invoice->calculateremainder() <= '0.0') {
            $invoice->status = Invoice::STATUS_CLOSED;
            $invoice->save();
        }

        flash('Payment Created', 'success');

        return redirect()->route('payment.index', ['company' => $company]);
    }

    /**
     * Display the specified resource.
     *
     * @param Company             $company
     * @param \App\Models\Payment $payment
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, Payment $payment)
    {
        return view('pages.payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company             $company
     * @param \App\Models\Payment $payment
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company, Payment $payment)
    {
        return view('pages.payment.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePaymentRequest $request
     * @param Company              $company
     * @param \App\Models\Payment  $payment
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentRequest $request, Company $company, Payment $payment)
    {
        $payment->fill($request->all());
        $payment->receiveddate = Carbon::createFromFormat('j F, Y', $request->input('receiveddate'))->startOfDay();
        $payment->save();

        $invoice = $payment->invoice;

        if ($invoice->calculateremainder() <= '0.0') {
            $invoice->status = Invoice::STATUS_CLOSED;
            $invoice->save();
        }

        flash('Payment Updated', 'success');

        return redirect()->route('payment.index', ['company' => $company]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company             $company
     * @param \App\Models\Payment $payment
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company, Payment $payment)
    {
        $payment->delete();

        flash('Payment Deleted', 'success');

        return redirect()->route('payment.index', ['company' => $company]);
    }
}
