@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Diretos pendentes
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Diretos pendentes</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#aguardando_pagamento" data-toggle="tab">Pendentes</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active tab-pane" id="aguardando_pagamento">
                            <div class="box">
                                <div class="box-header">
                                    Lista de diretos pendentes
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_portugues" class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Usuário</th>
                                            <th>Nome</th>
                                            <th>E-mail</th>
                                            <th>Dados de contato</th>
                                            @if($sistema->rede_binaria)
                                                <th>Direção</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($dados as $dd)
                                            <tr>
                                                <td>{{ $dd->username }}</td>
                                                <td>{{ $dd->name }}</td>
                                                <td>{{ $dd->email }}</td>
                                                <td>@if($dd->getRelation('endereco')){{ $dd->getRelation('endereco')->telefone1 }}<br>{{ $dd->getRelation('endereco')->telefone2 }}<br>{{ $dd->getRelation('endereco')->celular }}@endif</td>
                                                @if($sistema->rede_binaria)
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            @if($dd->equipe_predefinida == 0)
                                                                <a title="Esquerda" class="btn btn-default btn-sm tooltip2" href="{{ route('user.predefinir.equipe', [Auth::user()->id, $dd->id, 1]) }}">
                                                                    <span class="glyphicon glyphicon-arrow-left text-yellow" aria-hidden="true"></span>Rede esquerda
                                                                    <span class="tooltiptext">Após primeiro pagamento o indicado será posicionado na rede esquerda</span>
                                                                </a>
                                                                <a title="Direita" class="btn btn-default btn-sm tooltip2" href="{{ route('user.predefinir.equipe', [Auth::user()->id, $dd->id, 2]) }}">
                                                                    Rede direita <span class="glyphicon glyphicon-arrow-right text-yellow" aria-hidden="true"></span>
                                                                    <span class="tooltiptext">Após primeiro pagamento o indicado será posicionado na rede direita</span>
                                                                </a>
                                                            @else
                                                                @if($dd->equipe_predefinida == 1)
                                                                    <button title="Esquerda" class="btn btn-default btn-sm">
                                                                        <span class="glyphicon glyphicon-arrow-left text-success" aria-hidden="true"></span> Rede esquerdo
                                                                    </button>
                                                                    <a title="Mudar" class="btn btn-warning btn-sm" href="{{ route('user.predefinir.equipe', [Auth::user()->id, $dd->id, 0]) }}">
                                                                        Mudar
                                                                    </a>
                                                                @elseif($dd->equipe_predefinida == 2)
                                                                    <a title="Mudar" class="btn btn-warning btn-sm" href="{{ route('user.predefinir.equipe', [Auth::user()->id, $dd->id, 0]) }}">
                                                                        Mudar
                                                                    </a>
                                                                    <button title="Direita" class="btn btn-default btn-sm" >
                                                                        Rede direita <span class="glyphicon glyphicon-arrow-right text-success" aria-hidden="true"></span>
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach

                                        </tbody>
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
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">

@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/backend/tabelas.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/list.js') }}" type="text/javascript"></script>

@endsection