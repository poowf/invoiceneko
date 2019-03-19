<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Company;
use App\Models\Role;
use Illuminate\Support\Str;
use Silber\Bouncer\BouncerFacade as Bouncer;

class CompanyRoleController extends Controller
{
    private $defaultRoles;

    public function __construct()
    {
        $this->defaultRoles = [
            'global-administrator',
            'administrator',
            'user',
        ];
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
        $roles = Role::all();

        return view('pages.company.roles.index', compact('roles'));
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
        $permissions = self::getFormattedPermissions();

        return view('pages.company.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRoleRequest $request
     * @param Company           $company
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRoleRequest $request, Company $company)
    {
        $title = $request->input('title');
        $permissions = $request->input('permissions');
        $role = new Role();
        $role->title = $title;
        $role->name = Str::slug($title);
        $role->save();

        if (!empty($permissions)) {
            $abilities = Bouncer::ability()->whereIn('name', $permissions)->pluck('id');
            $role->abilities()->sync($abilities);
        }

        flash('The Role has been created', 'success');

        return redirect()->route('company.roles.index', ['company' => $company]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
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
     * @param Role    $role
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company, Role $role)
    {
        if (!in_array($role->name, $this->defaultRoles)) {
            $rolePermissions = $role->getAbilities();
            $permissions = self::getFormattedPermissions();

            return view('pages.company.roles.edit', compact('role', 'rolePermissions', 'permissions'));
        } else {
            return redirect()->route('company.roles.index', ['company' => $company]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRoleRequest $request
     * @param Company           $company
     * @param Role              $role
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Company $company, Role $role)
    {
        if (!in_array($role->name, $this->defaultRoles)) {
            $title = $request->input('title');
            $permissions = $request->input('permissions');
            $role->title = $title;
            $role->name = Str::slug($title);
            $role->save();

            if (!empty($permissions)) {
                $abilities = Bouncer::ability()->whereIn('name', $permissions)->pluck('id');
                $role->abilities()->sync($abilities);
            }

            flash('The Role has been updated', 'success');

            return redirect()->route('company.roles.index', ['company' => $company]);
        } else {
            return redirect()->route('company.roles.index', ['company' => $company]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @param Role    $role
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company, Role $role)
    {
        if (!in_array($role->name, $this->defaultRoles)) {
            $role->delete();
        }
        flash('The Role has been deleted', 'success');

        return redirect()->route('company.roles.index', ['company' => $company]);
    }

    public function getFormattedPermissions()
    {
        $permissions = Bouncer::ability()->all();

        foreach ($permissions as $key => $permission) {
            if ($permission->name === '*') {
                $permission->title = 'All Permissions';
                continue;
            }

            $type = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', str_replace('App\\Models\\', '', $permission->entity_type)));
            $permission->type = $type;
        }

        return $permissions;
    }
}
