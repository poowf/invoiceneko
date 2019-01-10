<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemTemplateRequest;
use App\Http\Requests\UpdateItemTemplateRequest;
use App\Models\Company;
use App\Models\ItemTemplate;

class ItemTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Company $company)
    {
        $itemTemplates = $company->itemtemplates()->get();

        return view('pages.itemtemplate.index', compact('itemTemplates'));
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
        if ($company) {
            return view('pages.itemtemplate.create');
        } else {
            return view('pages.invoice.nocompany');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateItemTemplateRequest $request
     * @param Company                   $company
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateItemTemplateRequest $request, Company $company)
    {
        $itemTemplate = new ItemTemplate();
        $itemTemplate->fill($request->all());
        $company->itemtemplates()->save($itemTemplate);

        flash('Item Template Created', 'success');

        return redirect()->route('itemtemplate.show', ['itemtemplate' => $itemTemplate, 'company' => $company]);
    }

    /**
     * Display the specified resource.
     *
     * @param Company                  $company
     * @param \App\Models\ItemTemplate $itemTemplate
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, ItemTemplate $itemTemplate)
    {
        return view('pages.itemtemplate.show', compact('itemTemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company                  $company
     * @param \App\Models\ItemTemplate $itemTemplate
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company, ItemTemplate $itemTemplate)
    {
        return view('pages.itemtemplate.edit', compact('itemTemplate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateItemTemplateRequest $request
     * @param Company                   $company
     * @param \App\Models\ItemTemplate  $itemTemplate
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemTemplateRequest $request, Company $company, ItemTemplate $itemTemplate)
    {
        $itemTemplate->fill($request->all());
        $company->itemtemplates()->save($itemTemplate);

        flash('Item Template Updated', 'success');

        return redirect()->route('itemtemplate.show', ['itemtemplate' => $itemTemplate, 'company' => $company]);
    }

    /**
     * Retrieve the itemtemplate and return as object.
     *
     * @param Company                  $company
     * @param \App\Models\ItemTemplate $itemTemplate
     *
     * @return ItemTemplate
     */
    public function retrieve(Company $company, ItemTemplate $itemTemplate)
    {
        return response()->json($itemTemplate);
    }

    /**
     * @param Company      $company
     * @param ItemTemplate $itemTemplate
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function duplicate(Company $company, ItemTemplate $itemTemplate)
    {
        $duplicatedItemTemplate = $itemTemplate->duplicate();
        flash('Item Template has been Cloned Sucessfully', 'success');

        return redirect()->route('itemtemplate.show', ['itemtemplate' => $duplicatedItemTemplate, 'company' => $company]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company                  $company
     * @param \App\Models\ItemTemplate $itemTemplate
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company, ItemTemplate $itemTemplate)
    {
        $itemTemplate->delete();

        flash('Item Template Deleted', 'success');

        return redirect()->route('itemtemplate.index', ['company' => $company]);
    }
}
