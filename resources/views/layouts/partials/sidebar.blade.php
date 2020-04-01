<!-- Left side column. contains the logo and sidebar -->
@if(!Auth::guest())
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->

        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ (Auth::user()->profile_image) ? Auth::user()->profile_image : asset('/img/avatar5.png') }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('messages.online') }}</a>
            </div>
        </div>

        <!-- search form (Optional) -->
        <form action="" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="{{ trans('messages.search') }}" value="{{ Request::input('q') }}"/>
            <span class="input-group-btn">
                <button type='submit' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
            </span>
            </div>
        </form>
        <!-- /.search form -->

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">Menù</li>


            <li class="treeview {{ (isset($resource) and in_array($resource,['user','role','permission'])) ? 'active' : '' }}"><a href="/extensionshome"><i class='fa fa-users'></i> <span>Coda Operatori</span><i class="fa fa-angle-left pull-right"></i></a>
            <li class="treeview {{ (isset($resource) and in_array($resource,['user','role','permission'])) ? 'active' : '' }}"><a href="/availablegenerale"><i class='fa fa-users'></i> <span>Available Extensions</span><i class="fa fa-angle-left pull-right"></i></a>
            <li class="treeview {{ (isset($resource) and in_array($resource,['user','role','permission'])) ? 'active' : '' }}"><a href="/dial_5"><i class='fa fa-users'></i> <span>Andamento Campagne</span><i class="fa fa-angle-left pull-right"></i></a>
            <li class="treeview {{ (isset($resource) and in_array($resource,['user','role','permission'])) ? 'active' : '' }}"><a href="/drag"><i class='fa fa-users'></i> <span>Confronta Campagne</span><i class="fa fa-angle-left pull-right"></i></a>
            <li class="header">Server</li>

            <li class="treeview {{ (isset($resource) and in_array($resource,['user','role','permission'])) ? 'active' : '' }}"><a href="/generale"><i class='fa fa-users'></i> <span>Cpu Usage & Log</span><i class="fa fa-angle-left pull-right"></i></a>

            <li class="header">{{ trans('messages.settings') }}</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="treeview {{ (isset($resource) and in_array($resource,['user','role','permission'])) ? 'active' : '' }}"><a href="#"><i class='fa fa-users'></i> <span>{{ trans('user.management') }}</span><i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    @if(Auth()->user()->hasPermission('Management\User\UserController@index'))
                        <li class="{{ (isset($resource) and $resource == 'user') ? 'active' : '' }}"><a href="{{ action('Management\User\UserController@index') }}"><i class='fa fa-circle'></i> <span>{{ trans('user.users') }}</span></a></li>
                    @endif
                    @if(Auth()->user()->hasPermission('Management\User\RoleController@index'))
                        <li class="{{ (isset($resource) and $resource == 'role') ? 'active' : '' }}"><a href="{{ action('Management\User\RoleController@index') }}"><i class='fa fa-circle'></i> <span>{{ trans('role.roles') }}</span></a></li>
                    @endif
                    @if(Auth()->user()->hasPermission('Management\User\PermissionController@index'))
                        <li class="{{ (isset($resource) and $resource == 'permission') ? 'active' : '' }}"><a href="{{ action('Management\User\PermissionController@index') }}"><i class='fa fa-circle'></i> <span>{{ trans('permission.permissions') }}</span></a></li>
                    @endif
                </ul>
            </li>



        @if(Auth()->user()->hasPermission('Management\User\UserController@index'))
                <li class="{{ (isset($resource) and $resource == 'log') ? 'active' : '' }}"><a href="{{ action('Management\LogController@index') }}"><i class='fa fa-archive'></i> <span>{{ trans('log.logs') }}</span></a></li>
            @endif
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
@endif