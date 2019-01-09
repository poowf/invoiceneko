<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRecipientRequest;
use App\Http\Requests\UpdateRecipientRequest;
use App\Models\Client;
use App\Models\Company;
use App\Models\Recipient;

class RecipientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Company $company)
    {
        return view('pages.client.recipient.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRecipientRequest $request
     * @param Company $company
     * @param Client $client
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRecipientRequest $request, Company $company, Client $client)
    {
        $recipient = new Recipient;
        $recipient->fill($request->all());
        $recipient->company_id = $company->id;
        $client->recipients()->save($recipient);

        flash("You have sucessfully created a recipient", 'success');

        return redirect()->route('client.show', [ 'company' => $company, 'client' => $client ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Recipient $recipient
     * @return void
     */
    public function show(Recipient $recipient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     * @param Client $client
     * @param  \App\Models\Recipient $recipient
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company, Client $client, Recipient $recipient)
    {
        return view('pages.client.recipient.edit', compact('recipient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRecipientRequest $request
     * @param Company $company
     * @param Client $client
     * @param  \App\Models\Recipient $recipient
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRecipientRequest $request, Company $company, Client $client, Recipient $recipient)
    {
        $recipient->fill($request->all());
        $recipient->company_id = $company->id;
        $client->recipients()->save($recipient);

        flash("You have sucessfully updated a recipient", 'success');

        return redirect()->route('client.show', [ 'company' => $company, 'client' => $client ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Recipient $recipient
     * @return void
     */
    public function destroy(Recipient $recipient)
    {
        //
    }
}
