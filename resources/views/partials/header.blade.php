@section("header")
    <header>
        <div class="navbar">
            <nav>
                <div class="row">
                    <div class="col l12">
                        <div class="nav-wrapper">
                            <a href="#" data-target="mobile-menu" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                            <a href="{{ route('main') }}" class="logo"><img src="{{ asset('assets/img/logo.png') }}" alt="Invoice Plz"></a>
                            <ul class="left hide-on-med-and-down">
                                <li><a href="{{ route('main') }}">Home</a></li>
                            </ul>

                            <ul class="right hide-on-med-and-down">
                                @if(Auth::check())
                                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li><a href="{{ route('quotes.index') }}">Quotes</a></li>
                                    <li><a href="{{ route('invoice.index') }}">Invoices</a></li>
                                    <li><a href="{{ route('client.index') }}">Clients</a></li>
                                    <li><a href="{{ route('payment.index') }}">Payments</a></li>
                                    <li><a class="waves-effect waves-dark btn-link btn dropdown-trigger" href="javascript:;" data-target="dropdown-navigation">My Account<i class="material-icons right" style="line-height: 35px;">arrow_drop_down</i></a></li>
                                    <ul id="dropdown-navigation" class="dropdown-content" style="margin-left: 15px;">
                                        <li><a href="{{ route('company.edit') }}">Company</a></li>
                                        <li><a href="{{ route('company.settings.edit') }}">Settings</a></li>
                                        <li>
                                            <form method="post" action="{{ route('auth.destroy') }}">
                                                {{ csrf_field() }}
                                                <button class="signmeout-btn signout-btn null-btn" type="submit">Sign Out</button>
                                            </form>
                                        </li>
                                    </ul>
                                @else
                                    <li><a href="{{ route('auth.show') }}">Sign In</a></li>
                                    <li><a href="{{ route('user.create') }}">Sign Up</a></li>
                                @endif
                            </ul>
                            <ul class="sidenav" id="mobile-menu">
                                <li><a href="{{ route('main') }}">HOME</a></li>
                                <hr>
                                @if(Auth::check())
                                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                    <li><a href="{{ route('quotes.index') }}">Quotes</a></li>
                                    <li><a href="{{ route('invoice.index') }}">Invoices</a></li>
                                    <li><a href="{{ route('client.index') }}">Clients</a></li>
                                    <li><a href="{{ route('payment.index') }}">Payments</a></li>
                                    <li><a class="waves-effect waves-dark btn-link btn dropdown-trigger" href="javascript:;" data-target="dropdown-mobile-navigation">My Account<i class="material-icons right">arrow_drop_down</i></a></li>
                                    <ul id="dropdown-mobile-navigation" class="dropdown-content" style="margin-left: 10px; margin-top: 15px;">
                                        <li><a href="{{ route('company.edit') }}">Company</a></li>
                                        <li><a href="{{ route('company.settings.edit') }}">Settings</a></li>
                                        <li>
                                            <form method="post" action="{{ route('auth.destroy') }}">
                                                {{ csrf_field() }}
                                                <button class="signmeout-btn signout-mobile-btn null-btn" type="submit">Sign Out</button>
                                            </form>
                                        </li>
                                    </ul>
                                @else
                                    <li><a href="{{ route('auth.show') }}">Sign In</a></li>
                                    <li><a href="{{ route('user.create') }}">Sign Up</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
@show