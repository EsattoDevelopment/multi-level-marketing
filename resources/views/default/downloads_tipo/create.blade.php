@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('download-tipo.store') }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cadastro de Tipo de Downloads</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Titulo</label>
                                <input type="text" required name="titulo" value="{{ old('titulo') }}" class="form-control" placeholder="Titulo">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Exibir apenas para quem tem rede?</label>
                                <br>
                                <input type="checkbox" value="1" name="habilita_rede" class="flat-red" {{ old('habilita_rede') == 1 ? 'checked' : '' }}>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="descricao">Descrição</label>
                                <textarea class="textarea" name="descricao" placeholder="Descrição..."
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('descricao') }}</textarea>
                            </div>

                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" name="botao" value="news" class="btn btn-primary">Salvar</button>

                            <a href="{{ route('download-tipo.index') }}" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div><!-- /.box -->

                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('script')
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/red.css') }}">
    <script>
        $(function(){
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });

            CKEDITOR.replace('descricao');
        });
    </script>
@endsection