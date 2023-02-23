@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <form role="form" action="{{ route('plataforma.update', $dados->id) }}" method="post">
                {!! csrf_field() !!}
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edição de Plataforma</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-2">
                                <label for="status">Status</label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ old('status', $dados->status) == 1 ? 'active' : $dados->status == 1 ? 'active' : '' }}">
                                        <input type="radio" value="1" {{ old('status', $dados->status) == 1 ? 'checked' : $dados->status == 1 ? 'checked' : ''  }} name="status"
                                               autocomplete="off">Ativo
                                    </label>
                                    <label class="btn btn-primary {{ old('status', $dados->status) == 0 ? 'active' : $dados->status == 0 ? 'active' : '' }}">
                                        <input type="radio" value="0" {{ old('status', $dados->status) == 0 ? 'checked' : $dados->status == 0 ? 'checked' : ''  }} name="status" autocomplete="off">Inativo
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="nome">Nome</label>
                                <input type="text" name="nome" value="{{ old('nome', $dados->nome) }}" class="form-control" placeholder="Nome">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="descricao">Descrição</label>
                                <textarea name="descricao" class="form-control" rows="5" placeholder="Descrição">{{ old('descricao', $dados->descricao) }}</textarea>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box box-primary">
                            <div class="box-footer">
                                <input type="hidden" name="_method" value="PUT">
                                <button type="submit" name="botao" value="news" class="btn btn-primary">Salvar</button>
                                <a href="{{ route('plataforma.index') }}" class="btn btn-default pull-right">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div><!-- /.box -->
            </form>
        </div>   <!-- /.row -->
    </section><!-- /.content -->
@endsection

