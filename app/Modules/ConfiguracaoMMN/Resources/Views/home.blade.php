@extends('configuracao::base')

@section('content')
    <section class="content">

        @include('errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Configuração MMN</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{ route('admin.configuracao.update', 1) }}" method="post">
                        {!! csrf_field() !!}
                        <div class="box-body">

                            <!-- form start -->
                            <div class="box-body">
                                <div class="form-group col-xs-12">
                                    <label for="exampleInputEmail1">Profundidade do unilevel</label>
                                    <input type="text" name="profundidade_unilevel" value="{{ old('profundidade_unilevel', $dados->profundidade_unilevel) }}" class="form-control" placeholder="Profundidade do unilevel">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="exampleInputEmail1">Bonus de primeiro cadastro <small>(Milhas)</small></label>
                                    <input type="text" name="bonus_milha_cadastro" value="{{ old('bonus_milha_cadastro', $dados->bonus_milha_cadastro) }}" class="form-control" placeholder="Bonus de primeiro cadastro small(Milhas)">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="exampleInputEmail1">Bonus de ciclo hotel <small>({{ $sistema->moeda }})</small></label>
                                    <input type="text" name="bonus_ciclo_hotel" value="{{ old('bonus_ciclo_hotel', $dados->bonus_ciclo_hotel) }}" class="form-control" placeholder="Bonus de ciclo hotel">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="exampleInputEmail1">Custo do novo hotel <small>({{ $sistema->moeda }})</small></label>
                                    <input type="text" name="custo_hotel" value="{{ old('custo_hotel', $dados->custo_hotel) }}" class="form-control" placeholder="Custo do novo hotel">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="exampleInputEmail1">Milhas ganhas no ciclo do hotel </label>
                                    <input type="text" name="milhas_ciclo_hotel" value="{{ old('milhas_ciclo_hotel', $dados->milhas_ciclo_hotel) }}" class="form-control" placeholder="Milhas ganhas no ciclo do hotel">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="exampleInputEmail1">Validade milhas de ciclo de hotel <small>em dias</small></label>
                                    <input type="text" name="validade_milhas_ciclo_hotel" value="{{ old('validade_milhas_ciclo_hotel', $dados->validade_milhas_ciclo_hotel) }}" class="form-control" placeholder="Validade milhas de ciclo de hotel">
                                </div>
                                <div class="form-group col-xs-12">
                                    <label for="exampleInputEmail1">Quantidade de diretos para qualificar <small>em dias</small></label>
                                    <input type="text" name="diretos_qualificacao" value="{{ old('diretos_qualificacao', $dados->diretos_qualificacao) }}" class="form-control" placeholder="Quantidade de diretos para qualificar">
                                </div>
                            </div>

                        </div><!-- /.box-body -->
                        <input type="hidden" name="_method" value="PUT">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
@endsection