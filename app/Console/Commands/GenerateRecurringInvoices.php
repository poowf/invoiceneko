<?php

namespace App\Console\Commands;

use App\Library\Poowf\Unicorn;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceRecurrence;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use Recurr\Transformer\Constraint\BeforeConstraint;

class GenerateRecurringInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate recurring invoices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws \Recurr\Exception\InvalidRRule
     * @throws \Recurr\Exception\InvalidWeekday
     *
     * @return mixed
     */
    public function handle()
    {
        $invoiceRecurrences = InvoiceRecurrence::all();

        foreach ($invoiceRecurrences as $invoiceRecurrence) {
            $company = $invoiceRecurrence->company;
            $now = Carbon::now();
            $template = $invoiceRecurrence->template;
            $templateItems = $template->items;

            $constraintTime = $now->{$this->getDateAdditionOperator($invoiceRecurrence->time_period)}($invoiceRecurrence->time_interval + 3);
            $constraint = new BeforeConstraint($constraintTime);

//            $rrule = Unicorn::generateRrule($invoiceRecurrence->created_at, $timezone, $invoiceRecurrence->time_interval, $invoiceRecurrence->time_period, $invoiceRecurrence->until_type, $invoiceRecurrence->until_meta, true);
            $rrule = Rule::createFromString($invoiceRecurrence->rule, $template->date);
            $transformer = new ArrayTransformer();

            $recurrences = $transformer->transform($rrule, $constraint);

            foreach ($recurrences as $key => $recurrence) {
                if ($key == 0) {
                    //Skip the first instance as it is the original invoice.
                    continue;
                } elseif ($key == 3) {
                    break;
                }

//                $template->date = $template->date->{$this->getDateAdditionOperator($invoiceRecurrence->time_period)}(($invoiceRecurrence->time_interval * ($key + 1) ));

                $generatedInvoice = new Invoice();
                $generatedInvoice->fill($template->toArray());
                $generatedInvoice->date = $recurrence->getEnd();
                $generatedInvoice->client_id = $template->client_id;
                $generatedInvoice->company_id = $company->id;
                $generatedInvoice->invoice_recurrence_id = $invoiceRecurrence->id;
                $generatedInvoice->status = Invoice::STATUS_DRAFT;
                $generatedInvoice->notify = $template->notify;

                //Generate hash based on the serialized version of the invoice;
                //Only retrieve the invoice data without any relations
                $hash = hash('sha512', serialize(json_encode($generatedInvoice->getAttributes()).$templateItems));

                if (Invoice::where('hash', $hash)->count() == 1) {
                    print_r("Invoice already generated\n");
                    continue;
                } else {
                    $generatedInvoice->generated = true;
                    $generatedInvoice->nice_invoice_id = $company->niceinvoiceid();
                    $generatedInvoice->hash = $hash;
                    $generatedInvoice->save();

                    foreach ($templateItems as $key => $item) {
                        $invoiceitem = new InvoiceItem();
                        $invoiceitem->name = $item->name;
                        $invoiceitem->description = $item->description;
                        $invoiceitem->quantity = $item->quantity;
                        $invoiceitem->price = $item->price;
                        $invoiceitem->invoice_id = $generatedInvoice->id;
                        $invoiceitem->save();
                    }

                    $generatedInvoice->setInvoiceTotal();
                }
            }
        }
    }

    public function getDateAdditionOperator($timePeriod)
    {
        switch ($timePeriod) {
            case 'day':
                return 'addDays';
                break;
            case 'week':
                return 'addWeeks';
                break;
            case 'month':
                return 'addMonths';
                break;
            case 'year':
                return 'addYears';
                break;
        }
    }
}
