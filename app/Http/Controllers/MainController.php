<?php

namespace App\Http\Controllers;

use App\Library\Poowf\Unicorn;
use Illuminate\Http\Request;

use App\Models\Invoice;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;
use PDF;

class MainController extends Controller
{
    public function main()
    {
        return redirect()->route('auth.show');
    }

    public function user_signup()
    {
        return view('pages.user.signup');
    }

    public function dashboard()
    {
        $user = auth()->user();
        $company = auth()->user()->company;
        if($company)
        {
            $overdueinvoices = $company->invoices()->overdue()->take(10)->get();
        }
        else
        {
            $overdueinvoices = null;
        }

        return view('pages.dashboard', compact('user', 'overdueinvoices'));
    }

    public function nocompany()
    {
        return view('pages.nocompany');
    }
}
