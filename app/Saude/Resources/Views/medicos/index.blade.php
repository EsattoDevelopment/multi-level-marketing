@extends('layout.main')

@section('content')

    @include('errors.errors')

    <section class="content-header">
        <h1>
            Lista de {{ $name }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">{{ $name }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tabela_index" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>CRM</th>
                                <th>Especialidades</th>
                                <th>Clinicas</th>
                                @permission(['master', 'admin'])
                                <th>Telefone</th>
                                <th>Celular</th>
                                <th>Ações</th>
                                @endpermission
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($dados as $dd)
                                <tr>
                                    <td>{{ $dd->id }}</td>
                                    <td>{{ $dd->name }}</td>
                                    <td>{{ $dd->crm }}</td>
                                    <td>
                                        @forelse($dd->getRelation('especialidades') as $espec)
                                            @if($espec->id == $dd->getRelation('especialidades')->last()->id)
                                                {{ $espec->name }}
                                            @else
                                                {{ $espec->name . ', ' }}
                                            @endif
                                        @empty

                                        @endforelse
                                    </td>
                                    <td>
                                        @forelse($dd->clinicas as $clinica)
                                            @if($clinica->id == $dd->clinicas->last()->id)
                                                {{ $clinica->name }}
                                            @else
                                                {{ $clinica->name . ', ' }}
                                            @endif
                                        @empty

                                        @endforelse
                                    </td>
                                    @permission(['master', 'admin'])
                                    <td>{{ $dd->telefone1 }}</td>
                                    <td>{{ $dd->telefone2 }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                            <a title="Editar" class="btn btn-default btn-sm"
                                               href="{{ route("saude.medicos.edit", $dd->id) }}">
                                                <span class="glyphicon glyphicon-edit text-success"
                                                      aria-hidden="true"></span> Editar
                                            </a>
                                            <a title="Desativar" class="btn btn-default btn-sm"
                                               href="{{ route("saude.medicos.delete", $dd->id) }}">
                                                <span class="glyphicon glyphicon-remove text-danger"
                                                      aria-hidden="true"> </span> Desativar
                                            </a>
                                        </div>
                                    </td>
                                    @endpermission
                                </tr>
                            @empty

                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
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
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="/js/backend/bootstrap-confirmation.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function () {
            $('#tabela_index').DataTable({
                order: [[1, "desc"]]
            });
            /*var table = $('#tabela_index').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 500,
                ajax: '{!! $getAll !!}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'crm', name: 'crm'},
                    {data: 'telefone1', name: 'telefone1'},
                    {data: 'telefone2', name: 'telefone2'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[1, "desc"]]
            });*/

            //adiciona o botão de NOVO
            $('<div class="btn-group"><a href="{{ route('saude.medicos.create') }}" class="btn btn-primary">Novo</a></div>').appendTo('div.box-btn');
        })
    </script>
@endsection