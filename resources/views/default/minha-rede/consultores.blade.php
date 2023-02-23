@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Agentes
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li >Rede hierárquica {{ env('COMPANY_NAME', 'Nome empresa') }}</li>
            <li class="active">Agentes</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tabela_portugues" class="table table-bordered table-striped responsive">
                            <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Status</th>
                                <th>E-mail</th>
                                <th>Dados de contato</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dados as $dd)
                                <tr>
                                    <td>{{ strlen($dd->cpf) == 18 ? $dd->empresa : $dd->name }}</td>
                                    <td>{{ $dd->getRelation('titulo')->name }}</td>
                                    <td>{{ $dd->email }}</td>
                                    <td>{{ $dd->telefone ?? $dd->endereco->telefone1 }}<br>{{ $dd->celular ?? $dd->endereco->celular }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">

@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>

    <script src="{{ asset('js/backend/tabelas.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
@endsection