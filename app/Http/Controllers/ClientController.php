<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use PragmaRX\Countries\Package\Countries;
use Storage;
use Uuid;
use Image;

class ClientController extends Controller
{
    public function __construct(){
        $this->countries = new Countries();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = auth()->user()->company;
        $clients = $company->clients;

        return view('pages.client.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = $this->countries->all();

        return view('pages.client.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateClientRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateClientRequest $request)
    {
        $client = new Client;
        $client->fill($request->all());
        $client->company_id = auth()->user()->company_id;
        $client->save();

        $storedirectory = '/perm_store/company/' . $client->company_id . '/clients/' . $client->id . '/photos/';

        Storage::makeDirectory($storedirectory);

        if ($request->file('logo'))
        {
            $file = $request->file('logo');
            $uuid = str_random(25);
            $filename = $uuid . '.png';

            if (!Storage::exists($storedirectory . 'logo_' . $filename))
            {
                $image = Image::make($file)->fit(500, 500, function ($constraint) {
                    $constraint->upsize();
                }, 'center');
                Storage::put($storedirectory . 'logo_' . $filename, $image->stream('jpg')->detach());
            }

            $filepath = $storedirectory . 'logo_' . $filename;

            $client->logo = $filepath;
        }

        $client->save();

        flash('Client Created', 'success');

        return redirect()->route('client.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        $invoices = $client->invoices;
        return view('pages.client.show', compact('client', 'invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $countries = $this->countries->all();

        return view('pages.client.edit', compact('client', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateClientRequest $request
     * @param  \App\Models\Client $client
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $client->fill($request->all());
        $client->save();

        $storedirectory = '/perm_store/company/' . $client->company_id . '/clients/' . $client->id . '/photos/';

        Storage::makeDirectory($storedirectory);

        if ($request->file('logo'))
        {
            $file = $request->file('logo');
            $uuid = str_random(25);
            $filename = $uuid . '.png';

            if (!Storage::exists($storedirectory . 'logo_' . $filename))
            {
                $image = Image::make($file)->fit(500, 500, function ($constraint) {
                    $constraint->upsize();
                }, 'center');
                Storage::put($storedirectory . 'logo_' . $filename, $image->stream('jpg')->detach());
            }

            $filepath = $storedirectory . 'logo_' . $filename;

            $client->logo = $filepath;
        }

        $client->save();

        flash('Client Updated', 'success');

        return redirect()->route('client.show', [ 'client' => $client->id ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client $client
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Client $client)
    {
        $client->delete();

        flash('Client Deleted', 'success');

        return redirect()->route('client.index');
    }

    /**
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function invoicecreate(Client $client)
    {
        return redirect()->route('invoice.create')->withInput([
            'client_id' => $client->id
        ]);
    }

}
