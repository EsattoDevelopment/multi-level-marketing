@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Seja um Agente
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Seja um agente</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <form action="{{ route('pedido.store') }}" method="post">
            {{ csrf_field() }}
            <div class="box-body">
                @forelse($itens->chunk(4) as $blocoItens)
                    <div class="row">
                        @foreach($blocoItens as $item)
                            <div class="col-lg-12 col-sm-12 col-md-6 col-xs-12">
                                <div class="box bg-gray-light" {!! ($item->cor_item ? 'style="border-top: 3px solid '.$item->cor_item.';"' : '') !!}>
                                    @if ($item->imagem)
                                    <figure class="image">
                                        <img class="img-rounded img" src="{{ route('imagecache', ['pacotes', 'itens/'. $item->id . '/' .  $item->imagem]) }}" alt="{{ $item->name }}" style="max-width: 100%;">
                                    </figure>
                                    @endif
                                    <div style="padding: 10px;">
                                        <h3>Valor: {{mascaraMoeda($sistema->moeda, $item->valor, 2, true)}}</h3>
                                        {!! $item->descricao !!}
                                    </div>

                                    <div class="text-center">
                                        @if(Auth::user()->ultimoMovimento() ? Auth::user()->ultimoMovimento()->saldo : 0 >= $item->valor)
                                            <input type="checkbox" id="aceite_consultor"  name="aceite_consultor"> Li e aceito todos os <a href="{{ asset('docs/Aceite-agente.pdf') }}" target="_blank">termos</a> do contrato de licenciado<br><br>
                                            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-item-agente" {!! ($item->cor_item ? 'style="background-color: '.$item->cor_item.'; border-color: '.$item->cor_item.'; color: #FFF;"' : '') !!}>
                                                Contratar
                                            </button>
                                        @else
                                            <span class="label label-default bg-black">Saldo Insuficiente</span><br><br>
                                            <a href="{{ route('deposito.depositar') }}" class="btn btn-success btn-sm text-black text-bold"><i class="fa fa-plus"></i> Adicionar crédito</a>
                                        @endif
                                    </div>
                                    <br><br>
                                </div>
                            </div>

                            <div class="modal fade" id="modal-item-agente" style="display: none;">
                                <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title">Parabéns</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>Este é o primeiro passo para seu sucesso como <strong>{{ $item->name }}</strong>, para confirmar clique no botão contratar.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancelar</button>
                                        <form action="{{ route('pedido.store') }}" id="form-pedido" method="post">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="item" value="{{ $item->id }}">
                                            <input type="hidden" name="qtd_itens" value="1">
                                            <button type="submit" class="btn btn-primary">Contratar</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                                </div>
                            <!-- /.modal-dialog -->
                            </div>
                        @endforeach
                    </div>
                    <!-- /.row -->
                @empty
                @endforelse
        </form>
    </section>
@endsection

@section('style')
    <!-- iCheck -->
    <link rel="stylesheet" href="/plugins/iCheck/square/red.css">
    <style>
        .small-box-body, .check-item{
            background-color: #FFF;
            color: #000;
        }
    </style>
@endsection

@section('script')
    <!-- iCheck -->
    <script src="/plugins/iCheck/icheck.min.js"></script>

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });

            $("div[id='modal-item-agente']").on('show.bs.modal', function (e) {
                var checked = $("#aceite_consultor").parent('[class*="icheckbox"]').hasClass("checked");

                if (!checked) {
                    alert('Você precisa aceitar os termos para se tornar um agente.');
                    this.modal('hide');
                    return;
                }
            });
        });
    </script>
@endsection