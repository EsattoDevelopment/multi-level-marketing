@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de usuários com documentos aguardando aprovação<br>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Documentos</li>
            <li>Associados</li>
            <li class="active">Aguardando Aprovação</li>
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
                                <th>ID</th>
                                <th>Usuário</th>
                                <th>Agência</th>
                                <th>Conta</th>
                                <th>Documentos Aguardando Aprovação</th>
                                <th>Detalhes</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($dados as $usuario)
                                <tr id="contrato-{{ $usuario->id }}">
                                    <td>{{$usuario->id}}</td>
                                    <td>{{ $usuario->name }}</td>
                                    <td>0001</td>
                                    <td>{{$usuario->conta}}</td>
                                    <td>
                                        @if(($usuario->status_cpf ==  'em_analise' || $usuario->status_comprovante_endereco == 'em_analise' || $usuario->status_selfie == 'em_analise')
                                            && ($usuario->dadosBancarios->count() == 0 || $usuario->dadosBancarios->where('status_comprovante', 'em_analise')->count() > 0)
                                            && ($usuario->responsavel_doc_aguardando > 0))
                                            Dados Pessoais, Dados Bancários e Dados do Responsável
                                        @elseif(($usuario->status_cpf ==  'em_analise' || $usuario->status_comprovante_endereco == 'em_analise' || $usuario->status_selfie == 'em_analise')
                                            && ($usuario->dadosBancarios->count() == 0 || $usuario->dadosBancarios->where('status_comprovante', 'em_analise')->count() > 0))
                                            Dados Pessoais e Dados Bancários
                                        @elseif(($usuario->status_cpf ==  'em_analise' || $usuario->status_comprovante_endereco == 'em_analise' || $usuario->status_selfie == 'em_analise')
                                            && ($usuario->responsavel_doc_aguardando > 0))
                                            Dados Pessoais e Dados do Responsável
                                        @elseif(($usuario->dadosBancarios->count() == 0 || $usuario->dadosBancarios->where('status_comprovante', 'em_analise')->count() > 0)
                                            && ($usuario->responsavel_doc_aguardando > 0))
                                            Dados Bancários e Dados do Responsável
                                        @elseif($usuario->dadosBancarios->count() == 0 || $usuario->dadosBancarios->where('status_comprovante', 'em_analise')->count() > 0)
                                            Dados Bancários
                                        @elseif($usuario->responsavel_doc_aguardando > 0)
                                            Dados do Responsável
                                        @else
                                            Dados Pessoais
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                            @role(['master', 'admin', 'manipulador-documento'])
                                            <a class="btn btn-success" href="{{route('documentos.associado.aguardando.visualizacao', $usuario->id)}}">
                                                Visualizar
                                                <span class="glyphicon glyphicon-eye-open text-black"></span>
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
@endsection