<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="@if(Auth::user()->image){{ route('imagecache', ['user', 'user/'.Auth::user()->image]) }}@else{{ route('imagecache', ['user', 'user-img.jpg']) }}@endif"
                     class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                {{--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>--}}
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">NAVEGAÇÃO</li>
            <li class="treeview">
                <a href="{{ route('home') }}">
                    <i class="fa fa-black-tie"></i>
                    <span>Home</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'guias') !== false)) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-user-plus"></i>
                    <span>Guias</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('saude.guias.aguardando') }}"><i class="fa fa-list"></i> Aguardando autorização</a></li>
                    <li><a href="{{ route('saude.guias.autorizadas') }}"><i class="fa fa-list text-green"></i> Autorizadas</a></li>
                    <li><a href="{{ route('saude.guias.create') }}"><i class="fa fa-calendar-plus-o"></i> Nova</a></li>
                </ul>
            </li>

            <li class="treeview">
                <a href="{{ route('saude.medicos.index') }}">
                    <i class="fa fa-user-md"></i>
                    <span>Medicos</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>