  @include('default.layout.header')

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  @if($tipoUser == 4)
    @include('default.layout.sidebar-callcenter')
  @elseif($tipoUser == 3)
    @include('default.layout.sidebar-clinica')
  @elseif($master || $admin)
    @include('default.layout.sidebar-master')
  @elseif($admin)
    @include('default.layout.sidebar')
  @elseif($usuarioComum)
    @include('default.layout.sidebar-associado')
  @endif

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  @if(Session::has('mostrar_erro'))
    @include('default.errors.errors')
  @endif
    <!-- Content Header -->
      @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('default.layout.footer')