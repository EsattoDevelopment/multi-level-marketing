@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Lista de Histórico de Operações
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">operacao-historico</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#portugues" data-toggle="tab">Ativos</a></li>
                        <li><a href="#desativados" data-toggle="tab">Desativados</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active  tab-pane" id="portugues">
                            <div class="box">
                                <div class="box-header">
                                    Lista de Histórico de Operaçãoes
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_portugues" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Titulo</th>
                                            <th>Valor</th>
                                            <th>%</th>
                                            <th>Data</th>
                                            <th>Plataforma</th>
                                            <th>Conta</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($dados as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->titulo}}</td>
                                                <td>{{mascaraMoeda($sistema->moeda, $dd->valor, 2, true)}}</td>
                                                <td>{{$dd->percentual.'%'}}</td>
                                                <td>{{ $dd->data}}</td>
                                                <td>{{ $dd->plataforma()->nome }}</td>
                                                <td>{{$dd->getRelation('plataformaconta')->nome}}</td>
                                                <td>
                                                    <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('operacao-historico.destroy', $dd->id) }}">
                                                        {!! csrf_field() !!}
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            <a title="Editar" class="btn btn-default btn-sm" href="{{ route('operacao-historico.edit', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                            </a>
                                                            <a title="Desativar" class="btn btn-default btn-sm" href="{{ route('operacao-historico.desativar', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-remove text-danger" aria-hidden="true"></span>
                                                            </a>
                                                            {{--<input type="hidden" name="_method" value="DELETE">
                                                            <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del" >
                                                                <span class="glyphicon glyphicon-trash text-black"></span>
                                                            </button>--}}
                                                        </div>
                                                    </form>
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

                        <div class="tab-pane" id="desativados">
                            <div class="box">
                                <div class="box-header text-danger">
                                    Lista de Histórico de Operações
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_desabled" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Titulo</th>
                                            <th>Valor</th>
                                            <th>%</th>
                                            <th>Data</th>
                                            <th>Plataforma</th>
                                            <th>Conta</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($dados_desativados != null)
                                            @foreach($dados_desativados as $dd)
                                                <tr>
                                                    <td>{{ $dd->id }}</td>
                                                    <td>{{ $dd->titulo}}</td>
                                                    <td>{{mascaraMoeda($sistema->moeda, $dd->valor, 2, true)}}</td>
                                                    <td>{{$dd->percentual.'%'}}</td>
                                                    <td>{{ $dd->data}}</td>
                                                    <td>{{ $dd->plataforma()->nome }}</td>
                                                    <td>{{$dd->getRelation('plataformaconta')->nome}}</td>
                                                    <td>
                                                        <form method="post" id="formDel_{{ $dd->id }}" action="{{ route('operacao-historico.ativar', $dd->id) }}">
                                                            {!! csrf_field() !!}
                                                            <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                                <a title="Ativar" class="btn btn-default btn-sm" href="{{ route('operacao-historico.ativar', $dd->id) }}">
                                                                    <span class="glyphicon glyphicon-ok text-success" aria-hidden="true"></span>
                                                                </a>
                                                                {{--<input type="hidden" name="_method" value="DELETE">
                                                                <button type="submit" data-id="{{ $dd->id }}" class="btn btn-danger btn-sm botao-del" >
                                                                    <span class="glyphicon glyphicon-trash text-black"></span>
                                                                </button>--}}
                                                            </div>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                        <tfoot>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.tab-pane -->

                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">

@endsection

@section('script')
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>

    <script src="/js/backend/tabelas.js" type="text/javascript"></script>
    <script src="/js/backend/datatables.js" type="text/javascript"></script>
    <script src="/js/backend/bootstrap-confirmation.js" type="text/javascript"></script>
    {{--<script src="/js/backend/list.js" type="text/javascript"></script>--}}
    <script type="text/javascript">
        $(function() {
            //adiciona o botão de NOVO
            $('<div class="btn-group"><a href="{{ route('operacao-historico.create') }}" class="btn btn-primary">Novo</a></div>').appendTo('div.box-btn');
        })
    </script>
@endsection