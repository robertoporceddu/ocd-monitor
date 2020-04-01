<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/') }}" class="logo"  style="padding: 0 9px">
        <img src="{{ asset('/img/octopus-cm-inverse.png') }}" height="40" class="pull-left" style="margin: 6px -5px">
        <span class="logo-lg"><b>{{ env('APP_NAME') }}</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">{{ trans('adminlte_lang::message.togglenav') }}</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li id="notifications" class="dropdown messages-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span id="" class="label label-success counter hidden">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">You have <span class="counter">0</span> messages</li>
                        <li>
                            <!-- inner menu: contains the messages -->
                            <ul id="list" class="menu">

                            </ul><!-- /.menu -->
                        </li>
                        <li class="footer">
                            <button onclick="javascript:window.location='{{ action('NotificationController@index') }}'" class="pull-left btn-link">Visualizza Tutte</button>
                            <button class="btn-link pull-right" onclick="setViewedAt('all')">Chiudi Tutte</button>
                        </li>
                    </ul>
                </li><!-- /.messages-menu -->

                @if (Auth::user())
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="{{ (Auth::user()->profile_image) ? Auth::user()->profile_image : asset('/img/avatar5.png') }}" class="user-image" alt="User Image"/>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="{{ (Auth::user()->profile_image) ? Auth::user()->profile_image : asset('/img/avatar5.png') }}" class="img-circle" alt="User Image" />
                                <p>
                                    {{ Auth::user()->name }}
                                    <small>Login Nov. 2012</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ action('AccountController@get',['id' => Auth::user()->id]) }}" class="btn btn-default btn-flat">{{ trans('messages.profile') }}</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('/logout') }}" class="btn btn-default btn-flat">Logout</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- Control Sidebar Toggle Button -->
                {{--<li>--}}
                    {{--<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>--}}
                {{--</li>--}}
            </ul>
        </div>
    </nav>
</header>

