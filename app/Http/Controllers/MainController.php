<?php

namespace App\Http\Controllers;

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
        $overdueinvoices = Invoice::query()->overdue()->get();

        return view('pages.dashboard', compact('user', 'overdueinvoices'));
    }

    public function testMail(Request $request)
    {
        $invoice = Invoice::find(1);

        Mail::to(auth()->user())->send(new InvoiceMail($invoice));
    }
}
