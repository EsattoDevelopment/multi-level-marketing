@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de Dados Boletos
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Boletos</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Retorno de boletos</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{ route('boleto.processa.retorno') }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="image">Retorno</label>
                                <input type="file" id="retorno" name="retorno">
                            </div>
{{--                            <div class="form-group col-xs-12">
                                <label for="image">Retorno Branco do Brasil</label>
                                <input type="file" id="boletoCEF" name="boletoCEF">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="image">Retorno Bradesco</label>
                                <input type="file" id="boletoCEF" name="boletoCEF">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="image">Retorno CEF</label>
                                <input type="file" id="boletoCEF" name="boletoCEF">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="image">Retorno CEF</label>
                                <input type="file" id="boletoCEF" name="boletoCEF">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="image">Retorno CEF</label>
                                <input type="file" id="boletoCEF" name="boletoCEF">
                            </div>--}}
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Processar</button>
                        </div>
                    </form>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
@endsection

@section('script')
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="/js/backend/tabelas.js" type="text/javascript"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="/js/backend/bootstrap-confirmation.js" type="text/javascript"></script>
    <script src="/js/backend/list.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            //adiciona o botão de NOVO
            //$('<div class="btn-group"><a href="{{-- route('boletos.create') --}}" class="btn btn-primary">Novo</a></div>').appendTo('div.box-btn');
        })
    </script>
@endsection