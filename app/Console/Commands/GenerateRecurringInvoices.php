<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\InvoiceEvent;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Library\Poowf\Unicorn;
use Illuminate\Support\Facades\Log;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use Recurr\Transformer\Constraint\AfterConstraint;
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
     * @return mixed
     * @throws \Recurr\Exception\InvalidRRule
     * @throws \Recurr\Exception\InvalidWeekday
     */
    public function handle()
    {
        $invoiceEvents = InvoiceEvent::all();

        foreach($invoiceEvents as $event)
        {
            $company = $event->company;
            $now = Carbon::now();
            $template = $event->template;
            $templateItems = $template->items;

            $constraintTime = $now->{$this->getDateAdditionOperator($event->time_period)}($event->time_interval + 3);
            $constraint = new BeforeConstraint($constraintTime);

//            $rrule = Unicorn::generateRrule($event->created_at, $timezone, $event->time_interval, $event->time_period, $event->until_type, $event->until_meta, true);
            $rrule = Rule::createFromString($event->rule);
            $transformer = new ArrayTransformer();

            $recurrences = $transformer->transform($rrule, $constraint);

            foreach($recurrences as $key => $recurrence)
            {
                $template->refresh();
                if($key == 2)
                {
                    break;
                }

                $template->date = $template->date->{$this->getDateAdditionOperator($event->time_period)}(($event->time_interval * ($key + 1) ));

                $generatedInvoice = new Invoice;
                $generatedInvoice->fill($template->toArray());
                $generatedInvoice->client_id = $template->client_id;
                $generatedInvoice->company_id = $company->id;
                $generatedInvoice->invoice_event_id = $event->id;
                $generatedInvoice->status = Invoice::STATUS_DRAFT;
                $generatedInvoice->notify = $template->notify;

                //Generate hash based on the serialized version of the invoice;
                $hash = hash('sha512', serialize($generatedInvoice . $templateItems));

                if(Invoice::where('hash', $hash)->count() == 1)
                {
                    print_r("Invoice already generated\n");
                }
                else
                {
                    $generatedInvoice->nice_invoice_id = $company->niceinvoiceid();
                    $generatedInvoice->hash = $hash;
                    $generatedInvoice->save();

                    foreach($templateItems as $key => $item)
                    {
                        $invoiceitem = new InvoiceItem;
                        $invoiceitem->name = $item->name;
                        $invoiceitem->description = $item->description;
                        $invoiceitem->quantity   = $item->quantity;
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
        switch($timePeriod)
        {
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
