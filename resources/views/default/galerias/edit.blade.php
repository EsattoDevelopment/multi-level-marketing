@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar Galeria</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form galeria="form" action="{{ route('galeria.update', $dados->id) }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Titulo</label>
                                <input type="text" name="title" value="{{ old('title', $dados->title) }}" class="form-control" placeholder="Titulo">
                            </div>

                            @if(isset($tipos))
                                <div class="form-group">
                                    <label>Tipo</label>
                                    <select class="form-control select2" name="galeria_tipo_id" style="width: 100%;">
                                        <option value="0" >Escolha um tipo de galeria</option>
                                        @foreach($tipos as $tipo)
                                            <option value="{{ $tipo->id }}" {{ old('galeria_tipo_id') == $tipo->id ? 'selected' : $dados->galeria_tipo_id == $tipo->id ? 'selected' : '' }}>{{ $tipo->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <textarea class="textarea" name="description" placeholder="Descrição..." style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                    {{ old('description' , $dados->description) }}
                                </textarea>
                            </div>
                        </div><!-- /.box-body -->
                        <input type="hidden" name="_method" value="PUT">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>

                            <a href="{{ route('galeria.index') }}" class="btn btn-primary pull-right">Voltar</a>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('style')
    <link rel="stylesheet" href="{{ elixir('css/backend/create_editor.css') }}">
@endsection

@section('script')
    <script src="{{ elixir('js/backend/create_editor.js') }}"></script>
@endsection