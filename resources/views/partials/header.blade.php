@section("header")
    <header>
        <div class="navbar">
            <nav>
                <div class="row">
                    <div class="col l12">
                        <div class="nav-wrapper">
                            <a href="#" data-target="mobile-menu" class="sidenav-trigger"><span>&#9776;</span></a>
                            <a href="{{ route('main') }}" class="logo black-text"><img src="{{ asset('assets/img/logo.png') }}"><span class="logo-text">{{ config('app.name') }}</span></a>
                            <ul class="left hide-on-med-and-down">
                                <li><a href="{{ route('main') }}">Home</a></li>
                            </ul>

                            <ul class="right hide-on-med-and-down">
                                @if(Auth::check())
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
                                        <a class="waves-effect waves-dark btn-link btn dropdown-trigger" href="javascript:;" data-target="company-dropdown-navigation">company</a>
                                    </li>
                                    <ul id="company-dropdown-navigation" class="dropdown-content" style="margin-left: 15px;">
                                        @foreach(auth()->user()->companies as $company)
                                        <li>
                                            <form method="post" action="{{ route('company.switch') }}">
                                                {{ csrf_field() }}
                                                <input id="domain_name" name="domain_name" class="form-control" type="hidden" value="{{ $company->domain_name }}">
                                                <button class="null-btn" type="submit">{{ $company->name }}t</button>
                                            </form>
                                        </li>
                                        @endforeach
                                    </ul>
                                    <li>
                                        <a class="waves-effect waves-dark btn-link btn dropdown-trigger" href="javascript:;" data-target="dropdown-navigation">
                                            <div class="dropdown-text" style="width: 115px; line-height: 35px; margin-right: 15px;">My Account</div>
                                            <div class="dropdown-text icon">&#9698;</div>
                                        </a>
                                    </li>
                                    <ul id="dropdown-navigation" class="dropdown-content" style="margin-left: 15px;">
                                        @if(auth()->user()->company)
                                            <li><a href="{{ route('company.show', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Company</a></li>
                                        @else
                                            <li><a href="{{ route('company.edit', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Company</a></li>
                                        @endif
                                        @can('index', \App\Models\ItemTemplate::class)
                                        <li><a href="{{ route('itemtemplate.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Item Templates</a></li>
                                        @endcan
                                        <li><a href="{{ route('user.edit') }}">User</a></li>
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
                                <li><a href="{{ route('main', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">HOME</a></li>
                                <hr>
                                @if(Auth::check())
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
                                        <a class="waves-effect waves-dark btn-link btn dropdown-trigger" href="javascript:;" data-target="dropdown-mobile-navigation">
                                            <div class="dropdown-text">My Account</div>
                                            <div class="dropdown-text icon">&#9698;</div>
                                        </a>
                                    </li>
                                    <ul id="dropdown-mobile-navigation" class="dropdown-content" style="margin-left: 10px; margin-top: 15px;">
                                        @if(auth()->user()->company)
                                            <li><a href="{{ route('company.show', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Company</a></li>
                                        @else
                                            <li><a href="{{ route('company.edit', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Company</a></li>
                                        @endif
                                        @can('index', \App\Models\ItemTemplate::class)
                                        <li><a href="{{ route('itemtemplate.index', [ 'company' => \App\Library\Poowf\Unicorn::getCompanyKey() ]) }}">Item Templates</a></li>
                                        @endcan
                                        <li><a href="{{ route('user.edit') }}">User</a></li>
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