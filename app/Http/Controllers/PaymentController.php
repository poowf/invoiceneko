<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Http\Requests\CreateSoloPaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Library\Poowf\Unicorn;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = auth()->user()->company;
        $payments = $company->payments()->with(['invoice', 'client'])->get();

        return view('pages.payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = auth()->user()->company;

        if($company)
        {
            if ($company->invoices->count() == 0)
            {
                return view('pages.payment.noinvoices');
            }
            else
            {
                return view('pages.payment.create');
            }
        }
        else
        {
            return view('pages.invoice.nocompany');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePaymentRequest $request, Invoice $invoice)
    {
        $payment = new Payment;
        $payment->fill($request->all());
        $payment->receiveddate = Carbon::createFromFormat('j F, Y', $request->input('receiveddate'))->startOfDay()->toDateTimeString();
        $payment->invoice_id = $invoice->id;
        $payment->client_id = $invoice->client->id;
        $payment->company_id = $invoice->company_id;
        $payment->save();

        $invoice = $invoice->fresh();

        if($invoice->calculateremainder() == "0.0")
        {
            $invoice->status = Invoice::STATUS_CLOSED;
            $invoice->save();
        }

        flash('Payment Created', 'success');

        return redirect()->route('invoice.show', ['invoice' => $invoice->id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createsolo()
    {
        $company = auth()->user()->company;

        $invoices = $company->invoices;

        return view('pages.payment.createsolo', compact('invoices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storesolo(CreateSoloPaymentRequest $request)
    {
        $payment = new Payment;
        $payment->fill($request->all());
        $payment->receiveddate = Carbon::createFromFormat('j F, Y', $request->input('receiveddate'))->startOfDay()->toDateTimeString();

        $invoice = Invoice::find($request->input('invoice_id'));

        $payment->invoice_id = $request->input('invoice_id');
        $payment->client_id = $invoice->client_id;
        $payment->company_id = $invoice->company_id;
        $payment->save();

        $invoice = $invoice->fresh();

        if($invoice->calculateremainder() == "0.0")
        {
            $invoice->status = Invoice::STATUS_CLOSED;
            $invoice->save();
        }

        flash('Payment Created', 'success');

        return redirect()->route('payment.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        return view('pages.payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        return view('pages.payment.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->fill($request->all());
        $payment->receiveddate = Carbon::createFromFormat('j F, Y', $request->input('receiveddate'))->startOfDay()->toDateTimeString();
        $payment->save();

        flash('Payment Updated', 'success');

        return redirect()->route('payment.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        flash('Payment Deleted', 'success');

        return redirect()->back();
    }
}
