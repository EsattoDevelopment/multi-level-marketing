@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('modal.update', $dados->id) }}" method="post" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="PUT">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cadastro de Modal</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">

                            <div class="form-group col-xs-12">
                                <label for="titulo">Titulo</label>
                                <input type="text" name="title" value="{{ old('title', $dados->title) }}" class="form-control" placeholder="Titulo">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="descricao">Descrição</label>
                                <textarea class="textarea" name="descricao" placeholder="Descrição..."
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('descricao', $dados->descricao) }}</textarea>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="arquivo">Arquivo (JPG, PNG)</label>
                                <input type="file" id="arquivo" name="arquivo">
                            </div>

                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>

                            <a href="{{ route('modal.index') }}" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div><!-- /.box -->

                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('script')
    <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(function(){
            CKEDITOR.replace('descricao');
        });
    </script>
@endsection
