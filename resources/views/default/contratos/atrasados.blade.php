@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de contratos atrasados <br>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Contratos</li>
            <li class="active">atrasados</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box">
                    <div class="box-body">
                        <table id="tabela_index" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Nº Doc</th>
                                <th>N° Contrato</th>
                                <th>Usuário Solicitante</th>
                                <th>Plano</th>
                                <th>Data Inicio</th>
                                <th>Data Fim</th>
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
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert2.min.css') }}">

@endsection

@section('script')
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{{ asset('plugins/handlebars.min.js') }}"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>

    {{--<script src="{{ asset('js/backend/tabelas.js') }}" type="text/javascript"></script>--}}
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>

    <script src="{{ asset('plugins/sweetalert/sweetalert2.js') }}" type="text/javascript"></script>

    <script>
        $(function () {
            var template = Handlebars.compile($("#details-template").html());

            var table = $('#tabela_index').DataTable({
                processing: true,
                serverSide: true,
                searchDelay: 500,
                ajax: '{!! route('contratos.atrasados.get') !!}',
                columns: [
                    {
                        className: 'details-control',
                        orderable: false,
                        searchable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {data: 'id', name: 'id'},
                    {data: 'usuario.codigo', name: 'usuario.codigo'},
                    {data: 'usuario.name', name: 'usuario.name'},
                    {data: 'item.name', name: 'item.name'},
                    {data: 'dt_inicio', name: 'contratos.dt_inicio'},
                    {data: 'dt_fim', name: 'contratos.dt_fim'},
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
                    "searching": false,
                    "paging": true,
                    ajax: data.details_url,
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'dt_pagamento', name: 'dt_pagamento', orderable: false },
                        { data: 'valor', name: 'valor', orderable: false },
                        { data: 'dt_baixa', name: 'dt_baixa', orderable: false },
                        { data: 'status', name: 'status', orderable: false },
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]
                })
            }

            $(document).on("click", "a.cancelar", function () {

                window.url_contrato = [
                    $(this).attr('data-url1'),
                    $(this).attr('data-url2')
                ];

                swal({
                    title: "Realmente deseja cancelar o contrato?",
                    input: 'select',
                    inputOptions: {
                        '0': 'Cancelamento dentro do prazo de 7 dias',
                        '1': 'Cancelamento fora do prazo'
                    },
                    inputPlaceholder: 'Selecione o motivo',
                    type: "warning",
                    showCancelButton: true,
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                    confirmButtonColor: "#04810d",
                    confirmButtonText: "Prosseguir",
                    cancelButtonText: "Cancelar",
                    preConfirm: (value) => {
                        if(value === ''){
                            swal.showValidationError(
                                `Selecione um motivo!`
                            )
                        }else{
                            var get = $.get(window.url_contrato[value]);

                            return get.done(function (data) {
                                return data;
                            }).fail(function (data) {
                                return data;
                            });
                        }
                    },
                }).then((result) => {
                    if(result.dismiss){
                        swal({
                            title: `Operação cancelada com sucesso!`,
                            type: "warning"
                        })
                    }else if(result.value) {
                        if (result.value.ok) {
                            swal({
                                title: `Cancelado com sucesso!`,
                                type: "success"
                            });
                            $('#contrato-'+result.value.contrato).fadeOut();
                        } else {
                            swal({
                                title: `Erro ao realizar operação. Tente novamente, se o erro perssistir contante o administrador!`,
                                type: "error"
                            })
                        }
                    }
                }).catch(error => {
                    swal({
                        title: `Erro ao realizar operação. Tente novamente, se o erro perssistir contante o administrador!`,
                        type: "error"
                    })
                }).finally(result => {
                    allowOutsideClick: () => !swal.isLoading()
                });

            })
        });
    </script>

    <script id="details-template" type="text/x-handlebars-template">
        <div class="label label-info">Mensalidades do contrato</div>
        <table class="details-table" id="posts-@{{id}}">
            <thead>
            <tr>
                <th>Id</th>
                <th>Vencimento</th>
                <th>Valor</th>
                <th>Baixa</th>
                <th>Status</th>
                <th>Ação</th>
            </tr>
            </thead>
        </table>
    </script>
@endsection