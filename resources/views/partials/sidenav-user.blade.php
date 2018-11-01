@section('sidenav-user')
    <div class="collection">
        <a href="{{ route('user.edit') }}" class="collection-item {{ Ekko::isActiveRoute('user.edit') }}">User</a>
        <a href="{{ route('user.security') }}" class="collection-item {{ Ekko::isActiveRoute('user.security') }}">Security</a>
        {{--<a href="{{ route('migration.create') }}" class="collection-item {{ Ekko::isActiveRoute('migration.create') }}">Data Migration</a>--}}
    </div>
@show