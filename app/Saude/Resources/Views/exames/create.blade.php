@extends('layout.main')

@section('content')
    <section class="content">

        @include('errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('saude.exames.store') }}" method="post">
                    {!! csrf_field() !!}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cadastro de Exames</h3>
                        </div><!-- /.box-header -->


                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="nome">Nome</label>
                                <input type="text" name="nome" value="{{ old('nome') }}" class="form-control"
                                       placeholder="Nome">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="name">Código</label>
                                <input type="text" name="codigo" value="{{ old('codigo') }}" class="form-control"
                                       placeholder="Código">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="descricao">Descrição</label>
                                <textarea class="textarea" name="descricao" placeholder="Descrição..." style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('descricao') }}</textarea>
                            </div>

                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div><!-- /.box -->
                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection