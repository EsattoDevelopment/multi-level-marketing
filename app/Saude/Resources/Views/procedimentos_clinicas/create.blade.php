@extends('layout.main')

@section('content')
    <section class="content">

        @include('errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('saude.procedimentos.store') }}" method="post">
                    {!! csrf_field() !!}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cadastro de Procedimentos</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input type="text" name="codigo" value="{{ old('codigo') }}" class="form-control" placeholder="Código">
                            </div>
                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Nome">
                            </div>
                            <div class="form-group">
                                <label for="valor">Valor</label>
                                <input type="text" name="valor" value="{{ old('valor') }}" class="form-control" placeholder="Valor">
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('saude.procedimentos.index') }}" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div><!-- /.box -->
                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
@endsection

@section('script')
@endsection