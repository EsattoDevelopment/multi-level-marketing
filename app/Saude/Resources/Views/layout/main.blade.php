  @include('ssm::layout.header')

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  @include('ssm::layout.sidebar')

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  @if(Session::has('mostrar_erro'))
    @include('ssm::errors.errors')
  @endif
    <!-- Content Header -->
      @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @include('ssm::layout.footer')