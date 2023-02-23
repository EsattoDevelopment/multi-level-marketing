@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            {{-- {{ $textos['todos'] }} {{ $textos['titulo'] }}--}}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            {{--<li class="active">{{ $textos['todos'] }} {{ $textos['titulo'] }}</li>--}}
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget">
                    <div class="box-header with-border">
                        <h3 class="box-title">Dados</h3>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"
                                     style="max-width: 900px; margin: auto;">
                                    <ol class="carousel-indicators">
                                        @foreach($dados->getRelation('galeria')->getRelation('imagens') as $key => $img)
                                            <li data-target="#carousel-example-generic" data-slide-to="{{ $key }}"
                                                @if($key == 0) class="active" @endif></li>
                                        @endforeach
                                    </ol>
                                    <div class="carousel-inner">
                                        @foreach($dados->getRelation('galeria')->getRelation('imagens') as $key => $img)
                                            <div class="item @if($key == 0) active @endif">
                                                <img src="{{ route('imagecache', ['carousel', $img->imagem]) }}"
                                                     alt="{{ $img->legenda }}">

                                                <div class="carousel-caption">
                                                    {{ $img->legenda }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                        <span class="fa fa-angle-left"></span>
                                    </a>
                                    <a class="right carousel-control" href="#carousel-example-generic"
                                       data-slide="next">
                                        <span class="fa fa-angle-right"></span>
                                    </a>
                                </div>
                                <!-- /.box -->
                            </div>
                            <div class="col-sm-12">
                                <div class="box box-solid">
                                    <div class="box-body">
                                        <h2>
                                            {{ $dados->chamada }}
                                        </h2>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="box box-solid">
                                    <div class="box-header with-border">
                                        <i class="fa fa-text-width"></i>

                                        <h3 class="box-title text-black">Descrição</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        {!! $dados->descricao !!}
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        </div>
                        <div class="row col-xs-12">
                            <form action="{{ route('usar-gmilhas.reservar') }}" method="post">
                                {!! csrf_field() !!}

                                @if($dados->local_selecionavel)
                                    {{--Local--}}
                                    <div class="row" id="local">
                                        <div class="col-md-12">
                                            <div class="box box-solid box-primary">
                                                <div class="box-header">
                                                    Local
                                                </div>
                                                <div class="box-body">
                                                    <div class="form-group col-xs-12 col-md-6">
                                                        <label>Estado</label>
                                                        <select class="form-control select2" name="estado" data-placeholder="Selecione um estado" style="width: 100%;">
                                                            @foreach($estados as $uf)
                                                                <option @if(old('estado') == $uf->id) selected @endif value="{{ $uf->id }}">{{ $uf->nome }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-xs-12 col-md-6">
                                                        <label>Cidade</label>
                                                        <select class="form-control select2" name="cidade_id" id="select-cidades" data-placeholder="Selecione um" style="width: 100%;">
                                                            @if(old('estado'))
                                                                @foreach(\App\Models\Cidade::where('estado', old('estado'))->get() as $city)
                                                                    <option @if(old('cidade_id') == $city->id) selected @endif value="{{ $city->id }}">{{ $city->nome }}</option>
                                                                @endforeach
                                                            @else
                                                                @foreach(\App\Models\Cidade::where('estado', $estados->first()->id)->get() as $city)
                                                                    <option value="{{ $city->id }}">{{ $city->nome }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        {{--Acomodações--}}
                                        <div class="row" id="acomodacao">
                                            <div class="col-md-12">
                                                <div class="box box-solid box-info">
                                                    <div class="box-header">
                                                        Escolha a acomodação
                                                    </div>
                                                    <div class="box-body">
                                                        @foreach($dados->getRelation('acomodacao') as $ta)
                                                            <div class="col-xs-12 col-sm-6 col-lg-3 text-center acomodacoes">
                                                                <label for="">{{ $ta->name }}</label>
                                                                <div class="input-group">
                                                        <span class="input-group-addon">
                                                          <input type="radio" required name="acomodacao" data-id="{{ $ta->id }}"
                                                                 value="{{ $ta->id }}">
                                                        </span>
                                                                    <input type="text" disabled="disabled" id="diaria-{{ $ta->id }}"
                                                                           name="valor-diaria-{{ $ta->id }}"
                                                                           value="{{ mascaraMoeda($sistema->moeda, $ta->getRelation('pivot')->valor, 0, true) }}"
                                                                           class="form-control">
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="box-footer">
                                                        <small><i>Valores em GMilhas<i class="fa fa-registered"></i> e por
                                                                diária</i></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{--Periodo--}}
                                        <div class="row" id="periodo">
                                            <div class="col-md-12">
                                                <div class="box box-solid box-warning">
                                                    <div class="box-header">
                                                        Periodo
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="form-group col-xs-12 col-md-6">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    Ida
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input type="text" required name="from" class="form-control pull-right"
                                                                       id="from">
                                                            </div>
                                                            <!-- /.input group -->
                                                        </div>

                                                        <div class="form-group col-xs-12 col-md-6">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    Volta
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input type="text" required name="to" class="form-control pull-right"
                                                                       id="to">
                                                            </div>
                                                            <!-- /.input group -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--!Periodo--}}

                                        {{--Resumo--}}
                                        <div class="row" id="resumo">
                                            <div class="col-md-12">
                                                <div class="box box-solid box-danger">
                                                    <div class="box-header">
                                                        Resumo
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="form-group col-xs-12 col-md-6">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                    Todas de diárias
                                                                </div>
                                                                <input type="text" name="total-diarias" readonly
                                                                       class="form-control pull-right" id="diarias">
                                                            </div>
                                                            <!-- /.input group -->
                                                        </div>

                                                        <div class="form-group col-xs-12 col-md-6">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    Valor em GMilhas<i class="fa fa-registered"></i>
                                                                </div>
                                                                <input type="text" name="gmilhas-total" readonly
                                                                       class="form-control pull-right text-red" id="gmilhas-total">
                                                            </div>
                                                            <div class="col-sx-12">
                                                                Suas GMilhas disponíveis: <strong
                                                                        id="gmilhas-user">{{ mascaraMoeda($sistema->moeda, $usuario->getRelation('milhas')->sum('quantidade'), 0, true) }}</strong>
                                                            </div>
                                                            <!-- /.input group -->
                                                        </div>
                                                    </div>
                                                    <div class="box-footer">
                                                        <div class="col-xs-12 hidden" id="insuficiente">
                                                            <strong class="text-red">Você não tem GMilhas suficientes.</strong> <br>
                                                            Adquira mais aqui
                                                            <a href="{{ route('pedido.create') }}" class="btn btn-primary">Comprar
                                                                GMilhas</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{--!Resumo--}}

                                        <div class="row hidden" id="submit-form">
                                            <input type="hidden" value="{{ $dados->id }}" name="pacote">
                                            <div class="col-md-12">
                                                <div class="box">
                                                    <div class="box-body">
                                                        <button type="submit" class="btn btn-success btn-block btn-lg">Usar GMilhas</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            </form>
                        </div>
                    </div>
                    <div class="box-footer">

                    </div>
                    <!-- /.widget-user -->
                </div>
                <!-- /.col -->
            </div>
        </div>
    </section>
