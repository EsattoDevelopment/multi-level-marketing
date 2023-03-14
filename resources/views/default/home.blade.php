@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Conta digital
        </h1>
        <ol class="breadcrumb hidden-xs">
            <li class="active"><a href="#"><i class="fa fa-dashboard"></i> Ínicio</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-6 col-lg-4">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="@if($usuario->image){{ route('imagecache', ['fotoclube', 'user/'.$usuario->image]) }}
                        @else
                        {{ route('imagecache', ['fotoclube', 'user-img.jpg']) }}@endif" alt="User profile picture">

                        <h3 class="profile-username text-center">{{ $usuario->name }}</h3>

                        @if($usuario->id > 2)
                            <p class="text-muted text-center">
                                <span class="label" {!! $usuario->titulo ? 'style="background-color: #'.$usuario->titulo->cor.';"' : ''  !!}>{{ $usuario->titulo->name }}</span>
                            </p>
                        @endif
                        @if($usuario->status == 0)
                            <p class="text-muted text-center">Desativado</p>
                        @endif

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <a href="{{route('extrato.financeiro')}}"><b>Saldo disponível</b></a> <a class="pull-right">{{ $usuario->ultimoMovimento() ?  mascaraMoeda($sistema->moeda, $usuario->ultimoMovimento()->saldo, 2, true) : mascaraMoeda($sistema->moeda, 0, 2, true)}}</a>
                            </li>
                        </ul>

                        <a href="{{ route('dados-usuario.show') }}" class="btn btn-primary btn-block"><b>Editar dados</b></a>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

                <!-- About Me Box -->
                <div class="box box-warning">
                    <div class="box-header bg-gray with-border">
                        <h3 class="box-title text-bold">Sobre Mim</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body bg-gray">
                        @if($usuario->titulo->habilita_rede)
                            <strong><i class="fa fa-link margin-r-5"></i> Link indicação</strong>

                            <p class="text-muted">
                                <a href="{{ route('ev.indicador', $usuario->conta) }}">{{  route('ev.indicador', $usuario->conta) }}</a><br>
                                <small>Este é seu link de negócios. Envie-o para as pessoas que querem se associar a {{ env('COMPANY_NAME') }}.</small>
                            </p>

                            <hr>
                        @endif
                        <strong><i class="fa fa-file-text-o margin-r-5"></i> Status</strong>
                        <br>
                        <p class="label" {!! $usuario->titulo ? 'style="background-color: #'.$usuario->titulo->cor.';"' : '' !!}>{{ $usuario->titulo ? $usuario->titulo->name : 'Não tem' }}</p>
                        <hr>
                        @if(!$usuario->validado)

                            <strong><i class="fa fa-file-text-o margin-r-5"></i> Pendências</strong>
                            <br>
                            <br>
                            @if(!$usuario->validado)
                                <small class="text-red">*</small><small>Favor enviar os itens abaixo para que possamos concluir a abertura de sua conta</small>
                            @endif
                                <br>
                                <br>

                            @if(!$usuario->identidade)
                                <div>
                                    <i class="fa fa-times text-red"></i> <b><a href="{{ route('dados-usuario.identificacao') }}">Identidade</a></b>
                                </div>
                            @endif

                            @if($usuario->status_comprovante_endereco != 'validado')
                                <div>
                                    <i class="fa fa-times text-red"></i> <b><a href="{{ route('dados-usuario.endereco') }}">Endereço</a></b>
                                </div>
                            @endif

                            @if($usuario->dadosBancarios->where('status_comprovante', 'validado')->count() == 0)
                                <div>
                                    <i class="fa fa-times text-red"></i> <b><a href="{{ route('dados-usuario.dados-bancarios') }}">Dados bancários</a></b>
                                </div>
                            @endif

                            @if(!$usuario->google2fa_secret)
                                <div>
                                    <i class="fa fa-times text-red"></i> <b><a href="{{ route('dados-usuario.seguranca') }}">Autenticação de 2 fatores</a></b>
                                </div>
                            @endif
                                <hr>
                        @endif

                        @if($usuario->indicador)
                            <strong><i class="fa fa-black-tie margin-r-5"></i> Meu Agente</strong>

                            <p class="text-muted">
                                {{ $usuario->indicador->name }}<br>
                                {{ $usuario->indicador->celular ? "Telefone: ". $usuario->indicador->celular : '' }}
                            </p>
                        @endif
                        @if($usuario->titulo->habilita_rede)
                            @if($contrato)
                                <hr>

                                <strong><i class="fa fa-black-tie margin-r-5"></i> Contrato Agente</strong>

                                <a class="btn btn-default btn-block" href="{{ asset('docs/Impressao-de-contrato-de-agente.pdf') }}">Imprimir</a>
                            @endif
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="row col-md-8">

                @if(\Auth::user()->titulo->habilita_rede)
                <div class="col-md-6 col-lg-6">
                    <!-- About Me Box -->
                    <div class="box box-info">
                        <div class="box-header bg-gray-active with-border">
                            <h3 class="box-title text-bold">Extratos de Negócios</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body bg-gray-active">
                            <strong><i class="fa fa-money margin-r-5"></i> Total de ganhos</strong>

                            <p class="text-right" style="font-size: 1.5em;">
                                {{ $sistema->moeda }} @if($usuario->movimentos->count() > 0){{ mascaraMoeda($sistema->moeda, $totalGanhos, 2) }} @else {{ $usuario->movimentos->count() }} @endif
                            </p>

                            @if($sistema->pontos_pessoais_calculo_exibicao == 1)
                                <hr>
                                <strong><i class="fa fa-user margin-r-5"></i> <a href="{{ route('extrato.pessoais') }}">GMilhas pessoais</a></strong>
                                <p class="text-right" style="font-size: 1.5em;">
                                    @if($usuario->extratoPessoais())
                                        {{ mascaraMoeda($sistema->moeda, $usuario->extratoPessoais()->saldo, 0) }}
                                    @else
                                        0
                                    @endif
                                </p>
                            @endif
                            @if($sistema->pontos_equipe_calculo_exibicao == 1)
                                <hr>
                                <strong><i class="fa fa-sitemap margin-r-5"></i> <a href="{{ route('extrato.equipe') }}">GMilhas de equipe</a></strong>

                                <p class="text-right" style="font-size: 1.5em;">
                                    @if($usuario->pontosEquipe())
                                        {{ mascaraMoeda($sistema->moeda, $usuario->pontosEquipe()->saldo, 0) }}
                                    @else
                                        0
                                    @endif
                                </p>
                            @endif
                            <hr>
                            <strong><i class="fa fa-users margin-r-5"></i> <a href="{{ route('rede') }}">Total de clientes ativos da {{ env('COMPANY_NAME', 'Nome empresa') }} indicados por você</a></strong>

                            <p class="text-right" style="font-size: 1.5em;">{{ $usuario->diretosAprovados()->count() }}</p>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                @endif

                @if($depositos > 0)
                    <div class="col-md-6 col-lg-6">
                        <!-- DONUT CHART -->

                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <h3 class="box-title">Saldo do(s) contrato(s): {{ mascaraMoeda($sistema->moeda, $depositos, 2, true) }}</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="box-body">
                                <li class="list-group-item" style="margin-bottom: 15px;">
                                    <a href="{{ route('capitalizacao.index') }}"><b>Capitalizado nesses contratos:</b></a> <a class="pull-right">{{ mascaraMoeda($sistema->moeda, $capitalizacao, 2, true) }}</a>
                                </li>
                                <canvas id="pieChart" style="height:250px"></canvas>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
                @endif

            </div>
            <div class="col-md-12 col-lg-12 text-center">
                <div class="box">
                    <div class="box-header">Calendário Econômico</div>
                    <div class="box-body">
                        <iframe src="https://sslecal2.forexprostools.com?columns=exc_flags,exc_currency,exc_importance,exc_actual,exc_forecast,exc_previous&features=datepicker,timezone&countries=110,17,29,25,32,6,37,36,26,5,22,39,14,48,10,35,7,43,38,4,12,72&calType=week&timeZone=12&lang=12" width="650" height="467" frameborder="0" allowtransparency="true" marginwidth="0" marginheight="0"></iframe>

                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#timeline" data-toggle="tab">Linha do tempo</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="timeline">
                            <!-- The timeline -->
                            <ul class="timeline timeline-inverse">
                            @foreach($timeLine as $data => $lines)
                                <!-- timeline time label -->
                                    <li class="time-label">
                                    <span class="bg-red">
                                      {{ $data }}
                                    </span>
                                    </li>
                                    <!-- /.timeline-label -->
                                    @foreach($lines as $line)
                                        @if($line instanceof \App\Models\Videos)
                                            @include('default.home.timeline.video')
                                        @endif

                                        @if($line instanceof \App\Models\Download)
                                            @include('default.home.timeline.download')
                                        @endif

                                        @if($line instanceof \App\Models\PedidosMovimentos)
                                            @include('default.home.timeline.rentabilidade')
                                        @endif
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->

