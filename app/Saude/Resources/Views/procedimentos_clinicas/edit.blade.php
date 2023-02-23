@extends('layout.main')

@section('content')
    <section class="content">
        @include('errors.errors')
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <!-- form start -->
                <form role="form" action="{{ route('saude.procedimentos_clinica.update', [$dados->user_id, $dados->procedimento_id]) }}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="PUT">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edição de Procedimento</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name">Procedimento</label>
                                <input type="text" name="" value="{{ $dados->procedimento->name  }}" class="form-control" placeholder="Nome" disabled>
                            </div>
                            <div class="form-group">
                                <label for="name">Nome</label> <small>Complemento do nome do procedimento</small>
                                <input type="text" name="name" value="{{ old('name', $dados->name) }}" class="form-control" placeholder="Nome">
                            </div>
                            <div class="form-group">
                                <label for="valor">Valor</label>
                                <input type="text" name="valor" value="{{ old('valor', $dados->valor) }}" class="form-control" placeholder="Valor">
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('saude.procedimentos_clinica.index', [$dados->user_id]) }}" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div><!-- /.box -->
                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('script')
@endsection