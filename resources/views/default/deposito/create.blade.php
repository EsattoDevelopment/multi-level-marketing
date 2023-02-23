@extends('default.layout.main')

@section('content')
    <section class="content-header">
        <h1>
            Depositar
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Depositar</li>
        </ol>
    </section>

    <section class="content">
        @include('default.errors.errors')
    @if($sistema->deposito_is_active)
        <div class="col-lg-6" style="float: none; margin: 0 auto;">
            <div class="box box-solid">
                <div class="box-body" style="text-align: center;">
                    <form action="{{ route('deposito.depositar.store') }}" method="post" id="deposito">
                        {{ csrf_field() }}
                        <h2><b>Quanto</b> você quer <b>depositar</b>?</h2>
                        <input type="text" name="valor" placeholder="{{ $sistema->moeda }} 0,00" value="{{ old('valor') }}" data-affixes-stay="true" data-prefix="{{ $sistema->moeda }} " data-thousands="." data-decimal=",">
                        <p>Valor mínimo de {{ mascaraMoeda($sistema->moeda, $sistema->min_deposito, 2, true) }}.</p>
{{--                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal">CONFIRMAR</button>--}}
                        <button type="submit" class="btn btn-primary">CONFIRMAR</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Parabéns</h4>
                    </div>
                    <div class="modal-body">
                        <p>Este é o primeiro passo para seu sucesso como, para confirmar clique no botão contratar.</p>
                        <h2>$ {{ mascaraMoeda($sistema->moeda, $sistema->min_deposito, 2, true) }}</h2>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Contratar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @else
        <p>Deposito desativado</p>
    @endif
    </section>
@endsection

@section('style')
    <style>
        #deposito h2{margin-top: 5px;}
        #deposito input{ text-align: center; background: none; border: none; padding-bottom: 5px; border-bottom: 1px solid #000; font-size: 30px; font-family: 'Source Sans Pro',sans-serif; margin: 10px 0px 20px 0; max-width: 230px; outline: none; }
    </style>
@endsection

@section('script')
    <script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
    <script>
        $(function(){
            $("input[name='valor']").maskMoney().maskMoney('mask');
            $("input[name='valor']").on('keyup', function (e) {
              e = e || window.event;
              var key = e.which || e.charCode || e.keyCode,
              keyPressedChar,
              selection,
              startPos,
              endPos,
              value;
              selection = getInputSelection();
              startPos = selection.start;
              maskAndPosition(startPos + 1);
            });
        });
    </script>
@endsection
