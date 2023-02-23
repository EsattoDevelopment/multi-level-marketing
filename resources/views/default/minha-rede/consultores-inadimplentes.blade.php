@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Agentes inadimplentes
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li >Minha rede</li>
            <li class="active">Agentes inadimplentes</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#aguardando_pagamento" data-toggle="tab">Agentes</a></li>
                    </ul>
                    <div class="tab-content">

                        <div class="active tab-pane" id="aguardando_pagamento">
                            <div class="box">
                                <div class="box-header">
                                    Lista de agentes
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <table id="tabela_portugues" class="table table-bordered table-striped responsive">
                                        <thead>
                                        <tr>
                                            <th>Usuário</th>
                                            <th>Nome</th>
                                            <th>E-mail</th>
                                      {{--      <th>Ativo</th>--}}
                                            <th>Dados de contato</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($dados as $dd)
                                            <tr>
                                                <td>{{ $dd->username }}</td>
                                                <td>{{ $dd->name }}</td>
                                                <td>{{ $dd->email }}</td>
                                        {{--        <td>{{ $dd->status_string }}</td>--}}
                                                <td>@if($dd->getRelation('endereco')){{ $dd->getRelation('endereco')->telefone1 }}<br>{{ $dd->getRelation('endereco')->telefone2 }}<br>{{ $dd->getRelation('endereco')->celular }}@endif</td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>Usuário</th>
                                            <th>Nome</th>
                                            <th>E-mail</th>
                                       {{--     <th>Ativo</th>--}}
                                            <th>Dados de contato</th>
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
    <link rel="stylesheet" href="{{ asset('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css') }}">

@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js') }}"></script>

    <script src="{{ asset('js/backend/tabelas.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/list.js') }}" type="text/javascript"></script>

@endsection