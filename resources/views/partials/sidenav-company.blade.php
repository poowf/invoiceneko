@section('sidenav-company')
    <div class="collection">
        @if(auth()->user()->company)
            <a href="{{ route('company.show') }}" class="collection-item {{ Ekko::isActiveRoute('company.show') }}">Company</a>
            @can('owner', \App\Models\Company::class)
                <a href="{{ route('company.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.edit') }}">Details</a>
                <a href="{{ route('company.roles.index') }}" class="collection-item {{ Ekko::isActiveRoute('company.roles.*') }}">Roles</a>
                <a href="{{ route('company.settings.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.settings.edit') }}">Settings</a>
                <a href="{{ route('company.address.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.address.edit') }}">Address</a>
                <a href="{{ route('company.owner.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.owner.edit') }}">Owner</a>
                <a href="{{ route('company.users.index') }}" class="collection-item {{ Ekko::isActiveRoute('company.users.*') }}">Users</a>
                <a href="{{ route('company.requests.index') }}" class="collection-item {{ Ekko::isActiveRoute('company.requests.*') }}">Requests</a>
            @endcan
        @else
            <a href="{{ route('company.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.edit') }}">Details</a>
        @endif
        {{--<a href="{{ route('migration.create') }}" class="collection-item {{ Ekko::isActiveRoute('migration.create') }}">Data Migration</a>--}}
    </div>
@show