<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Library\Poowf\Unicorn;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceItemTemplate;
use App\Models\InvoiceRecurrence;
use App\Models\InvoiceTemplate;
use App\Models\OldInvoice;
use App\Models\OldInvoiceItem;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Notifications\InvoiceNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PDF;

class InvoiceController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     *
     * @return Response
     */
    public function index(Company $company)
    {
        $overdue = $company->invoices()->with(['client'])->overdue()->notarchived()->get();
        $pending = $company->invoices()->with(['client'])->pending()->notarchived()->get();
        $draft = $company->invoices()->with(['client'])->draft()->notarchived()->get();
        $paid = $company->invoices()->with(['client'])->paid()->notarchived()->get();

        return view('pages.invoice.index', compact('overdue', 'pending', 'draft', 'paid'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     *
     * @return Response
     */
    public function index_archived(Company $company)
    {
        $invoices = $company->invoices()->archived()->with(['client'])->get();

        return view('pages.invoice.index_archived', compact('invoices'));
    }

    /**
     * Set the Invoice to Archived.
     *
     * @param Company $company
     * @param Invoice $invoice
     *
     * @return Response
     */
    public function archive(Company $company, Invoice $invoice)
    {
        $invoice->archived = true;
        $invoice->save();
        flash('Invoice has been archived successfully', 'success');

        return redirect()->route('invoice.show', ['invoice' => $invoice, 'company' => $company]);
    }

    /**
     * Set the Invoice to Written Off.
     *
     * @param Company $company
     *
     * @return void
     */
    public function writeoff(Company $company, Invoice $invoice)
    {
        $invoice->status = Invoice::STATUS_WRITTENOFF;
        $invoice->save();

        return redirect()->route('invoice.show', ['invoice' => $invoice, 'company' => $company]);
    }

    /**
     * Set the Invoice Share Token.
     *
     * @param Company $company
     * @param Invoice $invoice
     *
     * @return Response
     */
    public function share(Company $company, Invoice $invoice)
    {
        $token = $invoice->generateShareToken(true);

        return $token;
    }

    /**
     * Send Invoice Notification.
     *
     * @param Company $company
     * @param Invoice $invoice
     *
     * @return Response
     */
    public function sendnotification(Company $company, Invoice $invoice)
    {
        $invoice->notify(new InvoiceNotification($invoice));
        flash('An email notification has been sent to the client', 'success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param Company $company
     *
     * @return mixed
     */
    public function showwithtoken(Request $request, Company $company)
    {
        $token = $request->input('token');
        $invoice = Invoice::where('share_token', $token)->first();
        abort_unless($invoice, 404);

        $pdf = $invoice->generatePDFView();

        return $pdf->inline(str_slug($invoice->nice_invoice_id) . '.pdf');
    }

    /**
     * @param Company $company
     * @param Invoice $invoice
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate(Company $company, Invoice $invoice)
    {
        $duplicatedInvoice = $invoice->duplicate();
        flash('Invoice has been Duplicated Sucessfully', 'success');

        return redirect()->route('invoice.show', ['invoice' => $duplicatedInvoice, 'company' => $company]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     *
     * @return Response
     */
    public function create(Company $company)
    {
        if ($company) {
            $invoicenumber = $company->niceinvoiceid();
            $clients = $company->clients;
            $itemtemplates = $company->itemtemplates;

            if ($company->clients->count() == 0) {
                return view('pages.invoice.noclients');
            } else {
                return view('pages.invoice.create', compact('company', 'invoicenumber', 'clients', 'itemtemplates'));
            }
        } else {
            return view('pages.invoice.nocompany');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateInvoiceRequest $request
     * @param Company              $company
     *
     * @throws \Recurr\Exception\InvalidArgument
     * @throws \Recurr\Exception\InvalidRRule
     *
     * @return Response
     */
    public function store(CreateInvoiceRequest $request, Company $company)
    {
        $invoice = new Invoice();
        $invoice->nice_invoice_id = $company->niceinvoiceid();
        $invoice->fill($request->all());
        $invoice->client_id = $request->input('client_id');
        $invoice->company_id = $company->id;
        $invoice->notify = $request->has('notify') ? 1 : 0;
        $invoice->save();

        foreach ($request->input('item_name') as $key => $item) {
            $invoiceitem = new InvoiceItem();
            $invoiceitem->name = $item;
            $invoiceitem->description = $request->input('item_description')[$key];
            $invoiceitem->quantity = $request->input('item_quantity')[$key];
            $invoiceitem->price = $request->input('item_price')[$key];
            $invoiceitem->invoice_id = $invoice->id;
            $invoiceitem->save();
        }

        $invoice->setInvoiceTotal();

        if ($request->has('recurring-invoice-check')) {
            if ($request->input('recurring-invoice-check') === 'on') {
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

                $startDate = $invoice->date;
                $timezone = config('app.timezone');
                $rruleString = Unicorn::generateRrule($startDate, $timezone, $repeatsEveryInterval, $repeatsEveryTimePeriod, $repeatUntilOption, $repeatUntilMeta);

                $invoiceRecurrence = new InvoiceRecurrence();
                $invoiceRecurrence->time_interval = $repeatsEveryInterval;
                $invoiceRecurrence->time_period = $repeatsEveryTimePeriod;
                $invoiceRecurrence->until_type = $repeatUntilOption;
                $invoiceRecurrence->until_meta = $repeatUntilMeta;
                $invoiceRecurrence->rule = $rruleString;
                $invoiceRecurrence->company_id = $invoice->company_id;
                $invoiceRecurrence->save();

                $invoice->invoice_recurrence_id = $invoiceRecurrence->id;
                $invoice->save();

                $items = $invoice->items;

                $invoiceTemplate = new InvoiceTemplate();
                $invoiceTemplate->fill($invoice->toArray());
                $invoiceTemplate->invoice_recurrence_id = $invoiceRecurrence->id;
                $invoiceTemplate->save();

                foreach ($items as $item) {
                    $invoiceItemTemplate = new InvoiceItemTemplate();
                    $invoiceItemTemplate->fill($item->toArray());
                    $invoiceItemTemplate->invoice_template_id = $invoiceTemplate->id;
                    $invoiceItemTemplate->save();
                }
            }
        }

        flash('Invoice Created', 'success');

        return redirect()->route('invoice.show', ['invoice' => $invoice, 'company' => $company]);
    }

    /**
     * @param Company $company
     * @param Invoice $invoice
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function convertToQuote(Company $company, Invoice $invoice)
    {
        $quote = new Quote();
        $quote->nice_quote_id = $company->nicequoteid();
        $quote->date = $invoice->date;
        $quote->netdays = $invoice->netdays;
        $quote->duedate = $invoice->duedate;
        $quote->client_id = $invoice->client_id;
        $quote->company_id = $company->id;
        $quote->status = Quote::STATUS_DRAFT;
        $quote->save();

        foreach ($invoice->items as $key => $item) {
            $quoteitem = new QuoteItem();
            $quoteitem->name = $item->name;
            $quoteitem->description = $item->description;
            $quoteitem->quantity = $item->quantity;
            $quoteitem->price = $item->price;
            $quoteitem->quote_id = $quote->id;
            $quoteitem->save();
        }

        $quote->setQuoteTotal();

        $invoice->delete();

        flash('Quote Created', 'success');

        return redirect()->route('quote.show', ['quote' => $quote, 'company' => $company]);
    }

    /**
     * Display the specified resource.
     *
     * @param Company             $company
     * @param \App\Models\Invoice $invoice
     *
     * @return Response
     */
    public function show(Company $company, Invoice $invoice)
    {
        $client = $invoice->getClient();
        $histories = $invoice->history()->orderBy('updated_at', 'desc')->get();
        $payments = $invoice->payments;
        $recurrence = $invoice->recurrence;
        $siblings = $invoice->siblings();
        $notifications = $invoice->notifications;

        return view('pages.invoice.show', compact('invoice', 'recurrence', 'client', 'histories', 'payments', 'siblings', 'notifications'));
    }

    /**
     * Display the print version specified resource.
     *
     * @param Company             $company
     * @param \App\Models\Invoice $invoice
     *
     * @return Response
     */
    public function printview(Company $company, Invoice $invoice)
    {
        $pdf = $invoice->generatePDFView();

        return $pdf->inline(str_slug($invoice->nice_invoice_id) . '.pdf');
    }

    /**
     * Download the specified resource.
     *
     * @param Company             $company
     * @param \App\Models\Invoice $invoice
     *
     * @return Response
     */
    public function download(Company $company, Invoice $invoice)
    {
        $pdf = $invoice->generatePDFView();

        return $pdf->download(str_slug($invoice->nice_invoice_id) . '.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company             $company
     * @param \App\Models\Invoice $invoice
     *
     * @return Response
     */
    public function edit(Company $company, Invoice $invoice)
    {
        if ($invoice->isLocked()) {
            flash('More than 120 days has passed since the invoice has been completed, the invoice is now locked', 'error');

            return redirect()->route('invoice.show', ['invoice' => $invoice, 'company' => $company]);
        }

        if (is_null($invoice->client_id)) {
            return redirect()->route('invoice.adhoc.edit', ['invoice' => $invoice, 'company' => Unicorn::getCompanyKey()]);
        }

        $clients = $company->clients;
        $itemtemplates = $company->itemtemplates;
        $recurrence = ($invoice->recurrence) ? $invoice->recurrence : null;

        return view('pages.invoice.edit', compact('invoice', 'clients', 'recurrence', 'itemtemplates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateInvoiceRequest $request
     * @param Company              $company
     * @param \App\Models\Invoice  $invoice
     *
     * @throws \Recurr\Exception\InvalidArgument
     * @throws \Recurr\Exception\InvalidRRule
     *
     * @return Response
     */
    public function update(UpdateInvoiceRequest $request, Company $company, Invoice $invoice)
    {
        if ($invoice->isLocked()) {
            flash('More than 120 days has passed since the invoice has been completed, the invoice is now locked', 'error');

            return redirect()->route('invoice.show', ['invoice' => $invoice, 'company' => $company]);
        }

        $invoice->fill($request->all());
        $invoice->notify = $request->has('notify') ? 1 : 0;

        $ismodified = false;

        foreach ($request->input('item_id') as $key => $itemid) {
            $invoiceitem = InvoiceItem::find($itemid);
            $ismodified = $invoiceitem->modified(
                $request->input('item_name')[$key],
                $request->input('item_description')[$key],
                $request->input('item_quantity')[$key],
                $request->input('item_price')[$key]
            );

            if ($ismodified) {
                break;
            }
        }

        if (count($request->input('item_name')) != count($request->input('item_id'))) {
            $ismodified = true;
        }

        if ($invoice->isDirty() || $ismodified) {
            $originalinvoice = $invoice->getOriginal();
            $originalitems = $invoice->items;

            $oldinvoice = new OldInvoice();
            $oldinvoice->fill($originalinvoice);

            $oldinvoice->created_at = $originalinvoice['created_at'];
            $oldinvoice->updated_at = $originalinvoice['updated_at'];

            $invoice->history()->save($oldinvoice);
            $invoice->touch();

            foreach ($originalitems as $item) {
                $oldinvoiceitem = new OldInvoiceItem();
                $oldinvoiceitem->fill($item->toArray());
                $oldinvoiceitem->old_invoice_id = $oldinvoice->id;
                $oldinvoiceitem->save();
            }
        }

        $invoice->save();

        foreach ($request->input('item_name') as $key => $itemname) {
            if (isset($request->input('item_id')[$key])) {
                //TODO: Validate the invoice item belongs to the invoice/company, need to do authentication here.
                $invoiceitem = InvoiceItem::find($request->input('item_id')[$key]);
                if ($invoiceitem->invoice_id != $invoice->id) {
                    continue;
                }
            } else {
                $invoiceitem = new InvoiceItem();
            }
            $invoiceitem->name = $itemname;
            $invoiceitem->description = $request->input('item_description')[$key];
            $invoiceitem->quantity = $request->input('item_quantity')[$key];
            $invoiceitem->price = $request->input('item_price')[$key];
            $invoiceitem->invoice_id = $invoice->id;
            $invoiceitem->save();
        }

        $invoice = $invoice->fresh();
        $invoice->setInvoiceTotal();

        $recurrenceExists = ($invoice->recurrence) ? true : false;

        if ($request->has('recurring-invoice-check')) {
            if ($request->input('recurring-invoice-check') === 'on' && $request->input('recurring-details') === 'standalone') {
                $invoicesCount = $invoice->recurrence->invoices()->count();
                $invoice->invoice_recurrence_id = null;
                $invoice->save();

                //Check if last invoice and delete if so
                if ($invoicesCount == 1) {
                    $invoice->recurrence->delete();
                }
            } elseif ($request->input('recurring-invoice-check') === 'on') {
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

                $startDate = $invoice->date;
                $timezone = config('app.timezone');
                $rruleString = Unicorn::generateRrule($startDate, $timezone, $repeatsEveryInterval, $repeatsEveryTimePeriod, $repeatUntilOption, $repeatUntilMeta);

                $invoiceRecurrence = ($recurrenceExists) ? $invoice->recurrence : new InvoiceRecurrence();
                $invoiceRecurrence->time_interval = $repeatsEveryInterval;
                $invoiceRecurrence->time_period = $repeatsEveryTimePeriod;
                $invoiceRecurrence->until_type = $repeatUntilOption;
                $invoiceRecurrence->until_meta = $repeatUntilMeta;
                $invoiceRecurrence->rule = $rruleString;
                $invoiceRecurrence->company_id = $invoice->company_id;
                $invoiceRecurrence->save();

                $invoice->invoice_recurrence_id = $invoiceRecurrence->id;
                $invoice->save();

                $items = $invoice->items;

                if ($recurrenceExists) {
                    if ($request->input('recurring-details') === 'future') {
                        //TODO: If updating template, delete all generated preview invoices that are in draft status.
                        //Perhaps, it might be a better idea to just display a preview instead of generating the invoices.
                        $invoiceTemplate = $invoiceRecurrence->template;
                        $invoiceTemplate->fill($invoice->toArray());
                        $invoiceTemplate->save();

                        $invoiceItemTemplates = $invoiceTemplate->items;

                        foreach ($invoiceItemTemplates as $invoiceItemTemplate) {
                            $invoiceItemTemplate->delete();
                        }

                        foreach ($items as $item) {
                            $invoiceItemTemplate = new InvoiceItemTemplate();
                            $invoiceItemTemplate->fill($item->toArray());
                            $invoiceItemTemplate->invoice_template_id = $invoiceTemplate->id;
                            $invoiceItemTemplate->save();
                        }
                    }
                } else {
                    if ($request->input('recurring-details') === 'none') {
                        $invoiceTemplate = new InvoiceTemplate();
                        $invoiceTemplate->fill($invoice->toArray());
                        $invoiceTemplate->invoice_recurrence_id = $invoiceRecurrence->id;
                        $invoiceTemplate->save();

                        foreach ($items as $item) {
                            $invoiceItemTemplate = new InvoiceItemTemplate();
                            $invoiceItemTemplate->fill($item->toArray());
                            $invoiceItemTemplate->invoice_template_id = $invoiceTemplate->id;
                            $invoiceItemTemplate->save();
                        }
                    }
                }
            }
        } else {
            if ($recurrenceExists) : $invoice->recurrence->delete();
            endif;
        }

        flash('Invoice Updated', 'success');

        if (is_null($invoice->client_id)) {
            return redirect()->route('invoice.adhoc.edit', ['invoice' => $invoice, 'company' => $company]);
        }

        return redirect()->route('invoice.show', ['invoice' => $invoice, 'company' => $company]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company             $company
     * @param \App\Models\Invoice $invoice
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy(Company $company, Invoice $invoice)
    {
        $invoice->delete();

        flash('Invoice Deleted', 'success');

        return redirect()->route('invoice.index', ['company' => $company]);
    }

    /**
     * @param Company $company
     * @param Invoice $invoice
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history(Company $company, Invoice $invoice)
    {
        $client = $invoice->getClient();
        $histories = $invoice->history()->orderBy('created_at', 'desc')->get();

        return view('pages.invoice.history', compact('invoice', 'client', 'histories'));
    }

    /**
     * Function to check if the invoice has any siblings.
     *
     * @param Company $company
     * @param Invoice $invoice
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSiblings(Company $company, Invoice $invoice)
    {
        $hasSiblings = ($invoice->siblings()) ? true : false;

        return response()->json(compact('hasSiblings'));
    }
}
