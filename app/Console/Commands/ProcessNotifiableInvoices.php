<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Notifications\InvoiceNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
    protected $description = 'Process the invoices to be sent out based on the date attribute';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct ()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle ()
    {
        $startDate = Carbon::now()->subDay();
        $endDate = Carbon::now()->addDay();
        $invoices = Invoice::datebetween($startDate, $endDate)->notifiable()->get();
        foreach ($invoices as $invoice) {
            $company = $invoice->company;
            $localDate = Carbon::now($company->timezone);
            $localInvoiceDate = $invoice->date;

            if (date_diff($localDate, $localInvoiceDate)->format('%a') === '0') {
                if ($invoice->status = Invoice::STATUS_DRAFT) {
                    $invoice->status = Invoice::STATUS_OPEN;
                    $invoice->save();
                }

                $invoice->notify(new InvoiceNotification($invoice));
            }
        }
    }
}
