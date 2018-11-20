@section('sidenav-company')
    <div class="collection">
        @if(app('request')->route('company')->hasUser(auth()->user()))
            <a href="{{ route('company.show', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="collection-item {{ Ekko::isActiveRoute('company.show') }}">Company</a>
            @can('update', \App\Models\Company::class)
            <a href="{{ route('company.edit', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="collection-item {{ Ekko::isActiveRoute('company.edit') }}">Details</a>
            @endcan
            @can('index', \App\Models\Role::class)
            <a href="{{ route('company.roles.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="collection-item {{ Ekko::isActiveRoute('company.roles.*') }}">Roles</a>
            @endcan
            @can('create', \App\Models\CompanySettings::class)
            <a href="{{ route('company.settings.edit', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="collection-item {{ Ekko::isActiveRoute('company.settings.edit') }}">Settings</a>
            @endcan
            @can('create', \App\Models\CompanyAddress::class)
            <a href="{{ route('company.address.edit', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="collection-item {{ Ekko::isActiveRoute('company.address.edit') }}">Address</a>
            @endcan
            @can('owner', \App\Models\Company::class)
            <a href="{{ route('company.owner.edit', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="collection-item {{ Ekko::isActiveRoute('company.owner.edit') }}">Owner</a>
            @endcan
            @can('update', \App\Models\Company::class)
            <a href="{{ route('company.users.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="collection-item {{ Ekko::isActiveRoute('company.users.*') }}">Users</a>
            @endcan
            @can('create', \App\Models\CompanyUserRequest::class)
            <a href="{{ route('company.requests.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="collection-item {{ Ekko::isActiveRoute('company.requests.*') }}">Requests</a>
            @endcan
        @endif
        {{--<a href="{{ route('migration.create') }}" class="collection-item {{ Ekko::isActiveRoute('migration.create') }}">Data Migration</a>--}}
    </div>
@show