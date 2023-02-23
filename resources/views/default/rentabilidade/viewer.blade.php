@extends('default.layout.main')

@section('content')
    <section class="content-header">
        <h1>
            Visualização de pagamentos de rentabilidade
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('rentabilidade.index') }}"><i class="fa fa-line-chart"></i> Rentabilidade</a></li>
            <li class="active"><i class="glyphicon glyphicon-eye-open"></i> Visualização</li>
        </ol>
    </section>
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- form start -->
                <form role="form" action="{{ route('rentabilidade.update', $data) }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <!-- general form elements -->
                    <div class="box box-primary @if($sistema['rendimento_titulo'] == 0) hide @endif">
                        <div class="box-header with-border">
                            <h3 class="box-title">Rentabilidade títulos</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            {{--Pagamento de rentabilidade--}}
                            <div class="form-group col-xs-12 col-lg-12">
                                <div id="table">
                                    <table class="table table-bordered table-responsive-md table-striped text-center">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Nome</th>
                                            <th class="text-center">Valor fixo</th>
                                            <th class="text-center">Percentual</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($dados as $dado)
                                            @if($dado->titulo_id != null)
                                            <tr>
                                                <td>{{$dado->titulo}}</td>
                                                <td><input type="text" name="titulos[{{$dado->titulo_id}}][valor_fixo]" value="{{$dado->valor_fixo}}" class="form-control" placeholder="Rentabilidade por valor fixo" readonly/></td>
                                                <td><input type="text" name="titulos[{{$dado->titulo_id}}][percentual]" value="{{$dado->percentual}}" class="form-control" placeholder="Rentabilidade por percentual" readonly/></td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <div class="box box-primary @if($sistema['rendimento_item'] == 0) hide @endif">
                        <div class="box-header with-border">
                            <h3 class="box-title">Rentabilidade itens</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            {{--Pagamento de rentabilidade--}}
                            <div class="form-group col-xs-12 col-lg-12">
                                <div id="table">
                                    <table class="table table-bordered table-responsive-md table-striped text-center">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Nome</th>
                                            <th class="text-center">Valor fixo</th>
                                            <th class="text-center">Percentual</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($dados as $dado)
                                            @if($dado->item_id != null)
                                            <tr>
                                                <td>{{$dado->item}}</td>
                                                <td><input type="text" name="itens[{{$dado->item_id}}][valor_fixo]" value="{{$dado->valor_fixo}}" class="form-control" placeholder="Rentabilidade por valor fixo" readonly/></td>
                                                <td><input type="text" name="itens[{{$dado->item_id}}][percentual]" value="{{$dado->percentual}}" class="form-control" placeholder="Rentabilidade por percentual" readonly/></td>
                                            </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            Total pago:<h2>{{ mascaraMoeda($sistema->moeda, $totalPago, 2, true) }}</h2>
                        </div>
                    </div><!-- /.box -->

                    <div class="box box-warning">
                        <div class="box-footer">
                            <a href="{{ route('rentabilidade.index') }}" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div>
                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
@endsection

