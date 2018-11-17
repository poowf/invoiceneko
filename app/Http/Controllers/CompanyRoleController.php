<?php

namespace App\Http\Controllers;

use App\Library\Poowf\Unicorn;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;

class CompanyRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = auth()->user()->company;

        $user = $company->owner;

//        $roles = $user->getRoles();

        $roles = Bouncer::role()->all();

        return view('pages.company.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $company = auth()->user()->company;

        $permissions = self::getFormattedPermissions();

        return view('pages.company.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = $request->input('title');
        $permissions = $request->input('permissions');
        $role = Bouncer::role()->firstOrCreate([
            'name' => str_slug($title),
            'title' => $title,
        ]);

        if(!empty($permissions)) {
            foreach ($permissions as $permission) {
                Bouncer::allow($role)->to($permission);
            }
        }

        flash("The Role has been created", 'success');
        return redirect()->route('company.roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($rolename)
    {
        if($rolename != 'global-administrator')
        {
            $role = Bouncer::role()->where('name', $rolename)->first();
            $rolePermissions = $role->getAbilities();
            $permissions = self::getFormattedPermissions();

            return view('pages.company.roles.edit', compact('role', 'rolePermissions', 'permissions'));
        }
        else
        {
            return redirect()->route('company.roles.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $rolename)
    {
        if($rolename != 'global-administrator')
        {
            $title = $request->input('title');
            $permissions = $request->input('permissions');
            $role = Bouncer::role()->where('name', $rolename)->first();
            $role->title = $title;
            $role->name = str_slug($title);
            $role->save();

            $rolePermissions = $role->getAbilities();

    //        $role->abilities()->sync($permissions);

            foreach($rolePermissions as $rolePermission)
            {
                Bouncer::disallow($role)->to($rolePermission);
            }

            if(!empty($permissions))
            {
                foreach ($permissions as $permission)
                {
                    Bouncer::allow($role)->to($permission);
                }
            }

            flash("The Role has been updated", 'success');
            return redirect()->route('company.roles.index');
        }
        else
        {
            return redirect()->route('company.roles.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($rolename)
    {
        $role = Bouncer::role()->where('name', $rolename)->first();
        $role->delete();

        flash("The Role has been deleted", 'success');
        return redirect()->route('company.roles.index');
    }

    public function getFormattedPermissions()
    {
        $permissions = Bouncer::ability()->all();

        foreach($permissions as $key => $permission)
        {
            if($permission->name === '*')
            {
                $permission->title = 'All Permissions';
                continue;
            }

            $type = ucwords(substr(str_replace('-', ' ', $permission->name), strpos($permission->name, "-") + 1));
            $permission->type = $type;
        }

        return $permissions;
    }
}
