<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Library\Poowf\Unicorn;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceEvent;
use App\Models\InvoiceItemTemplate;
use App\Models\InvoiceTemplate;
use App\Models\OldInvoice;
use App\Models\OldInvoiceItem;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Notifications\InvoiceNotification;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PragmaRX\Countries\Package\Countries;
use Log;
use PDF;
use Uuid;
use Carbon\Carbon;
use Recurr\Rule;
use Recurr\Frequency;

class InvoiceController extends Controller
{
    public function __construct(){
        $this->countries = new Countries();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = auth()->user()->company;
        $overdue = $company->invoices()->with(['client'])->overdue()->notarchived()->get();
        $pending = $company->invoices()->with(['client'])->pending()->notarchived()->get();
        $draft = $company->invoices()->with(['client'])->draft()->notarchived()->get();
        $paid = $company->invoices()->with(['client'])->paid()->notarchived()->get();

        return view('pages.invoice.index', compact('overdue', 'pending', 'draft', 'paid'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_archived()
    {
        $company = auth()->user()->company;
        $invoices = $company->invoices()->archived()->with(['client'])->get();

        return view('pages.invoice.index_archived', compact('invoices'));
    }


    /**
     * Set the Invoice to Archived
     *
     * @param Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function archive(Invoice $invoice)
    {
        $invoice->archived = true;
        $invoice->save();
        flash('Invoice has been archived successfully', "success");

        return redirect()->route('invoice.show', [ 'invoice' => $invoice->id ]);
    }

    /**
     * Set the Invoice to Written Off
     *
     * @param Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function writeoff(Invoice $invoice)
    {
        $invoice->status = Invoice::STATUS_WRITTENOFF;
        $invoice->save();

        return redirect()->route('invoice.show', [ 'invoice' => $invoice->id ]);
    }

    /**
     * Set the Invoice Share Token
     *
     * @param Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function share(Invoice $invoice)
    {
        $token = $invoice->generateShareToken(true);

        return $token;
    }

    /**
     * Send Invoice Notification
     *
     * @param Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function sendnotification(Invoice $invoice)
    {
        $invoice->notify(new InvoiceNotification($invoice));
        flash('An email notification has been sent to the client', "success");

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function showwithtoken(Request $request)
    {
        $token = $request->input('token');
        $invoice = Invoice::where('share_token', $token)->first();
        abort_unless($invoice, 404);

        $pdf = $invoice->generatePDFView();
        return $pdf->inline(str_slug($invoice->nice_invoice_id) . '.pdf');
    }

    public function duplicate(Invoice $invoice)
    {
        $duplicatedInvoice = $invoice->duplicate();
        flash('Invoice has been Cloned Sucessfully', "success");
        return redirect()->route('invoice.show', ['invoice' => $duplicatedInvoice->id]);
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
            $invoicenumber = $company->niceinvoiceid();

            if ($company->clients->count() == 0)
            {
                return view('pages.invoice.noclients');
            }
            else
            {
                return view('pages.invoice.create', compact('company', 'invoicenumber', 'clients', 'itemtemplates'));
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
     * @param CreateInvoiceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateInvoiceRequest $request)
    {
        $company = auth()->user()->company;

        $invoice = new Invoice;
        $invoice->nice_invoice_id = $company->niceinvoiceid();
        $duedate = Carbon::createFromFormat('j F, Y', $request->input('date'))->addDays($request->input('netdays'))->startOfDay()->toDateTimeString();
        $invoice->date = Carbon::createFromFormat('j F, Y', $request->input('date'))->startOfDay()->toDateTimeString();
        $invoice->netdays = $request->input('netdays');
        $invoice->duedate = $duedate;
        $invoice->client_id = $request->input('client_id');
        $invoice->company_id = $company->id;
        $invoice->notify = $request->has('notify') ? true : false;
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

        if($request->has('recurring-invoice-check'))
        {
            if($request->input('recurring-invoice-check') === 'on')
            {
                //$repeatsEveryInterval is the number of times the event needs to occur in a time period
                //$repeatsEveryTimePeriod is the time period in which an event needs to occur (day, week, month, year)
                //$repeatUntilOption is the duration of which the event needs to occur until
                //--never option is forever
                //--occurence option is how many occurences for it to occur till the event stops
                //--date option is until a specific date
                $repeatsEveryInterval = $request->input('recurring-time-interval');
                $repeatsEveryTimePeriod = $request->input('recurring-time-period');
                $repeatUntilOption = $request->input('recurring-until');
                $repeatUntilMeta = null;

                switch($repeatUntilOption)
                {
                    case 'occurence':
                        $numberOfOccurences = $request->input('recurring-until-occurence-number');
                        $repeatUntilMeta = $numberOfOccurences;
                        break;
                    case 'date':
                        $repeatUntilMeta = Carbon::createFromFormat('j F, Y', $request->input('recurring-until-date-value'))->startOfDay()->toDateTimeString();
                        break;
                }

                $startDate = Carbon::now();
                $timezone = new DateTimeZone('Asia/Singapore');
                $rruleString = Unicorn::generateRrule($startDate, $timezone, $repeatsEveryInterval, $repeatsEveryTimePeriod, $repeatUntilOption, $repeatUntilMeta);

                $invoiceEvent = new InvoiceEvent;
                $invoiceEvent->time_interval = $repeatsEveryInterval;
                $invoiceEvent->time_period = $repeatsEveryTimePeriod;
                $invoiceEvent->until_type = $repeatUntilOption;
                $invoiceEvent->until_meta = $repeatUntilMeta;
                $invoiceEvent->rule = $rruleString;
                $invoiceEvent->company_id = $invoice->company_id;
                $invoiceEvent->save();

                $invoice->invoice_event_id = $invoiceEvent->id;
                $invoice->save();

                $items = $invoice->items;

                $invoiceTemplate = new InvoiceTemplate;
                $invoiceTemplate->fill($invoice->toArray());
                $invoiceTemplate->invoice_event_id = $invoiceEvent->id;
                $invoiceTemplate->save();

                foreach($items as $item)
                {
                    $invoiceItemTemplate = new InvoiceItemTemplate;
                    $invoiceItemTemplate->fill($item->toArray());
                    $invoiceItemTemplate->invoice_template_id = $invoiceTemplate->id;
                    $invoiceItemTemplate->save();
                }
            }
        }

        flash('Invoice Created', 'success');

        return redirect()->route('invoice.show', [ 'invoice' => $invoice->id ]);
    }

    /**
     * @param Invoice $invoice
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function convertToQuote(Invoice $invoice)
    {
        $company = auth()->user()->company;

        $quote = new Quote;
        $quote->nice_quote_id = $company->nicequoteid();
        $quote->date = $invoice->date;
        $quote->netdays = $invoice->netdays;
        $quote->duedate = $invoice->duedate;
        $quote->client_id = $invoice->client_id;
        $quote->company_id = $company->id;
        $quote->status = Quote::STATUS_DRAFT;
        $quote->save();

        foreach($invoice->items as $key => $item)
        {
            $quoteitem = new QuoteItem;
            $quoteitem->name = $item->name;
            $quoteitem->description = $item->description;
            $quoteitem->quantity   = $item->quantity;
            $quoteitem->price = $item->price;
            $quoteitem->quote_id = $quote->id;
            $quoteitem->save();
        }

        $quote->setQuoteTotal();

        $invoice->delete();

        flash('Quote Created', 'success');

        return redirect()->route('quote.show', [ 'quote' => $quote->id ]);
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
        $histories = $invoice->history()->orderBy('updated_at', 'desc')->get();
        $payments = $invoice->payments;
        $event = $invoice->event;
        $siblings = $invoice->siblings();

        return view('pages.invoice.show', compact('invoice', 'event','client', 'histories', 'payments', 'siblings'));
    }

    /**
     * Display the print version specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function printview(Invoice $invoice)
    {
        $pdf = $invoice->generatePDFView();

        return $pdf->inline(str_slug($invoice->nice_invoice_id) . 'test.pdf');
    }

    /**
     * Download the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function download(Invoice $invoice)
    {
        $pdf = $invoice->generatePDFView();
        return $pdf->download(str_slug($invoice->nice_invoice_id) . '.pdf');
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
        $event = ($invoice->event) ? $invoice->event : null;

        return view('pages.invoice.edit', compact('invoice', 'clients', 'event'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateInvoiceRequest $request
     * @param  \App\Models\Invoice $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $duedate = Carbon::createFromFormat('j F, Y', $request->input('date'))->addDays($request->input('netdays'))->startOfDay()->toDateTimeString();
        $invoice->date = Carbon::createFromFormat('j F, Y', $request->input('date'))->startOfDay()->toDateTimeString();
        $invoice->netdays = $request->input('netdays');
        $invoice->duedate = $duedate;
        $invoice->notify = $request->has('notify') ? true : false;

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

        $eventExists = ($invoice->event) ? true : false;

        if ($request->has('recurring-invoice-check'))
        {

            if($request->input('recurring-invoice-check') === 'on' && $request->input('recurring-details') === 'standalone')
            {
                $invoicesCount = $invoice->event->invoices()->count();
                $invoice->invoice_event_id = null;
                $invoice->save();

                //Check if last invoice and delete if so
                if($invoicesCount == 1)
                {
                    $invoice->event->delete();
                }
            }
            elseif($request->input('recurring-invoice-check') === 'on')
            {
                //$repeatsEveryInterval is the number of times the event needs to occur in a time period
                //$repeatsEveryTimePeriod is the time period in which an event needs to occur (day, week, month, year)
                //$repeatUntilOption is the duration of which the event needs to occur until
                //--never option is forever
                //--occurence option is how many occurences for it to occur till the event stops
                //--date option is until a specific date

                $repeatsEveryInterval = $request->input('recurring-time-interval');
                $repeatsEveryTimePeriod = $request->input('recurring-time-period');
                $repeatUntilOption = $request->input('recurring-until');
                $repeatUntilMeta = null;

                switch ($repeatUntilOption) {
                    case 'occurence':
                        $numberOfOccurences = $request->input('recurring-until-occurence-number');
                        $repeatUntilMeta = $numberOfOccurences;
                        break;
                    case 'date':
                        $repeatUntilMeta = Carbon::createFromFormat('j F, Y', $request->input('recurring-until-date-value'))->startOfDay()->toDateTimeString();
                        break;
                }

                $startDate = Carbon::now();
                $timezone = new DateTimeZone('UTC');
                $rruleString = Unicorn::generateRrule($startDate, $timezone, $repeatsEveryInterval, $repeatsEveryTimePeriod, $repeatUntilOption, $repeatUntilMeta);

                $invoiceEvent = ($eventExists) ? $invoice->event : new InvoiceEvent;
                $invoiceEvent->time_interval = $repeatsEveryInterval;
                $invoiceEvent->time_period = $repeatsEveryTimePeriod;
                $invoiceEvent->until_type = $repeatUntilOption;
                $invoiceEvent->until_meta = $repeatUntilMeta;
                $invoiceEvent->rule = $rruleString;
                $invoiceEvent->company_id = $invoice->company_id;
                $invoiceEvent->save();

                $invoice->invoice_event_id = $invoiceEvent->id;
                $invoice->save();

                $items = $invoice->items;

                if ($eventExists) {
                    if($request->input('recurring-details') === 'future')
                    {
                        //TODO: If updating template, delete all generated preview invoices that are in draft status.
                        //Perhaps, it might be a better idea to just display a preview instead of generating the invoices.
                        $invoiceTemplate = $invoiceEvent->template;
                        $invoiceTemplate->fill($invoice->toArray());
                        $invoiceTemplate->save();

                        $invoiceItemTemplates = $invoiceTemplate->items;

                        foreach($invoiceItemTemplates as $invoiceItemTemplate)
                        {
                            $invoiceItemTemplate->delete();
                        }

                        foreach ($items as $item)
                        {
                            $invoiceItemTemplate = new InvoiceItemTemplate;
                            $invoiceItemTemplate->fill($item->toArray());
                            $invoiceItemTemplate->invoice_template_id = $invoiceTemplate->id;
                            $invoiceItemTemplate->save();
                        }
                    }
                } else {
                    if($request->input('recurring-details') === 'none')
                    {
                        $invoiceTemplate = new InvoiceTemplate;
                        $invoiceTemplate->fill($invoice->toArray());
                        $invoiceTemplate->invoice_event_id = $invoiceEvent->id;
                        $invoiceTemplate->save();

                        foreach ($items as $item) {
                            $invoiceItemTemplate = new InvoiceItemTemplate;
                            $invoiceItemTemplate->fill($item->toArray());
                            $invoiceItemTemplate->invoice_template_id = $invoiceTemplate->id;
                            $invoiceItemTemplate->save();
                        }
                    }

                }
            }
        }
        else
        {
            if($eventExists) : $invoice->event->delete(); endif;
        }

        flash('Invoice Updated', 'success');

        return redirect()->route('invoice.show', [ 'invoice' => $invoice->id ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice $invoice
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        flash('Invoice Deleted', 'success');

        return redirect()->back();
    }

    /**
     * @param Invoice $invoice
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history(Invoice $invoice)
    {
        $client = $invoice->client;
        $histories = $invoice->history()->orderBy('created_at', 'desc')->get();

        return view('pages.invoice.history', compact('invoice', 'client', 'histories'));
    }

    /**
     * Function to check if the invoice has any siblings
     *
     * @param Invoice $invoice
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSiblings(Invoice $invoice)
    {
        $hasSiblings = ($invoice->siblings()) ? true : false;

        return response()->json(compact('hasSiblings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adhoccreate()
    {
        $company = auth()->user()->company;

        if($company)
        {

            if ($company->clients->count() == 0)
            {
                return view('pages.invoice.noclients');
            }
            else
            {
                $invoicenumber = $company->niceinvoiceid();
                $countries = $this->countries->all();

                return view('pages.invoice.adhoccreate', compact('company', 'invoicenumber', 'countries'));
            }
        }
        else
        {
            return view('pages.invoice.nocompany');
        }
    }
}
