<?php

namespace App\Http\Controllers;

use App\Library\Poowf\Unicorn;
use App\Models\Company;

class MainController extends Controller
{
    public function main()
    {
        return redirect()->route('auth.show');
    }

    public function start()
    {
        return view('pages.start');
    }

    public function dashboard(Company $company)
    {
        $user = auth()->user();
//        $company = auth()->user()->company;
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
        if(is_null(Unicorn::getCompanyKey()))
        {
            return view('pages.nocompany');
        }
        else
        {
            return redirect()->route('dashboard', [ 'company' => Unicorn::getCompanyKey() ]);
        }
    }
}
