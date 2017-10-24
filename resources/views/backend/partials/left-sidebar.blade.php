@section("left-sidebar")
    <div class="be-left-sidebar">
        <div class="left-sidebar-wrapper"><a href="#" class="left-sidebar-toggle">Dashboard</a>
            <div class="left-sidebar-spacer">
                <div class="left-sidebar-scroll">
                    <div class="left-sidebar-content">
                        <ul class="sidebar-elements">
                            <li class="divider">Menu</li>
                            <li class="{{ Ekko::isActiveRoute('backend.main') }}"><a href="{{ route('backend.main') }}"><i class="icon mdi mdi-home"></i><span>Dashboard</span></a>
                            </li>

                            @can('index', \App\Models\User::class)
                            <li class="parent"><a href="#"><i class="icon mdi mdi-face"></i><span>Users</span></a>
                                    <ul class="sub-menu">
                                        <li class="{{ Ekko::isActiveRoute('backend.user.index') }}"><a href="{{ route('backend.user.index') }}">View</a></li>
                                        <li class="{{ Ekko::isActiveRoute('backend.user.create') }}"><a href="{{ route('backend.user.create') }}">Create</a></li>
                                    </ul>
                                </li>
                            @endcan

                            @if(auth()->user()->isSuperAdmin())
                                <li class="divider">Access Control</li>
                                <li class="parent"><a href="#"><i class="icon mdi mdi-pin"></i><span>Roles</span></a>
                                    <ul class="sub-menu">
                                        <li class="{{ Ekko::isActiveRoute('backend.role.index') }}"><a href="{{ route('backend.role.index') }}">View</a></li>
                                        <li class="{{ Ekko::isActiveRoute('backend.role.create') }}"><a href="{{ route('backend.role.create') }}">Create</a></li>
                                    </ul>
                                </li>
                                <li class="parent"><a href="#"><i class="icon mdi mdi-pin"></i><span>Permissions</span></a>
                                    <ul class="sub-menu">
                                        <li class="{{ Ekko::isActiveRoute('backend.permission.index') }}"><a href="{{ route('backend.permission.index') }}">View</a></li>
                                        <li class="{{ Ekko::isActiveRoute('backend.permission.create') }}"><a href="{{ route('backend.permission.create') }}">Create</a></li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            {{--<div class="progress-widget">--}}
                {{--<div class="progress-data"><span class="progress-value">60%</span><span class="name">Current Project</span></div>--}}
                {{--<div class="progress">--}}
                    {{--<div style="width: 60%;" class="progress-bar progress-bar-primary"></div>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
@show