@endsection

@section('style')
    <!-- DataTables -->
    {{--<link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">--}}
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('script')
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>
    <script src="{{ asset('plugins/jquery-number-master/jquery.number.min.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    {{--    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>--}}
    {{--<script src="{{ asset('plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js') }}"></script>--}}


    <script>
        $(function () {

            $("#from, #to").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});

            var dateFormat = "dd/mm/yy";

                    @if($dados->dias > 0)

            var from =  $( "#from" ).datepicker({
                    minDate: "+1M",
                    changeMonth: true,
                    numberOfMonths: 2,
                    dateFormat: 'dd/mm/yy',
                    dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                    dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                    monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    nextText: 'Próximo',
                    prevText: 'Anterior',
                    onClose: function () {

                        var currentDate = from.datepicker('getDate');

                        currentDate.setDate(currentDate.getDate() +{{ $dados->dias }});

                        if (currentDate.getDate() < 10)
                            data = '0' + currentDate.getDate() + '/';
                        else
                            data = currentDate.getDate() + '/';


                        if (currentDate.getMonth() < 10)
                            data = data + '0' + currentDate.getMonth() + '/';
                        else
                            data = data + currentDate.getMonth() + '/';

                        data = data + currentDate.getFullYear();


                        $('#to').datepicker({
                            dateFormat: 'dd/mm/yy'
                        });

                        to.datepicker('setDate', currentDate);

                        daysFixo();
                    }
                }),

                to = $("#to").datepicker({
                    minDate: "+1M +{{ $dados->dias }}d",
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 2,
                    dateFormat: 'dd/mm/yy',
                    dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                    dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                    monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    nextText: 'Próximo',
                    prevText: 'Anterior',
                    onClose: function () {

                        var currentDate = to.datepicker('getDate');

                        currentDate.setDate(currentDate.getDate() -{{ $dados->dias }});

                        if (currentDate.getDate() < 10)
                            data = '0' + currentDate.getDate() + '/';
                        else
                            data = currentDate.getDate() + '/';


                        if (currentDate.getMonth() < 10)
                            data = data + '0' + currentDate.getMonth() + '/';
                        else
                            data = data + currentDate.getMonth() + '/';

                        data = data + currentDate.getFullYear();

                        from.datepicker('setDate', currentDate);

                        daysFixo();

                    }
                });

                    @else

            var from = $("#from")
                    .datepicker({
                        minDate: "+1M",
                        changeMonth: true,
                        numberOfMonths: 2,
                        dateFormat: 'dd/mm/yy',
                        dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                        dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                        dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                        monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                        nextText: 'Próximo',
                        prevText: 'Anterior'
                    })
                    .on("change", function () {
                        to.datepicker("option", "minDate", getDate(this));
                        days();
                    }),

                to = $("#to").datepicker({
                    minDate: "+1M",
                    defaultDate: "+1w",
                    changeMonth: true,
                    numberOfMonths: 2,
                    dateFormat: 'dd/mm/yy',
                    dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                    dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                    monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                    nextText: 'Próximo',
                    prevText: 'Anterior'
                })
                    .on("change", function () {
                        from.datepicker("option", "maxDate", getDate(this));
                        days();
                    });

            @endif

            function getDate(element) {
                var date;
                try {
                    date = $.datepicker.parseDate(dateFormat, element.value);
                } catch (error) {
                    date = null;
                }

                return date;
            }

            $('input[name="acomodacao"]').change(function () {
                if ($(this).is(':checked')) {
                    @if($dados->dias > 0)
                        daysFixo();
                    @else
                        days();
                    @endif
                }
            });

            function days() {
                if ($("#from").val().trim().length > 0 && $("#to").val().trim().length > 0) {

                    calcDias();

                    acomodacao();

                }
            }

            function daysFixo() {
                if ($("#from").val().trim().length > 0 && $("#to").val().trim().length > 0) {

                    calcDias();

                    acomodacaoFixa();

                }
            }

            function calcDias() {

                var a = $("#from").datepicker('getDate').getTime(),
                    b = $("#to").datepicker('getDate').getTime(),
                    c = 24 * 60 * 60 * 1000,
                    diffDays = Math.round(Math.abs((a - b) / (c)));

                $('#diarias').val(diffDays);

                window.dias = diffDays;

            }

            function acomodacao() {
                $('input[name="acomodacao"]').each(function () {
                    if ($(this).is(':checked')) {
                        var id = $(this).attr('data-id');
                        var gmilhas = window.dias * parseFloat($('#diaria-' + id).val().replace('.', ''));
                        $('#gmilhas-total').val($.number(gmilhas, 0 , '.', '.'));

                        verificaQuantidadeGmilhas(gmilhas);
                    }
                });
            }

            function acomodacaoFixa() {
                $('input[name="acomodacao"]').each(function () {
                    if ($(this).is(':checked')) {
                        var id = $(this).attr('data-id');
                        var gmilhas = parseFloat($('#diaria-' + id).val().replace('.', ''));
                        $('#gmilhas-total').val($.number(gmilhas, 0 , '.', '.'));

                        verificaQuantidadeGmilhas(gmilhas);
                    }
                });
            }

            function verificaQuantidadeGmilhas(gmilhas) {

                var totalGmilhasUser = parseFloat($('#gmilhas-user').html().replace('.', ''));

                if (gmilhas > totalGmilhasUser) {

                    $('#gmilhas-total').removeClass('text-green');
                    $('#gmilhas-total').addClass('text-red');

                    if ($('#insuficiente').hasClass('hidden'))
                        $('#insuficiente').removeClass('hidden');

                    if (!$('#submit-form').hasClass('hidden'))
                        $('#submit-form').addClass('hidden');

                } else {
                    $('#gmilhas-total').addClass('text-green');
                    $('#gmilhas-total').removeClass('text-red');


                    if (!$('#insuficiente').hasClass('hidden'))
                        $('#insuficiente').addClass('hidden');

                    if ($('#submit-form').hasClass('hidden'))
                        $('#submit-form').removeClass('hidden');
                }
            }

            function getCidades(estado){
                get = $.get('/pacotes/cidades/'+estado);

                get.done(function(data){

                    $('select[name="cidade_id"]').html('');

                    $.each(data, function(i, item){
                        $('select[name="cidade_id"]').append("<option value='" + item.id + "'>" + item.nome + "</option>");
                    });

                },'json');
            }

            $('select[name="estado"]').change(function(){
                valor = $(this).val();
                getCidades(valor);
            });

            @if(old('estado', false))
                getCidades({{ old('estado') }});
            @endif
        })
    </script>
@endsection