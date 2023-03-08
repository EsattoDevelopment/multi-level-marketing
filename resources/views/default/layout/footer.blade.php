<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.1.1
    </div>
    <strong>Empresa: {{ env('COMPANY_NAME_FOOTER', 'Nome empresa') }} - Copyright © {{ date('Y') }} // Software
        Gerenciamento by banco@diretoria.net © .</strong> Todos os direitos reservados.
</footer>

<script>
    paceOptions = {
        // Disable the 'elements' source
        elements: false,

        // Only show the progress on regular and ajax-y page navigation,
        // not every request
        restartOnRequestAfter: false
    }
</script>
<!-- jQuery 2.2.0 -->
<script src="{{ asset('plugins/jQuery/jQuery-2.2.0.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('plugins/fastclick/fastclick.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/app.min.js') }}"></script>
<script src="{{ asset('plugins/pace/pace.js') }}"></script>

@if( ! Auth::user()->hasRole('master') && ! Auth::user()->hasRole('admin') )
  @foreach($modais as $modal)
    @if(Cookie::get('modal_' . $modal->id) === null)
      <div class="modal fade" id="modal_{{ $modal->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="">{{ $modal->title }}</h4>
                  </div>
                  <div class="modal-body">
                      {!! $modal->descricao !!}
                      @if($modal->nomeArquivo)
                        <img class="img-responsive" src="{{ route('imagecache', ['visualizar', $modal->nomeArquivo]) }}" alt="{{ $modal->title }}">
                      @endif
                  </div>
              </div>
          </div>
      </div>
      <script>
          $(function(){
            $("#modal_{{ $modal->id }}").modal("show");
          });
      </script>
      {{ Cookie::queue(Cookie::forever('modal_' . $modal->id, true)) }}
    @endif
  @endforeach
@endif

@yield('script')

</body>
</html>
