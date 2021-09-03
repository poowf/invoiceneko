<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Models\Company;
use App\Models\Recipient;
use Illuminate\Support\Str;
use Image;
use PragmaRX\Countries\Package\Countries;
use Storage;

class ClientController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Company $company)
    {
        $clients = $company->clients;

        return view('pages.client.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Company $company)
    {
        $countries = countries();

        return view('pages.client.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateClientRequest $request
     * @param Company             $company
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateClientRequest $request, Company $company)
    {
        $client = new Client();
        $client->fill($request->all());
        $client->company_id = $company->id;
        $client->save();

        $storedirectory = '/perm_store/company/'.$client->company_id.'/clients/'.$client->id.'/photos/';

        Storage::makeDirectory($storedirectory);

        if ($request->file('logo')) {
            $file = $request->file('logo');
            $uuid = Str::random(25);
            $filename = $uuid.'.png';

            if (! Storage::exists($storedirectory.'logo_'.$filename)) {
                $image = Image::make($file)->fit(500, 500, function ($constraint) {
                    $constraint->upsize();
                }, 'center');
                Storage::put($storedirectory.'logo_'.$filename, $image->stream('jpg')->detach());
            }

            $filepath = $storedirectory.'logo_'.$filename;

            $client->logo = $filepath;
        }

        $client->save();

        $recipient = new Recipient();
        $recipient->salutation = $client->contactsalutation;
        $recipient->first_name = $client->contactfirstname;
        $recipient->last_name = $client->contactlastname;
        $recipient->email = $client->contactemail;
        $recipient->phone = $client->contactphone;
        $recipient->company_id = $client->company_id;
        $client->recipients()->save($recipient);

        flash('Client Created', 'success');

        return redirect()->route('client.index', ['company' => $company]);
    }

    /**
     * Display the specified resource.
     *
     * @param Company            $company
     * @param \App\Models\Client $client
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, Client $client)
    {
        $invoices = $client->invoices;
        $recipients = $client->recipients;

        return view('pages.client.show', compact('client', 'recipients', 'invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company            $company
     * @param \App\Models\Client $client
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company, Client $client)
    {
        $countries = countries();

        return view('pages.client.edit', compact('client', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateClientRequest $request
     * @param Company             $company
     * @param \App\Models\Client  $client
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, Company $company, Client $client)
    {
        $client->fill($request->all());
        $client->save();

        $storedirectory = '/perm_store/company/'.$client->company_id.'/clients/'.$client->id.'/photos/';

        Storage::makeDirectory($storedirectory);

        if ($request->file('logo')) {
            $file = $request->file('logo');
            $uuid = Str::random(25);
            $filename = $uuid.'.png';

            if (! Storage::exists($storedirectory.'logo_'.$filename)) {
                $image = Image::make($file)->fit(500, 500, function ($constraint) {
                    $constraint->upsize();
                }, 'center');
                Storage::put($storedirectory.'logo_'.$filename, $image->stream('jpg')->detach());
            }

            $filepath = $storedirectory.'logo_'.$filename;

            $client->logo = $filepath;
        }

        $client->save();

        flash('Client Updated', 'success');

        return redirect()->route('client.show', ['client' => $client, 'company' => $company]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company            $company
     * @param \App\Models\Client $client
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company, Client $client)
    {
        $client->delete();

        flash('Client Deleted', 'success');

        return redirect()->route('client.index', ['company' => $company]);
    }

    /**
     * @param Company $company
     * @param Client  $client
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invoicecreate(Company $company, Client $client)
    {
        return redirect()->route('invoice.create', ['company' => $company])->withInput([
            'client_id' => $client->id,
        ]);
    }
}
