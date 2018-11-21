<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemTemplateRequest;
use App\Http\Requests\UpdateItemTemplateRequest;
use App\Models\Company;
use App\Models\ItemTemplate;
use Illuminate\Http\Request;

class ItemTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function index(Company $company)
    {
        $itemtemplates = $company->itemtemplates()->get();

        return view('pages.itemtemplate.index', compact('itemtemplates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function create(Company $company)
    {
        if($company)
        {
            return view('pages.itemtemplate.create');
        }
        else
        {
            return view('pages.invoice.nocompany');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateItemTemplateRequest $request
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function store(CreateItemTemplateRequest $request, Company $company)
    {
        $itemtemplate = new ItemTemplate;
        $itemtemplate->fill($request->all());
        $company->itemtemplates()->save($itemtemplate);

        flash('Item Template Created', 'success');

        return redirect()->route('itemtemplate.show', [ 'itemtemplate' => $itemtemplate->id, 'company' => $company->domain_name ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Company $company
     * @param  \App\Models\ItemTemplate $itemtemplate
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, ItemTemplate $itemtemplate)
    {
        return view('pages.itemtemplate.show', compact('itemtemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     * @param  \App\Models\ItemTemplate $itemtemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company, ItemTemplate $itemtemplate)
    {
        return view('pages.itemtemplate.edit', compact('itemtemplate'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateItemTemplateRequest $request
     * @param Company $company
     * @param  \App\Models\ItemTemplate $itemtemplate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemTemplateRequest $request, Company $company, ItemTemplate $itemtemplate)
    {
        $itemtemplate->fill($request->all());
        $company->itemtemplates()->save($itemtemplate);

        flash('Item Template Updated', 'success');

        return redirect()->route('itemtemplate.show', [ 'itemtemplate' => $itemtemplate->id, 'company' => $company->domain_name ]);
    }

    /**
     * Retrieve the itemtemplate and return as object
     *
     * @param Company $company
     * @param  \App\Models\ItemTemplate $itemtemplate
     * @return ItemTemplate
     */
    public function retrieve(Company $company, ItemTemplate $itemtemplate)
    {
        return response()->json($itemtemplate);
    }

    /**
     * @param Company $company
     * @param ItemTemplate $itemtemplate
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate(Company $company, ItemTemplate $itemtemplate)
    {
        $duplicatedItemTemplate = $itemtemplate->duplicate();
        flash('Item Template has been Cloned Sucessfully', "success");
        return redirect()->route('itemtemplate.show', [ 'itemtemplate' => $duplicatedItemTemplate->id, 'company' => $company->domain_name ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @param  \App\Models\ItemTemplate $itemtemplate
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Company $company, ItemTemplate $itemtemplate)
    {
        $itemtemplate->delete();

        flash('Item Template Deleted', 'success');

        return redirect()->route('itemtemplate.index', [ 'company' => $company->domain_name ]);
    }
}
