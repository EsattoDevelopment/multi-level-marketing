@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('download.store') }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cadastro de Downloads</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Titulo</label>
                                <input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="Titulo">
                            </div>

                            <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                <label>Tipo de download</label><br>
                                <select required class="form-control select2" name="download_tipo_id"
                                        data-placeholder="Selecione uma tipo" style="width: 100%;">
                                    <option value="">Selecione um tipo</option>
                                    @foreach($tipos as $tipo)
                                        <option {{ old('download_tipo_id') == $tipo->id ? 'selected' : '' }} value="{{ $tipo->id }}">{{ $tipo->titulo }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="descricao">Descrição</label>
                                <textarea class="textarea" name="descricao" placeholder="Descrição..."
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('descricao') }}</textarea>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="arquivo">Arquivos (JPG, PNG, AI, PDF, Word, Excel, PowerPoint)</label>
                                <input type="file" id="arquivo" name="arquivo">
                            </div>

                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" name="botao" value="news" class="btn btn-primary">Salvar</button>

                            <a href="{{ route('download.index') }}" class="btn btn-default pull-right">Voltar</a>
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

