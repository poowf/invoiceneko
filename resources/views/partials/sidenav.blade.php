@section('sidenav')
    <div class="collection">
        <a href="{{ route('company.edit') }}" class="collection-item {{ Ekko::isActiveRoute('company.edit') }}">Company</a>
        <a href="{{ route('user.edit') }}" class="collection-item {{ Ekko::isActiveRoute('user.edit') }}">Settings</a>
        <a href="{{ route('migration.create') }}" class="collection-item {{ Ekko::isActiveRoute('migration.create') }}">Data Migration</a>
    </div>
@show