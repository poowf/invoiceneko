@section('sidenav-company')
    <div class="collection">
        @if(auth()->user()->company)
            @can('owner', \App\Models\Company::class)
                <a href="{{ route('company.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.edit') }}">Company</a>
                <a href="{{ route('company.settings.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.settings.edit') }}">Settings</a>
                <a href="{{ route('company.address.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.address.edit') }}">Address</a>
            @endcan
            <a href="{{ route('company.owner.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.owner.edit') }}">Owner</a>
            <a href="{{ route('company.users.index') }}" class="collection-item {{ Ekko::isActiveRoute('company.users.*') }}">Users</a>
        @else
            <a href="{{ route('company.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.edit') }}">Company</a>
        @endif
        {{--<a href="{{ route('migration.create') }}" class="collection-item {{ Ekko::isActiveRoute('migration.create') }}">Data Migration</a>--}}
    </div>
@show