<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdhocInvoiceRequest;
use App\Http\Requests\UpdateAdhocInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Company;
use App\Models\OldInvoice;
use App\Models\OldInvoiceItem;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use PragmaRX\Countries\Package\Countries;

class AdhocInvoiceController extends Controller
{

    public function __construct(){

    }

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
     * @param Company $company
     * @return Response
     */
    public function create(Company $company)
    {
        if($company)
        {
            $invoicenumber = $company->niceinvoiceid();
            $itemtemplates = $company->itemtemplates;
            $countries = countries();

            return view('pages.invoice.adhoc.create', compact('company', 'invoicenumber', 'countries', 'itemtemplates'));
        }
        else
        {
            return view('pages.invoice.nocompany');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateAdhocInvoiceRequest $request
     * @param Company $company
     * @return Response
     */
    public function store(CreateAdhocInvoiceRequest $request, Company $company)
    {
        $invoice = new Invoice;
        $invoice->nice_invoice_id = $company->niceinvoiceid();
        $invoice->fill($request->all());
        $invoice->company_id = $company->id;
        $invoice->notify = $request->has('notify') ? true : false;

        $client = [
            'companyname' => $request->input('companyname'),
            'country_code' => $request->input('country_code'),
            'block' => $request->input('block'),
            'street' => $request->input('street'),
            'unitnumber' => $request->input('unitnumber'),
            'postalcode' => $request->input('postalcode')
        ];
        $invoice->client_data = json_encode($client);

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

        $invoice->setInvoiceTotal();

        flash('Invoice Created', 'success');

        return redirect()->route('invoice.show', [ 'invoice' => $invoice, 'company' => $company ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company, Invoice $invoice)
    {
        if($invoice->isLocked())
        {
            flash('More than 120 days has passed since the invoice has been completed, the invoice is now locked', 'error');

            return redirect()->route('invoice.show', [ 'invoice' => $invoice, 'company' => $company ]);
        }

        $client = $invoice->getClient();
        $countries = countries();

        return view('pages.invoice.adhoc.edit', compact('invoice', 'client', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateAdhocInvoiceRequest $request
     * @param Company $company
     * @param Invoice $invoice
     * @return Response
     */
    public function update(UpdateAdhocInvoiceRequest $request, Company $company, Invoice $invoice)
    {
        if($invoice->isLocked())
        {
            flash('More than 120 days has passed since the invoice has been completed, the invoice is now locked', 'error');

            return redirect()->route('invoice.show', [ 'invoice' => $invoice, 'company' => $company ]);
        }

        $invoice->fill($request->all());
        $invoice->notify = $request->has('notify') ? true : false;

        $ismodified = false;

        foreach($request->input('item_id') as $key => $itemid)
        {
            $invoiceitem = InvoiceItem::find($itemid);
            $ismodified = $invoiceitem->modified(
                $request->input('item_name')[$key],
                $request->input('item_description')[$key],
                $request->input('item_quantity')[$key],
                $request->input('item_price')[$key]
            );

            if ($ismodified)
            {
                break;
            }
        }

        if(count($request->input('item_name')) != count($request->input('item_id')))
        {
            $ismodified = true;
        }

        if($invoice->isDirty() || $ismodified){
            $originalinvoice = $invoice->getOriginal();
            $originalitems = $invoice->items;

            $oldinvoice = new OldInvoice;
            $oldinvoice->fill($originalinvoice);

            $oldinvoice->created_at = $originalinvoice['created_at'];
            $oldinvoice->updated_at = $originalinvoice['updated_at'];

            $invoice->history()->save($oldinvoice);
            $invoice->touch();

            foreach($originalitems as $item)
            {
                $oldinvoiceitem = new OldInvoiceItem;
                $oldinvoiceitem->fill($item->toArray());
                $oldinvoiceitem->old_invoice_id = $oldinvoice->id;
                $oldinvoiceitem->save();
            }
        }

        $client = [
            'companyname' => $request->input('companyname'),
            'country_code' => $request->input('country_code'),
            'block' => $request->input('block'),
            'street' => $request->input('street'),
            'unitnumber' => $request->input('unitnumber'),
            'postalcode' => $request->input('postalcode')
        ];

        $invoice->client_data = json_encode($client);

        $invoice->save();

        foreach($request->input('item_name') as $key => $itemname)
        {
            if (isset($request->input('item_id')[$key]))
            {
                //TODO: Validate the invoice item belongs to the invoice/company, need to do authentication here.
                $invoiceitem = InvoiceItem::find($request->input('item_id')[$key]);
                if($invoiceitem->invoice_id != $invoice->id)
                {
                    continue;
                }
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

        $invoice = $invoice->fresh();
        $invoice->setInvoiceTotal();

        flash('Invoice Updated', 'success');

        return redirect()->route('invoice.show', [ 'invoice' => $invoice, 'company' => $company ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
