@section('sidenav-company')
    <div class="collection">
        @if(auth()->user()->company)
            <a href="{{ route('company.show') }}" class="collection-item {{ Ekko::isActiveRoute('company.show') }}">Company</a>
            @can('update', \App\Models\Company::class)
            <a href="{{ route('company.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.edit') }}">Details</a>
            @endcan
            @can('index', \App\Models\Role::class)
            <a href="{{ route('company.roles.index') }}" class="collection-item {{ Ekko::isActiveRoute('company.roles.*') }}">Roles</a>
            @endcan
            @can('create', \App\Models\CompanySettings::class)
            <a href="{{ route('company.settings.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.settings.edit') }}">Settings</a>
            @endcan
            @can('create', \App\Models\CompanyAddress::class)
            <a href="{{ route('company.address.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.address.edit') }}">Address</a>
            @endcan
            @can('owner', \App\Models\Company::class)
            <a href="{{ route('company.owner.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.owner.edit') }}">Owner</a>
            @endcan
            @can('update', \App\Models\Company::class)
            <a href="{{ route('company.users.index') }}" class="collection-item {{ Ekko::isActiveRoute('company.users.*') }}">Users</a>
            @endcan
            @can('create', \App\Models\CompanyUserRequest::class)
            <a href="{{ route('company.requests.index') }}" class="collection-item {{ Ekko::isActiveRoute('company.requests.*') }}">Requests</a>
            @endcan
        @endif
        {{--<a href="{{ route('migration.create') }}" class="collection-item {{ Ekko::isActiveRoute('migration.create') }}">Data Migration</a>--}}
    </div>
@show