@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de Contas
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Contas empresa</li>
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
                        <table id="tabela_index" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Banco</th>
                                <th>Conta</th>
                                <th>Usado no boleto?</th>
                                <th>Recebe TED?</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($dados as $dt)
                                <tr>
                                    <td>{{ $dt->id }}</td>
                                    <td>{{ $dt->getRelation('banco')->nome }}</td>
                                    <td>{{ $dt->conta }}</td>
                                    <td>{{ $dt->usar_boleto_string }}</td>
                                    <td>{{ $dt->recebe_ted == 0 ? "Não" : "Sim"}}</td>
                                    <td>{{ $dt->status == 0 ? "Inativo" : "Ativo"}}</td>
                                    <td>
                                        <form method="post" id="formDel" action="{{ route('contas_empresa.destroy', $dt->id) }}">
                                            {!! csrf_field() !!}
                                            <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                <a title="Editar" class="btn btn-default btn-sm" href="{{ route('contas_empresa.edit', $dt->id) }}">
                                                    <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                </a>
                                                {{--<input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger btn-sm" data-toggle="confirmation" data-btn-ok-label="Sim" data-btn-ok-class="btn-success" data-btn-cancel-label="Não" data-btn-cancel-class="btn-danger"  data-title="Deletar Permanentemente!" data-singleton="true">
                                                    <span class="glyphicon glyphicon-trash text-black"></span>
                                                </button>--}}
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty

                            @endforelse

                            </tbody>
                            <tfoot>
                            </tfoot>
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
    <script src="/js/backend/tabelas.js" type="text/javascript"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="/js/backend/bootstrap-confirmation.js" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            //adiciona o botão de NOVO
            $('<div class="btn-group"><a href="{{ route('contas_empresa.create') }}" class="btn btn-primary">Novo</a></div>').appendTo('div.box-btn');
        })
    </script>
@endsection