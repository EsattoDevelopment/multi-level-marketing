@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Títulos para Update
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Títulos para update</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal-user-all">
                            Update All
                        </button>
                        <div class="modal fade" id="modal-user-all" data-id="all" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title">Update de Título</h4>
                                    </div>
                                    <form action="{{ route('user.update.titulo.all') }}" method="get">
                                        <div class="modal-body">
                                            <p>Você está fazendo o update do título de <strong>todos os usúarios listados</strong>, clique abaixo no botão "CONFIRMAR" para efetuar o update.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Cancelar</button>
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-primary">Confirmar</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="tabela_index" class="table table-bordered table-striped responsive">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Sobe Título</th>
                                <th>Título Atual</th>
                                <th>Título Update</th>
                                <th>Requisitos Atingidos</th>
                                <th>Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dados as $dd)
                                <tr>
                                    <td>{{ $dd['id']}}</td>
                                    <td>{{ $dd['name']}}</td>
                                    <td>{{ $dd['sobe_titulo']}}</td>
                                    <td>{{ $dd['titulo_atual_name']}}</td>
                                    <td>{{ $dd['titulo_update_name']}}</td>
                                    <td>
                                        <b>Min. Diretos Aprovados:</b> {{$dd['diretos_aprovados_update']}} <b>Atual:</b> {{$dd['diretos_aprovados']}}<br>
                                        <b>Min. GMilhas Pessoais:</b> {{$dd['ponto_pessoal_update']}} <b>Atual:</b> {{$dd['ponto_pessoal_atual']}}<br>
                                        <b>Min. GMilhas Equipe:</b> {{$dd['ponto_equipe_update']}} <b>Atual:</b> {{$dd['ponto_equipe_atual']}}<br>
                                        @if(@count($dd['titulos_update']) > 0)
                                            @foreach($dd['titulos_update'] as $chave => $valor)
                                                <b>{{$chave}} (Min:</b> {{$valor['minimo']}} <b>Atual:</b> {{$valor['atual']}}<b>)</b><br>
                                            @endforeach
                                        @else
                                            <b><br>Títulos para update NÃO CONFIGURADO</b>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-user-{{ $dd['id'] }}">
                                                Fazer Update
                                            </button>
                                        </div>
                                        <div class="modal fade" id="modal-user-{{ $dd['id'] }}" data-id="{{ $dd['id'] }}" style="display: none;">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span></button>
                                                        <h4 class="modal-title">Update de Título</h4>
                                                    </div>
                                                    <form action="{{ route('user.update.titulo', $dd['id']) }}" id="frm-{{ $dd['id'] }}" method="get">
                                                        <div class="modal-body">
                                                            <p>Você está fazendo o update do título de <strong>{{ $dd['titulo_atual_name'] }}</strong> para <strong>{{ $dd['titulo_update_name'] }}</strong> do usúario <strong>{{$dd['name']}}</strong>, clique abaixo no botão "CONFIRMAR" para efetuar o update.</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Cancelar</button>
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="titulo_atual_id" value="{{ $dd['titulo_update_id'] }}">
                                                            <input type="hidden" name="titulo_antigo_id" value="{{ $dd['titulo_atual_id'] }}">
                                                            <button type="submit" class="btn btn-primary">Confirmar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
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
    <script src="/js/backend/tabelas.js" type="text/javascript"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="/js/backend/bootstrap-confirmation.js" type="text/javascript"></script>
@endsection