@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/fancybox/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
    <style>
        .sweet-alert button.cancel{
            background-color: #820b11;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.js"></script>
    <script src="{{ asset('plugins/fancybox/jquery.fancybox.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <script>
        @if(!Auth::user()->validado && Auth::user()->id > 2)

        function avisoDocumentos()
        {
            swal({
                    title: "Aviso",
                    html: true,
                    text: "<p class='text-red'>Para fazer transferências, seus documentos precisam estar aprovados!</p>\n" +
                        "Se você ainda não os enviou, <a href=\"{{ route('dados-usuario.identificacao') }}\">aqui</a> para enviar agora.\n" +
                        "Caso já os tenha enviado, aguarde a aprovação.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#048200",
                    confirmButtonText: "Enviar Agora",
                    cancelButtonText: "Enviar Depois",
                    closeOnConfirm: false,
                    closeOnCancel: false
                }
                ,
                function (isConfirm) {
                    if (!isConfirm) {
                        @if(!Auth::user()->google2fa_secret)
                        swal({
                                title: "Atenção",
                                html: true,
                                text: "Para sua segurança, ative a \"Autenticação de 2 fatores\", <a href=\"{{ route('dados-usuario.seguranca') }}\">clique aqui</a>" +
                                    " para ativar.",
                                type: "error",
                                showCancelButton: true,
                                confirmButtonColor: "#048200",
                                confirmButtonText: "Ativar Agora",
                                cancelButtonText: "Ativar Depois",
                                closeOnConfirm: true,
                                closeOnCancel: true
                            },
                            function (isConfirm) {
                                if (isConfirm) {
                                    window.location = "{{ route('dados-usuario.seguranca') }}";
                                }
                            });
                        @else
                        swal.close();
                        @endif
                    }else{
                        window.location = "{{ route('dados-usuario.identificacao') }}";
                    }
                }
            );
        }
        @endif

        $(function() {
                    @if(!Auth::user()->validado && Auth::user()->id > 2)

            var lastActivity_{{ Auth::user()->id }} = parseInt(sessionStorage.lastActivity_{{ Auth::user()->id }} || "0") || Date.now();

            setInterval(function() {
                if (Date.now() - lastActivity_{{ Auth::user()->id }} > 600000) { // 600000 = 10 minutes in ms
                    sessionStorage.lastActivity_{{ Auth::user()->id }} = lastActivity_{{ Auth::user()->id }} = Date.now();
                }else if(Date.now() - lastActivity_{{ Auth::user()->id }} < 3000){
                    sessionStorage.lastActivity_{{ Auth::user()->id }} = lastActivity_{{ Auth::user()->id }};
                    avisoDocumentos();
                }

                @if(env('APP_ENV') == 'local')
                console.log(Date.now() - lastActivity_{{ Auth::user()->id }});
                @endif
            }, 1000);

            @endif

            @if($depositos > 0)

            //-------------
            //- PIE CHART -
            //-------------
            // Get context with jQuery - using jQuery's .get() method.
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieOptions = {
                //Boolean - Whether we should show a stroke on each segment
                segmentShowStroke: true,
                //String - The colour of each segment stroke
                segmentStrokeColor: '#fff',
                //Number - The width of each segment stroke
                segmentStrokeWidth: 2,
                //Number - The percentage of the chart that we cut out of the middle
                percentageInnerCutout: 50, // This is 0 for Pie charts
                //Number - Amount of animation steps
                animationSteps: 100,
                //String - Animation easing effect
                animationEasing: 'easeOutBounce',
                //Boolean - Whether we animate the rotation of the Doughnut
                animateRotate: true,
                //Boolean - Whether we animate scaling the Doughnut from the centre
                animateScale: false,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true,
                // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                legend: {
                    position: 'right',
                },
                title: {
                    position: 'bottom',
                    display: true,
                    text: ''
                },
                //String - A legend template
                legendTemplate: '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.datasets[0].data[tooltipItem.index] || '';

                            if (label) {
                                label += ' %';
                            }
                            changeTitle(data.datasets[0].values[tooltipItem.index]);
                            return label;
                        }
                    }
                }

            }

            var pieChart = new Chart(pieChartCanvas, {
                type: 'doughnut',
                data: {
                    labels: {!! $labels !!},
                    datasets: [{
                        data: {!! $porcentagens !!},
                        backgroundColor: {!! $colors !!},
                        values : {!! $valores !!}
                    }]
                },
                options: pieOptions
            })

            function changeTitle(label){
                pieChart.options.title.text = label;
            }
            @endif

        })
    </script>
@endsection
