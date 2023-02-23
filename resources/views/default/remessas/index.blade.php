@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de Regras
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Regras</li>
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
                                <th></th>
                                <th>ID</th>
                                <th>Data</th>
                                <th>Ordem</th>
                                <th>Arquivo</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
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
    <link rel="stylesheet" href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">

    <style>
        td.details-control {
            cursor: pointer;
            width: 18px;
        }

        td.details-control:before {
            font-family: FontAwesome;
            content: "\f055";
            color: #1ead05;
            border: 2px solid rgba(187, 168, 168, 0.59);
            border-radius: 20px;
            background: #ffffff;
            padding: 1px;
        }

        tr.shown > td.details-control:before {
            content: "\f056";
            color: #ff0000;
        }

    </style>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{{ asset('plugins/handlebars.min.js') }}"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
{{--    <script src="/js/backend/tabelas.js" type="text/javascript"></script>--}}

    <script>
        $(function () {
            var template = Handlebars.compile($("#details-template").html());

            var table = $('#tabela_index').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 500,
                ajax: '{!! route('remessa.all') !!}',
                columns: [
                    {
                        className: 'details-control',
                        orderable: false,
                        searchable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {data: 'id', name: 'id'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'numero', name: 'numero'},
                    {data: 'arquivo', name: 'arquivo'},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order: [[1, "desc"]]
            });

            // Add event listener for opening and closing details
            $('#tabela_index tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var tableId = 'posts-' + row.data().id;

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    // Open this row
                    row.child(template(row.data())).show();
                    initTable(tableId, row.data());
                    tr.addClass('shown');
                    tr.next().find('td').addClass('no-padding bg-gray');
                }
            });

            function initTable(tableId, data) {
                $('#' + tableId).DataTable({
                    processing: true,
                    serverSide: true,
                    "ordering": false,
                    "searching": true,
                    "paging": true,
                    ajax: data.details_url,
                    columns: [
                        { data: 'id', name: 'b.id' },
                        { data: 'nosso_numero', name: 'b.nosso_numero' },
                        { data: 'numero_documento', name: 'b.numero_documento' },
                        { data: 'created_at', name: 'b.created_at' },
                        { data: 'vencimento', name: 'b.vencimento' },
                        { data: 'valor', name: 'm.valor' }
                    ]
                })
            }
        });
    </script>

    <script type="text/javascript">
        $(function() {
            //adiciona o botão de NOVO
            $('<div class="btn-group"><a href="{{ route('remessa.create') }}" class="btn btn-primary"><i class="glyphicon glyphicon-refresh"></i> Gerar</a></div>').appendTo('div.box-btn');
        })
    </script>

    <script id="details-template" type="text/x-handlebars-template">
        <div class="label label-info">Boletos da remessa</div>
        <table class="details-table" id="posts-@{{id}}">
            <thead>
            <tr>
                <th>Id</th>
                <th>Nosso Numero</th>
                <th>Documento</th>
                <th>Gerado em</th>
                <th>Vencimento</th>
                <th>Valor</th>
            </tr>
            </thead>
        </table>
    </script>
@endsection