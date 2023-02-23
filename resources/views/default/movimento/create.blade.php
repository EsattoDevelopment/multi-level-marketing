@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Cadastro de movimentos
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Movimentos</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Movimentos</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{ route('movimento.store') }}" method="post">
                        {!! csrf_field() !!}
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label>Operação</label>
                                <select class="form-control select2"  name="operacao_id" data-placeholder="Selecione uma operação" style="width: 100%;">
                                    <option value="">Escolha uma operação</option>
                                    @foreach($operacoes as $operacao)
                                        <option @if(old('operacao_id') == $operacao->id) selected @endif value="{{ $operacao->id }}">{{ $operacao->cor == 'text-green' ? '+' : '-' }} {{ $operacao->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Valor</label>
                                <input type="text" name="valor_manipulado" value="{{ old('valor_manipulado') }}" class="form-control" placeholder="Valor">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Descrição</label>
                                <input type="text" name="descricao" value="{{ old('descricao') }}" class="form-control" placeholder="Descrição">
                            </div>
                            <div class="form-group col-xs-12">
                                <label>Usuário</label>
                                <select class="form-control select2"  name="user_id" data-placeholder="Selecione um usuário" style="width: 100%;">
                                    <option value="">Escolha um usuario</option>
                                    @foreach($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}">#{{ $usuario->id }} - {{ $usuario->name }} - {{ $usuario->empresa }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>
@endsection