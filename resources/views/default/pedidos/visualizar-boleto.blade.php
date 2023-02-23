@extends('default.layout.main')

@section('content')

@include('default.errors.errors')

<section class="content-header">
    <h1>
            @if($msg != '')
                Boleto Bancário
            @else
                2ª Via Boleto Bancário
            @endif
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>{{ $tipo }}</li>
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
                    <i class="fa fa-indent"></i>

                    <h3 class="box-title">Nº de referência do seu {{ $tipo }}: #{{ $dados->id }}</h3>
                </div>
                <div class="box-body">
                    <dl class="dl-horizontal">
                        <dt>Status</dt>
                        <dd>{{ $dados->getRelation('status')->name }}</dd>
                        <dt>Associado</dt>
                        <dd>{{ $dados->getRelation('usuario')->name }}</dd>
                        <dt>Item Contratado</dt>
                        <dd>{{ $dados->getRelation('itens')->first()->name_item }}</dd>
                        <dt>Valor</dt>
                        <dd>{{mascaraMoeda($sistema->moeda, $dados->getRelation('dadosPagamento')->valor, 2, true)}}</dd>
                        <dt>Data</dt>
                        <dd>{{ $dados->data_compra_formatada }}</dd>
                    </dl>
                </div><!-- /.box-body -->

                <div class="box-header with-border">
                    <i class="glyphicon glyphicon-barcode"></i>
                    <h3 class="box-title">Dados do boleto</h3>
                </div>
                <!-- /.box-header -->
                @if($dados->status != 2 && $dados->status != 3)
                <div class="box-body">
                    @if(@isset($dados->getRelation('dadosPagamento')->dados_boleto['valor_reais']))
                        <div class="form-group col-xs-12">
                            <label for=""> Tarifa do boleto: </label>
                            <span>{{mascaraMoeda($sistema->moeda, $dados->getRelation('dadosPagamento')->tarifa_boleto, 2, true)}}</span>
                        </div>
                        <div class="form-group col-xs-12">
                            <label for="">Total do boleto: </label>
                            <span>{{mascaraMoeda($sistema->moeda, $dados->getRelation('dadosPagamento')->dados_boleto['valor_reais'], 2, true)}}</span>
                        </div>
                    @endif
                    <div class="form-group col-xs-12">
                        <label for=""> Data de Vencimento: </label>
                        <span>{{$dados->getRelation('dadosPagamento')->data_vencimento_formatada}}</span>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for=""> Linha Digitável: </label>
                        <span>{{$dados->getRelation('dadosPagamento')->dados_boleto['codigo_barra']}}</span>
                    </div>
                    <div class="form-group col-xs-12">
                        <a target="_blank" href="{{ $dados->getRelation('dadosPagamento')->dados_boleto['pdf'] }}" class="btn btn-warning">Visualizar / Imprimir Boleto <i class="glyphicon glyphicon-print"></i></a>
                    </div>
                </div>
                @endif
                <div class="box-footer">
                    <a href="{{ $url }}" class="btn btn-primary pull-right">Voltar para meus {{ $tipo2 }}</a>
                </div>
            </div><!-- /.box -->
        </div><!--/.col (left) -->
    </div>   <!-- /.row -->
</section>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
@endsection

@section('script')
<script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    $(function(){
        {!! $msg !!}
    });
</script>
@endsection