<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;

class MainController extends Controller
{
    public function getMain()
    {
        return view('pages.main');
    }

    public function testMail(Request $request)
    {
        $invoice = Invoice::find(1);

        Mail::to(auth()->user())->send(new InvoiceMail($invoice));
    }
}
