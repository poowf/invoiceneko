<?php

namespace App\Console\Commands;

use App\Models\InvoiceEvent;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Console\Command;
use App\Library\Poowf\Unicorn;
use Illuminate\Support\Facades\Log;
use Recurr\Rule;
use Recurr\Transformer\ArrayTransformer;
use Recurr\Transformer\Constraint\AfterConstraint;
use Recurr\Transformer\Constraint\BeforeConstraint;

class ProcessRecurringInvoices extends Command
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
    protected $description = 'Process and generate recurring invoices';

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

        $timezone = new DateTimeZone('Asia/Singapore');

        foreach($invoiceEvents as $event)
        {
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

            foreach($recurrences as $key => $recurrence)
            {
                if($key == 2)
                {
                    break;
                }

                $today = Carbon::now();

                if($today > $recurrence->getEnd())
                {
                    $invoice = $event->invoice;
                    $duplicatedInvoice = $invoice->duplicate(Carbon::createFromFormat('Y-m-d H:i:s', $recurrence->getEnd()->format('Y-m-d H:i:s')));
                }
            }

        }
    }
}
