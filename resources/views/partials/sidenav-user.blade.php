@section('sidenav-user')
    <div class="collection">
        <a href="{{ route('user.edit', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="collection-item {{ Ekko::isActiveRoute('user.edit') }}">Profile</a>
        <a href="{{ route('user.security', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}" class="collection-item {{ Ekko::areActiveRoutes(['user.security', 'user.multifactor.*']) }}">Security</a>
        {{--<a href="{{ route('migration.create') }}" class="collection-item {{ Ekko::isActiveRoute('migration.create') }}">Data Migration</a>--}}
    </div>
@show