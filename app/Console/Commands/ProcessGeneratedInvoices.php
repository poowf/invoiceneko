<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\InvoiceEvent;
use App\Notifications\InvoiceNotification;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Console\Command;
use App\Library\Poowf\Unicorn;
use Illuminate\Support\Facades\Log;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use Recurr\Transformer\Constraint\AfterConstraint;
use Recurr\Transformer\Constraint\BeforeConstraint;

class ProcessGeneratedInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process the generated recurring invoices';

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
     */
    public function handle()
    {
        $invoiceEvents = InvoiceEvent::all();

        $timezone = new DateTimeZone('Asia/Singapore');

        foreach($invoiceEvents as $event)
        {
            $company = $event->company;
            $now = Carbon::now();

            switch($event->time_period)
            {
                case 'day':
                    $constraintTime = $now->addDay($event->time_interval + 2);
                    break;
                case 'week':
                    $constraintTime = $now->addWeek(($event->time_interval + 2));
                    break;
                case 'month':
                    $constraintTime = $now->addMonth($event->time_interval + 2);
                    break;
                case 'year':
                    $constraintTime = $now->addYear($event->time_interval + 2);
                    break;
            }

            $constraint = new BeforeConstraint($constraintTime);

//            $rrule = Unicorn::generateRrule($event->created_at, $timezone, $event->time_interval, $event->time_period, $event->until_type, $event->until_meta, true);
            $rrule = Rule::createFromString($event->rule);
            $transformer = new ArrayTransformer();

            $recurrences = $transformer->transform($rrule, $constraint);

            $today = Carbon::now();

            foreach($recurrences as $key => $recurrence)
            {
                //Need to use datediff
                //Currently this will only send after the date has passed.
                if($today > $recurrence->getEnd())
                {
                    if ($key == 2) {
                        break;
                    }

                    $template = $event->template;
                    $templateItems = $template->items;
                    $generatedInvoice = new Invoice;
                    $generatedInvoice->fill($template->toArray());
                    $duedate = Carbon::createFromFormat('Y-m-d H:i:s', $recurrence->getEnd()->format('Y-m-d H:i:s'))->addDays($generatedInvoice->netdays)->startOfDay()->toDateTimeString();
                    $generatedInvoice->date = Carbon::createFromFormat('Y-m-d H:i:s', $recurrence->getEnd()->format('Y-m-d H:i:s'))->startOfDay()->toDateTimeString();
                    $generatedInvoice->duedate = $duedate;
                    $generatedInvoice->client_id = $template->client_id;
                    $generatedInvoice->company_id = $company->id;
                    $generatedInvoice->invoice_event_id = $event->id;
                    $generatedInvoice->status = Invoice::STATUS_DRAFT;

                    //Generate hash based on the serialized version of the invoice;
                    $hash = hash('sha512', serialize($generatedInvoice . $templateItems));

                    if (Invoice::where('hash', $hash)->count() == 1) {
                        {
                            $invoice = Invoice::where('hash', $hash)->first();
                            $invoice->notify(new InvoiceNotification($invoice));
                        }
                    }
                }
            }
        }
    }
}
