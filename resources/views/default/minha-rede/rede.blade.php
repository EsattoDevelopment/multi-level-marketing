@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Clientes {{ env('COMPANY_NAME', 'Nome empresa') }} Indicados
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Clientes {{ env('COMPANY_NAME', 'Nome empresa') }} indicados</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tabela_portugues" style="width: 100%;" class="table table-bordered table-striped table-responsive">
                            <thead>
                            <tr>
                                <th>Agência</th>
                                <th>Nº Conta</th>
                                <th>Nome</th>
                                <th>Status</th>
                                <th>E-mail</th>
                                <th>Ativo</th>
                                <th>Dados de contato</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dados as $dd)
                                <tr>
                                    <td>0001</td>
                                    <td>{{ $dd->conta }}</td>
                                    <td>{{ strlen($dd->cpf) == 18 ? $dd->empresa : $dd->name }}</td>
                                    <td>{{ $dd->getRelation('titulo')->name }}</td>
                                    <td>{{ $dd->email }}</td>
                                    <td>{{ $dd->status_string }}</td>
                                    <td>{{ $dd->telefone }}<br>{{ $dd->celular }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            </tfoot>
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
    <script>
        $(function () {
            $.fn.dataTable.columnDefs = [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: 2 },
                { responsivePriority: 3, targets: 3 },
                { responsivePriority: 999, targets: 1 }
            ];
        })
    </script>

@endsection