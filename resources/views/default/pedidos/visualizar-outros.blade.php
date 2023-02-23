@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Pedido #{{ $dados->id }}
        </h1>
        @if(isset($movimento))
            <span>
            <small>
                Saldo da Sua Carteira: {{ $sistema->moeda }} {{$movimento->saldo}}
            </small>
        </span>
        @endif
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Pedidos</li>
            <li class="active">#{{ $dados->id }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-user"></i>

                        <h3 class="box-title">Dados do pedido</h3>
                    </div>
                    <div class="box-body">
                        <dl class="dl-horizontal">
                            <dt>Status</dt>
                            <dd>{{ $dados->getRelation('status')->name }}</dd>
                            <dt>Comprador</dt>
                            <dd>{{ $dados->getRelation('usuario')->name }}</dd>

                            <dt>Usuário</dt>
                            <dd>{{ $dados->getRelation('usuario')->username }}</dd>

                            <dt>Item</dt>
                            <dd>{{ $dados->getRelation('itens')->first()->name_item }}</dd>
                            <dt>Valor</dt>
                            <dd>{{ $dados->getRelation('dadosPagamento')->valor }}</dd>
                            <dt>Data compra</dt>
                            <dd>{{ $dados->data_compra->format('d/m/Y') }}</dd>
                            <dt>Pontos válidos</dt>
                            <dd>{{ $dados->getRelation('itens')->first()->getRelation('itens')->pontos_binarios }}</dd>
                            <dt>Milhas</dt>
                            <dd>{{ $dados->getRelation('itens')->first()->getRelation('itens')->milhas }}</dd>
                        </dl>
                    </div><!-- /.box-body -->

                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-list-alt"></i>
                        <h3 class="box-title">Formas de pagamento</h3>
                    </div>
                    <!-- /.box-header -->
                    @if($dados->status != 2 && $dados->status != 3)
                        <div class="box-body">
                            @if(isset($contas))
                                <div class="col-md-3">
                                    <form method="post" target="_blank" action="{{ route('pedido.usuario.pedido.pagar.boleto', [Auth::user()->id, $dados->id]) }}">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="metodo_pagamento" value="1">
                                        <input type="hidden" name="pedido_id" value="{{ $dados->id }}">
                                        <input type="hidden" name="user_id" value="{{ $dados->user_id }}">
                                        @foreach($contas as $conta)
                                            <button name="boleto" value="{{ $conta->getRelation('banco')->codigo }}" class="btn btn-{{ $conta->getRelation('banco')->class_cor }}">Imprimir Boleto <i class="glyphicon glyphicon-barcode"></i></button>
                                        @endforeach
                                    </form>
                                </div>
                            @endif


                            @if($movimento)
                                @if($movimento->saldo >= $dados->valor_total)
                                    <div class="col-md-3">
                                        <form method="post" action="{{ route('pedido.usuario.pedido.pagar.saldo', [Auth::user()->id, $dados->id]) }}">
                                            {!! csrf_field() !!}
                                            <input type="hidden" name="metodo_pagamento" value="6">
                                            <input type="hidden" name="pedido_id" value="{{ $dados->id }}">
                                            <input type="hidden" name="user_id" value="{{ $dados->user_id }}">
                                            <button  class="btn btn-success">Pagar com saldo <i class="fa fa-money"></i></button>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                    <div class="box-footer">
                        <a href="{{ route('pedido.bonus') }}" class="btn btn-primary pull-right">Voltar</a>
                    </div>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section>
@endsection

@section('style')

@endsection

@section('script')

@endsection