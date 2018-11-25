<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Library\Poowf\Unicorn;
use App\Mail\ContactForm;
use App\Models\Company;
use Mail;

class MainController extends Controller
{
    public function main()
    {
        return view('pages.main');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function community()
    {
        return view('pages.community');
    }

    public function pricing()
    {
        return view('pages.pricing');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactHandle(ContactRequest $request)
    {
        Mail::to(env('MAIL_TO_ADDRESS', 'support@poowflabs.com'))->send(new ContactForm(
            $request->input('name'),
            $request->input('email'),
            $request->input('message')
        ));

        flash('Cool! We will get back to you soon.', 'success');
        return redirect()->back();
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
