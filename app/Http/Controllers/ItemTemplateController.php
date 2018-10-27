<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateItemTemplateRequest;
use App\Http\Requests\UpdateItemTemplateRequest;
use App\Models\ItemTemplate;
use Illuminate\Http\Request;

class ItemTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = auth()->user()->company;
        $itemtemplates = $company->itemtemplates()->get();

        return view('pages.itemtemplate.index', compact('itemtemplates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = auth()->user()->company;

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
     * @return \Illuminate\Http\Response
     */
    public function store(CreateItemTemplateRequest $request)
    {
        $company = auth()->user()->company;

        $itemtemplate = new ItemTemplate;
        $itemtemplate->fill($request->all());
        $company->itemtemplates()->save($itemtemplate);

        flash('Item Template Created', 'success');

        return redirect()->route('itemtemplate.show', [ 'itemtemplate' => $itemtemplate->id ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemTemplate  $itemtemplate
     * @return \Illuminate\Http\Response
     */
    public function show(ItemTemplate $itemtemplate)
    {
        return view('pages.itemtemplate.show', compact('itemtemplate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemTemplate  $itemtemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemTemplate $itemtemplate)
    {
        return view('pages.itemtemplate.edit', compact('itemtemplate'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateItemTemplateRequest $request
     * @param  \App\Models\ItemTemplate $itemtemplate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemTemplateRequest $request, ItemTemplate $itemtemplate)
    {
        $company = auth()->user()->company;

        $itemtemplate->fill($request->all());
        $company->itemtemplates()->save($itemtemplate);

        flash('Item Template Updated', 'success');

        return redirect()->route('itemtemplate.show', [ 'itemtemplate' => $itemtemplate->id ]);
    }

    /**
     * Retrieve the itemtemplate and return as object
     *
     * @param  \App\Models\ItemTemplate $itemtemplate
     * @return ItemTemplate
     */
    public function retrieve(ItemTemplate $itemtemplate)
    {
        return response()->json($itemtemplate);
    }

    public function duplicate(ItemTemplate $itemtemplate)
    {
        $duplicatedItemTemplate = $itemtemplate->duplicate();
        flash('Item Template has been Cloned Sucessfully', "success");
        return redirect()->route('itemtemplate.show', ['itemtemplate' => $duplicatedItemTemplate->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemTemplate $itemtemplate
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(ItemTemplate $itemtemplate)
    {
        $itemtemplate->delete();

        flash('Item Template Deleted', 'success');

        return redirect()->back();
    }
}
