@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de contratos Aguardando liberação<br>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Contratos</li>
            <li class="active">Aguardando liberação</li>
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
                                <th>Nº Doc</th>
                                <th>N° Contrato</th>
                                <th>Usuário Solicitante</th>
                                <th>Plano</th>
                                <th>Data Inicio</th>
                                <th>Data Fim</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($dados as $contrato)

                                <tr id="contrato-{{ $contrato->id }}">
                                    <td>{{ $contrato->id }}</td>
                                    <td>{{ $contrato->getRelation('usuario')->codigo }}</td>
                                    <td>@if($contrato->getRelation('usuario') ){{ $contrato->getRelation('usuario')->name }} @endif</td>
                                    <td>{{ $contrato->getRelation('item')->name }}</td>
                                    <td>{{ $contrato->dt_inicio }}</td>
                                    <td>{{ $contrato->dt_fim }}</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                            <a title="Editar" class="btn btn-default btn-sm"
                                               href="{{ route('contratos.edit', $contrato->id) }}">
                                                            <span class="glyphicon glyphicon-edit text-success"
                                                                  aria-hidden="true"></span> Abrir
                                            </a>
                                            <a title="Gerar parcelas" target="_blank" class="btn btn-default btn-sm"
                                               href="{{ route('contratos.mensalidades.gerar', $contrato->id) }}">
                                                            <span class="fa fa-copy"
                                                                  aria-hidden="true"></span> Gerar parcelas
                                            </a>
                                            {{--                <a title="Editar dados" class="btn btn-default btn-sm" href="{{ route('dados-usuario.show', $dt->id) }}">
                                                                <span class="fa fa-list-alt text-info" aria-hidden="true"></span>
                                                            </a>--}}
                                            @permission(['master', 'admin'])
                                            <a title="Cancelar"
                                               data-url1="{{ route('contratos.cancelar.dentro-prazo', $contrato->id) }}"
                                               data-url2="{{ route('contratos.cancelar.fora-prazo', $contrato->id) }}"
                                               class="btn btn-default btn-sm cancelar"
                                               href="javascript:;">
                                                <span class="glyphicon glyphicon-remove text-danger"
                                                      aria-hidden="true"></span> Cancelar
                                            </a>
                                            @endpermission
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

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
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert2.min.css') }}">

@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>

    <script src="{{ asset('js/backend/tabelas.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/sweetalert/sweetalert2.js') }}" type="text/javascript"></script>

    <script>
        $(function () {
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
        })
    </script>
@endsection