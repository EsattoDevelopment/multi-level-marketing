@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    {{--//TODO voltar na rapimed--}}
{{--    @if(in_array($item->tipo_pedido_id, [1, 3]))
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            Atenção! Há o acrescimo de {{ $sistema->moeda }}5,00 (cinco reais), por dependente cadastrado e ativo.
        </div>
    @endif--}}

    <section class="content-header">
        <h1>
            Pacote
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Pacotes > {{ $item->name }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box bg-gray-light">
            <form action="{{ route('pedido.store') }}" id="form-pedido" method="post">
                {{ csrf_field() }}
                <div class="box-body">
                    <h1 class="text-orange">{{ $item->name }}</h1>
                    <div class="row col-xs-12">
                        <div class="col-sm-4" style="width: 360px; margin: 0 50px;">
                            <figure>
                                <img class="img-rounded"
                                     src="{{ route('imagecache', ['pacotes', 'itens/'. $item->id . '/' .  $item->imagem]) }}"
                                     alt="">
                            </figure>
                            <article class="text-green" style="font-size: 1.5em; font-weight: bolder;">
                                {{ $sistema->moeda }}{{ $item->valor }}
                            </article>
                        </div>
                        <div class="col-sm-8" style="font-size: 1.5em;">
                            <p>{{ $item->chamada }}</p>

                            {!! $item->descricao !!}
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <div class="box-footer">
                    @if($item->tipo_pedido_id == 6)
                        <div class="form-group col-xs-12">
                            <label for="">Quantidade de dependentes</label>
                            <input class="form-control" type="number" name="qtd_itens" min="1" value="1">
                        </div>
                    @endif

                        @if($item->tipo_pedido_id != 6)
                            <input type="hidden" name="qtd_itens" value="1">
                        @endif
                    <input name="item" value="{{ $item->id }}" type="hidden">
                    @if(Auth::user()->dependentes()->count() > 0)
                            {{--//TODO pra sistema de saude--}}
                        {{--<input name="sen-dependente" value="2" type="hidden">--}}
                        <button type="submit" class="btn btn-primary">
                            {{--//TODO pra sistema de saude--}}
                            {{--@if(in_array($item->tipo_pedido_id, [5, 6]))
                                Realizar pedido
                            @else
                                Aderir ao plano -
                            @endif--}}
                                Realizar pedido
                            {{ $sistema->moeda }} <b class="valor_item">{{ $item->valor }}</b></button>
                    @else
                        @if(\Entrust::hasRole('user-empresa'))
                                {{--//TODO pra sistema de saude--}}
                            {{--<input name="sen-dependente" value="3" type="hidden">--}}
                            <button type="submit" class="btn btn-primary">
                                {{--//TODO pra sistema de saude--}}
                                {{--@if(in_array($item->tipo_pedido_id, [5, 6]))
                                    Realizar pedido
                                @else
                                    Aderir ao plano -
                                @endif--}}
                                Realizar pedido
                                {{ $sistema->moeda }} <b class="valor_item">{{ $item->valor }}</b></button>
                        @else
                                <button type="submit" class="btn btn-primary">
                                    {{--//TODO pra sistema de saude--}}
                                    {{--@if(in_array($item->tipo_pedido_id, [5, 6]))
                                        Realizar pedido
                                    @else
                                        Aderir ao plano -
                                    @endif--}}
                                    Realizar pedido
                                    {{ $sistema->moeda }} <b class="valor_item">{{ $item->valor }}</b></button>

                                {{--//TODO pra sistema de saude--}}

                                {{--<a href="javascript:;" id="submit-form" class="btn btn-primary">Aderir ao plano - {{ $sistema->moeda }} <b class="valor_item">{{ $item->valor }}</b></a>--}}
                        @endif
                    @endif
                    <a href="{{ route('pedido.create') }}" class="btn btn-default pull-right">Voltar</a>
                </div>
            </form>

            <!-- Modal --> {{--//TODO pra sistema de saude--}}
            <div class="modal modal-warning fade" id="myModal" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Atenção</h4>
                        </div>
                        <div class="modal-body">
                            Você deve inserir os dependentes antes de escolher o plano.
                            <br>
                            <br>
                            <input type="checkbox" id="sen-dependente" name="sen-dependente" value="true"> <b
                                    class="text-black">Desejo continuar sem dependentes</b>
                        </div>

                        <div class="modal-footer">
                            {{--<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>--}}
                            <a href="{{ route('saude.dependentes.create', Auth::user()->id) }}"
                               class="btn btn-danger pull-left">Ir para cadastro de dependentes</a>
                            <a href="javascript:;" id="continuar" class="btn btn-success">Continuar sem dependentes</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(function () {
            @if(Auth::user()->dependentes()->count() == 0)
                @if(!\Entrust::hasRole('user-empresa'))
                    $('#submit-form').click(function () {
                        $('#myModal').modal();
                    });
                @endif
            @endif

            $('input[name="qtd_itens"]').change(function(){
                var valor = $(this).val();
                valor = valor * 5;

                $('.valor_item').html(valor);
            });

            /*$('#continuar').click(function () {
                if (!$('#sen-dependente').is(':checked')) {
                    alert('Para prosseguir sem dependentes, concorde em seguir sem dependentes!');
                    $('#sen-dependente').focus();
                }else{
                    $('<input />').attr('type', 'hidden')
                        .attr('name', "sen-dependente")
                        .attr('value', "1")
                        .appendTo('#form-pedido');

                    $('#form-pedido').submit();
                }
            });*/

        });
    </script>
@endsection