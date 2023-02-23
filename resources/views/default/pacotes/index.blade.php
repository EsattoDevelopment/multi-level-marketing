@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            {{ $textos['todos'] }} {{ $textos['titulo'] }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">{{ $textos['todos'] }} {{ $textos['titulo'] }}</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#pacotes" data-toggle="tab">Ativos</a></li>
                        <li><a href="#desativados" data-toggle="tab">Desativados </a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active tab-pane" id="pacotes">
                            <div class="box">
                                <div class="box-header">
                                    Lista de {{ $textos['titulo'] }}
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_portugues" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Chamada</th>
                                            <th>Tipos de acomodação</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pacotes as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->chamada }}</td>
                                                <td>{{ $dd->getRelation('acomodacao')->implode('name', ',') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                        @if($dd->galeria_id)
                                                            <a title="Imagens" class="btn btn-default btn-sm" href="{{ route('pacotes.galeria', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-picture text-blue" aria-hidden="true"></span>
                                                                <span class="badge">{{ $dd->galeria->imagensCount() }}</span>
                                                            </a>
                                                        @else
                                                            <a title="Criar Galeria" class="btn btn-default btn-sm" href="{{ route('pacotes.galeria.create', $dd->id) }}">
                                                                <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
                                                            </a>
                                                        @endif
                                                        <a title="Editar" class="btn btn-default btn-sm" href="{{ route('pacotes.edit', $dd) }}">
                                                            <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Chamada</th>
                                            <th>Tipos de acomodação</th>
                                            <th>Ações</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="desativados">
                            <div class="box">
                                <div class="box-header">
                                    Lista de {{ $textos['titulo'] }} desativados
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_espanhol" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Chamada</th>
                                            <th>Tipos de acomodação</th>
                                            <th>Ações</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pacotes_desativados as $dd)
                                            <tr>
                                                <td>{{ $dd->id }}</td>
                                                <td>{{ $dd->chamada }}</td>
                                                <td>{{ $dd->getRelation('acomodacao')->implode('name', ',') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                        <a title="Editar" class="btn btn-default btn-sm" href="{{ route('pacotes.edit', $dd) }}">
                                                            <span class="glyphicon glyphicon-edit text-success" aria-hidden="true"></span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Chamada</th>
                                            <th>Tipos de acomodação</th>
                                            <th>Ações</th>
                                        </tr>
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
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/backend/tabelas.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            //adiciona o botão de NOVO
            $('<div class="btn-group"><a href="{{ $textos['url'] }}" class="btn btn-primary">Novo</a></div>').appendTo('div.box-btn');
        })
    </script>
@endsection