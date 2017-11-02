<?php

namespace App\Http\Controllers;

use App\Library\Poowf\Unicorn;
use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;

class MainController extends Controller
{
    public function main()
    {
        return view('pages.main');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $company = auth()->user()->company;
        $overdueinvoices = Unicorn::ifExists($company, 'invoices()->overdue()->take(10)->get()');
        $overdueinvoices = $company->invoices()->overdue()->take(10)->get();

        return view('pages.dashboard', compact('user', 'overdueinvoices'));
    }

    public function nocompany()
    {
        return view('pages.nocompany');
    }

    public function testMail(Request $request)
    {
        $invoice = Invoice::find(1);

        Mail::to(auth()->user())->send(new InvoiceMail($invoice));
    }
}
