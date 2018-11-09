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

class ProcessNotifiableInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:notify';

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
        $startDate = Carbon::now()->subDay();
        $endDate = Carbon::now()->addDay();
        $invoices = Invoice::datebetween($startDate, $endDate)->notifiable()->get();
        foreach($invoices as $invoice)
        {
            $company = $invoice->company;
            $localDate = Carbon::now($company->timezone);
            $localInvoiceDate = Carbon::createFromFormat('Y-m-d H:i:s', $invoice->date, $company->timezone);

            if(date_diff($localDate, $localInvoiceDate)->format('%a') === '0')
            {
                $invoice->notify(new InvoiceNotification($invoice));
            }
        }
    }
}
