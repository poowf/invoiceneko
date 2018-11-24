@section("header")
    <header>
        <div class="navbar">
            <nav>
                <div class="row">
                    <div class="col l12">
                        <div class="nav-wrapper">
                            <a href="#" data-target="mobile-menu" class="sidenav-trigger"><span class="white-text">&#9776;</span></a>
                            <a href="{{ route('main') }}" class="logo black-text"><img src="{{ asset('assets/img/logo.png') }}"><span class="logo-text">{{ config('app.name') }}</span></a>
                            <ul class="left hide-on-med-and-down">
                            </ul>

                            <ul class="right hide-on-med-and-down">
                                @if(Auth::check())
                                    @if(app('request')->route('company'))
                                    <li><a href="{{ route('dashboard', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Dashboard</a></li>
                                    @can('index', \App\Models\Quote::class)
                                    <li><a href="{{ route('quote.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Quotes</a></li>
                                    @endcan
                                    @can('index', \App\Models\Invoice::class)
                                    <li><a href="{{ route('invoice.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Invoices</a></li>
                                    @endcan
                                    @can('index', \App\Models\Client::class)
                                    <li><a href="{{ route('client.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Clients</a></li>
                                    @endcan
                                    @can('index', \App\Models\Payment::class)
                                    <li><a href="{{ route('payment.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Payments</a></li>
                                    @endcan
                                    <li>
                                        <a class="btn btn-link btn-floating waves-effect waves-dark company-selector-btn dropdown-trigger" href="javascript:;" data-target="company-dropdown-navigation"><i class="mdi mdi-office-building"></i></a>
                                    </li>
                                    <ul id="company-dropdown-navigation" class="dropdown-content top-arrow">
                                        @foreach(auth()->user()->companies as $company)
                                        <li class="@if(app('request')->route('company')->domain_name == $company->domain_name){{ 'active' }}@endif">
                                            <form method="post" action="{{ route('company.switch') }}">
                                                {{ csrf_field() }}
                                                <input id="domain_name" name="domain_name" class="form-control" type="hidden" value="{{ $company->domain_name }}">
                                                <button class="null-btn" type="submit">{{ $company->name }}</button>
                                            </form>
                                        </li>
                                        @endforeach
                                        <li>
                                            <a href="{{ route('company.create') }}">Add Company</a>
                                        </li>
                                    </ul>
                                    @endif
                                    <li>
                                        <a class="btn btn-link waves-effect waves-dark dropdown-trigger my-account-btn" href="javascript:;" data-target="dropdown-navigation">
                                            <div class="dropdown-text" style="width: 115px; line-height: 35px; margin-right: 15px;">My Account</div>
                                        </a>
                                    </li>
                                    <ul id="dropdown-navigation" class="dropdown-content" style="margin-left: 15px;">
                                        @if(app('request')->route('company'))
                                            <li><a href="{{ route('company.show', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Company</a></li>
                                            <li><a href="{{ route('user.edit', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">User</a></li>
                                            @can('index', \App\Models\ItemTemplate::class)
                                            <li><a href="{{ route('itemtemplate.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Item Templates</a></li>
                                            @endcan
                                        @endif
                                        <li>
                                            <form method="post" action="{{ route('auth.destroy') }}">
                                                {{ csrf_field() }}
                                                <button class="signmeout-btn signout-btn null-btn" type="submit">Sign Out</button>
                                            </form>
                                        </li>
                                    </ul>
                                @else
                                    <li><a href="{{ route('auth.show') }}">Sign In</a></li>
                                    <li><a href="{{ route('start') }}">Start Here</a></li>
                                @endif
                            </ul>
                            <ul class="sidenav" id="mobile-menu">
                                @if(Auth::check())
                                <li class="sidenav-profile">
                                    <div class="user-view">
                                        <div class="background">
                                            <img src="images/office.jpg">
                                        </div>
                                        <a href="#user"><img class="circle" src="{{ auth()->user()->gravatar }}"></a>
                                        <a href="#name"><span class="white-text name">{{ auth()->user()->full_name }}</span></a>
                                        <a href="#email"><span class="white-text email">{{ auth()->user()->email }}</span></a>
                                    </div>
                                </li>
                                <li class="sidenav-company">
                                    <ul class="collapsible collapsible-accordion">
                                        <li>
                                            <a class="collapsible-header white-text"><i class="mdi mdi-office-building white-text"></i>@if(app('request')->route('company')){{ app('request')->route('company')->name ?? 'New Company' }}@else{{ 'New Company' }}@endif<i class="material-icons right">arrow_drop_down</i></a>
                                            <div class="collapsible-body">
                                                <ul>
                                                    @foreach(auth()->user()->companies as $company)
                                                        <li class="@if(app('request')->route('company')->domain_name == $company->domain_name){{ 'active' }}@endif">
                                                            <form method="post" action="{{ route('company.switch') }}">
                                                                {{ csrf_field() }}
                                                                <input id="domain_name" name="domain_name" class="form-control" type="hidden" value="{{ $company->domain_name }}">
                                                                <button class="null-btn" type="submit">{{ $company->name }}</button>
                                                            </form>
                                                        </li>
                                                    @endforeach
                                                    <li>
                                                        <a href="{{ route('company.create') }}">Add Company</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                    @if(app('request')->route('company'))
                                    <li><a href="{{ route('dashboard', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Dashboard</a></li>
                                    @can('index', \App\Models\Quote::class)
                                    <li><a href="{{ route('quote.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Quotes</a></li>
                                    @endcan
                                    @can('index', \App\Models\Invoice::class)
                                    <li><a href="{{ route('invoice.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Invoices</a></li>
                                    @endcan
                                    @can('index', \App\Models\Client::class)
                                    <li><a href="{{ route('client.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Clients</a></li>
                                    @endcan
                                    @can('index', \App\Models\Payment::class)
                                    <li><a href="{{ route('payment.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Payments</a></li>
                                    @endcan
                                    @endif
                                    <li>
                                        <a class="btn btn-link waves-effect waves-dark dropdown-trigger my-account-mobile-btn" href="javascript:;" data-target="dropdown-mobile-navigation">
                                            <div class="dropdown-text">My Account</div>
                                        </a>
                                    </li>
                                    <ul id="dropdown-mobile-navigation" class="dropdown-content">
                                        @if(app('request')->route('company'))
                                            <li><a href="{{ route('company.show', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Company</a></li>
                                            <li><a href="{{ route('user.edit', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">User</a></li>
                                            @can('index', \App\Models\ItemTemplate::class)
                                            <li><a href="{{ route('itemtemplate.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Item Templates</a></li>
                                            @endcan
                                        @endif
                                        <li>
                                            <form method="post" action="{{ route('auth.destroy') }}">
                                                {{ csrf_field() }}
                                                <button class="signmeout-btn signout-mobile-btn null-btn" type="submit">Sign Out</button>
                                            </form>
                                        </li>
                                    </ul>
                                @else
                                    <li><a href="{{ route('auth.show') }}">Sign In</a></li>
                                    <li><a href="{{ route('start') }}">Start Here</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
@show