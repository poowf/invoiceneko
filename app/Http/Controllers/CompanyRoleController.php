<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Library\Poowf\Unicorn;
use App\Models\Company;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;
use App\Models\Role;

class CompanyRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function index(Company $company)
    {
        $roles = Role::all();

        return view('pages.company.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function create(Company $company)
    {
        $company = auth()->user()->company;

        $permissions = self::getFormattedPermissions();

        return view('pages.company.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRoleRequest $request
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoleRequest $request, Company $company)
    {
        $title = $request->input('title');
        $permissions = $request->input('permissions');
        $role = new Role;
        $role->title = $title;
        $role->name = str_slug($title);
        $role->save();

        if(!empty($permissions)) {
            foreach ($permissions as $permission) {
                $permissionPieces = explode('-', $permission);
                $model = '\\App\\Models\\' . str_replace(' ', '', $permissionPieces[1]);
                Bouncer::allow($role)->to($permissionPieces[0], $model);
            }
        }

        flash("The Role has been created", 'success');
        return redirect()->route('company.roles.index', [ 'company' => $company->domain_name ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company, Role $role)
    {
        if($role->name != 'global-administrator')
        {
            $rolePermissions = $role->getAbilities();
            $permissions = self::getFormattedPermissions();

            return view('pages.company.roles.edit', compact('role', 'rolePermissions', 'permissions'));
        }
        else
        {
            return redirect()->route('company.roles.index', [ 'company' => $company->domain_name ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleRequest $request
     * @param Company $company
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Company $company, Role $role)
    {
        if($role->name != 'global-administrator')
        {
            $title = $request->input('title');
            $permissions = $request->input('permissions');
            $role->title = $title;
            $role->name = str_slug($title);
            $role->save();

            $rolePermissions = $role->getAbilities();

    //        $role->abilities()->sync($permissions);

            foreach($rolePermissions as $rolePermission)
            {
                Bouncer::disallow($role)->to($rolePermission);
            }

            if(!empty($permissions)) {
                foreach ($permissions as $permission) {
                    $permissionPieces = explode('-', $permission);
                    $model = '\\App\\Models\\' . str_replace(' ', '', $permissionPieces[1]);
                    Bouncer::allow($role)->to($permissionPieces[0], $model);
                }
            }

            flash("The Role has been updated", 'success');
            return redirect()->route('company.roles.index', [ 'company' => $company->domain_name ]);
        }
        else
        {
            return redirect()->route('company.roles.index', [ 'company' => $company->domain_name ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @param Role $role
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Company $company, Role $role)
    {
        $role->delete();

        flash("The Role has been deleted", 'success');
        return redirect()->route('company.roles.index', [ 'company' => $company->domain_name ]);
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

            $type = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', str_replace('App\\Models\\', '', $permission->entity_type)));
            $permission->type = $type;
        }

        return $permissions;
    }
}
