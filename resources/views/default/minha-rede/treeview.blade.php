@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Organograma
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li >Rede hierárquica {{ env('COMPANY_NAME', 'Nome empresa') }}</li>
            <li class="active">Organograma</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Está e a rede hierárquica com clientes {{ env('COMPANY_NAME', 'Nome empresa') }} indicados por você</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="treeview-animated" >
                            <ul class="treeview-animated-list">
                                <li class="treeview-animated-items">
                                    <a class="closed" id="userroot">
                                        <i class="fa fa-angle-right"></i>
                                        <span>
                                            <i class="fa fa-user"></i>{{$dados->name}}
                                            <small class="label" style="background-color: #{{$dados->getRelation('titulo')}};"> {{$dados->getRelation('titulo')->name}}</small>
                                            <small class="label" style="background-color: #{{$dados->cor_status}};"> {{$dados->status_ativo}}</small>
                                        </span>
                                        {{--<span class="pull-right-container">
                                          <small class="label pull-right bg-red">diretos: {{$dados->diretos()->count()}}</small>
                                        </span>--}}
                                    </a>
                                    <ul class="nested nivel1">
                                        @foreach($dados->getRelation('diretos') as $direto)
                                            @if($direto->diretos()->count() > 0)
                                                <li class="treeview-animated-items">
                                                    <a onclick="preencherDiretos({{ $direto->id }})" pai="sim" data-user="{{ $direto->id }}" id="{{ $direto->id }}" class="estado closed"><i class="fa fa-angle-right"></i>
                                                        <span><i class="fa fa-user"></i>{{$direto->name}}
                                                            <small class="label" style="background-color: #{{$direto->titulo->cor}};">{{$direto->titulo->name}}</small>
                                                            <small class="label" style="background-color: #{{$direto->cor_status}};"> {{$direto->status_ativo}}</small>
                                                        </span>
                                                    </a>
                                                    <ul class="nested" id="nested_{{$direto->id}}">
                                                    </ul>
                                                </li>
                                            @else
                                                <li>
                                                    <div class="treeview-animated-element">
                                                        <i class="fa fa-user"></i>{{$direto->name}}
                                                        <small class="label" style="background-color: #{{$direto->titulo->cor}};">{{$direto->titulo->name}}</small>
                                                        <small class="label" style="background-color: #{{$direto->cor_status}};"> {{$direto->status_ativo}}</small>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                {{--<div class="box box-primary">
                    <div class="box-footer">
                        <a href="{{ route('rede.organograma') }}" class="btn btn-primary pull-right">Voltar</a>
                    </div>
                </div>--}}
            </div>
            <!-- /.row -->
        </div>
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-treeview/mdb.css') }}">
@endsection

@section('script')
    <script src="{{ asset('plugins/bootstrap-treeview/mdb.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.treeview-animated').mdbTreeview();
        });

        $("#userroot").addClass('open');
        $(".nested.nivel1").addClass('active');
    </script>

    <script>
        $(function () {
            $(".nested.active.nivel1").css('display', 'block');
        })

        function preencherDiretos(id) {
            var estado = $("#" + id).attr("class");
            var pai = $("#" + id).attr("pai");
            if(estado == 'estado closed') {
                $.getJSON('/api/user/diretos?user_id=' + id,
                    function (dados) {
                        if (dados.length > 0) {
                            /*if atributo close = faz a ação abaixo*/
                            var html = '';
                            $.each(dados, function (index, value) {
                                if (value.diretosqtde > 0) {
                                    html += '<li class="treeview-animated-items"><a onclick="preencherDiretos(' + value.id + ')" pai="nao" id="' + value.id + '" class="estado closed"><i id="i' + value.id + '" class="fa fa-angle-right"></i>';
                                    html += '<span><i class="fa fa-user"></i>' + value.nome + '   <small class="label" style="background-color: #' + value.cor + ';">' + value.titulo + ' </small> <small class="label" style="margin-left: 3px; background-color: #' + value.corstatus + ';">' + value.statusativo + '</small></span></a>';
                                    html += '<ul class="nested nivel1 active" id="nested_' + value.id + '" style="display: block;"></ul></li>';
                                } else {
                                    html += '<li><div class="treeview-animated-element"><i class="fa fa-user"></i>' + value.nome + '   <small class="label" style="background-color: #' + value.cor + ';">' + value.titulo + '</small><small class="label" style="margin-left: 3px; background-color: #' + value.corstatus + ';">' + value.statusativo + '</small></div></li>';
                                }
                            })
                            $("#nested_" + id).html(html).show();
                        }
                    });
                if (pai == 'nao') {
                    $("#" + id).addClass('open');
                    $("#i" + id).addClass('down');
                }
            }
            else{
                if (pai == 'nao') {
                    $("#i" + id).removeClass('down');
                    $("#nested_" + id).removeClass('active');
                    $("#nested_" + id).css('display', 'none');
                    $("#" + id).removeClass('open');
                }
            }
        }
    </script>
@endsection