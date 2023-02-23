@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('user.{user}.faixas-cep.store', [$user->id]) }}" method="post">
                    {!! csrf_field() !!}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cadastro de Faixa de CEP</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inicio">Inicio</label>
                                <input type="text" name="inicio" value="{{ old('inicio') }}" class="form-control" placeholder="Inicio">
                            </div>
                            <div class="form-group">
                                <label for="fim">Fim</label>
                                <input type="text" name="fim" value="{{ old('fim') }}" class="form-control" placeholder="Fim">
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('user.{user}.faixas-cep.index', [$user->id]) }}" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div><!-- /.box -->
                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
@endsection

@section('script')
@endsection