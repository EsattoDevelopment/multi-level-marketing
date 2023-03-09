@extends('default.layout.main')

@section('content')
    <section class="content-header">
        <h1>{{ env('COMPANY_NAME') }}</h1>
        <ol class="breadcrumb">
            <li class="active">
                <a href="#"><i class="fa fa-dashboard"></i> Home</a>
            </li>
        </ol>
    </section>
    @include('default.errors.errors')
    <section class="content">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Índices de Bolsas de Valores em Tempo Real</h3>
                    </div>
                    <div class="box-body ">
                        <iframe src="https://br.widgets.investing.com/live-indices?theme=darkTheme&pairs=166,27,172,177,170,175,178,959206" width="100%" height="350" frameborder="0" allowtransparency="true" marginwidth="0" marginheight="0"></iframe><div class="poweredBy" style="font-family: Arial, Helvetica, sans-serif;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <!-- DONUT CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Quadro de Resumo Técnico</h3>
                    </div>
                    <div class="box-body text-center">
                        <iframe style="border:none;" src="https://ssltsw.forexprostools.com?lang=12&forex=2103,1617,1513,1,3,9,10&commodities=8833,8849,8830,8836,8832,8918,8911&indices=23660,166,172,27,179,170,174&stocks=358,474,446,334,345,346,347&tabs=1,2,3,4" width="317" height="467"></iframe>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6">
                <!-- DONUT CHART -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cotações de Mercado</h3>
                    </div>
                    <div class="box-body text-center">
                        <iframe frameborder="0" scrolling="no" height="467" width="317" allowtransparency="true" marginwidth="0" marginheight="0" src="https://ssltools.forexprostools.com/market_quotes.php?force_lang=12&tabs=1,2,4,3&tab_1=1,2,3,5,6,7,9,11,51,1617&tab_2=27,170,172,174,175,176,178,53094,23658,44486&tab_3=22159,21925,21870,23306,18599,18626,18604,18692,18749,18814&tab_4=8830,8833,8836,8849,8851,8862,8869,8910,8911,959211&tab_5=8907,8906,8905,8880,8895,8899,8900,8898,13917,28719&select_color=000000&default_color=0059b0"></iframe>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Resumo Técnico</h3>
                    </div>
                    <div class="box-body ">
                        <iframe frameborder="0" scrolling="yes" height="274" width="100%" allowtransparency="true" marginwidth="0" marginheight="0" src="https://ssltools.forexprostools.com/technical_summary.php?pairs=8830,8836,8849,8869,8911,166&curr-name-color=%230059B0&fields=5m,1h,1d&force_lang=12"></iframe><br /><div style="width:425px;"><span style="float:left"><span style="font-size: 11px;color: #333333;text-decoration: none;"></span></span></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Cotações de Commodities</h3>
                    </div>
                    <div class="box-body ">
                        <iframe src="https://br.widgets.investing.com/live-commodities?theme=darkTheme&pairs=8869,8832,8831,8830,8833,8849,8836,8917,49768,8911,8848,8861,8862" width="100%" height="275" frameborder="0" allowtransparency="true" marginwidth="0" marginheight="0"></iframe><div class="poweredBy" style="font-family: Arial, Helvetica, sans-serif;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Calendário Econômico</h3>
                    </div>
                    <div class="box-body text-center">
                        <iframe src="https://sslecal2.forexprostools.com?columns=exc_flags,exc_currency,exc_importance,exc_actual,exc_forecast,exc_previous&features=datepicker,timezone&countries=110,17,29,25,32,6,37,36,26,5,22,39,14,48,10,35,7,43,38,4,12,72&calType=week&timeZone=12&lang=12" width="750" height="467" frameborder="0" allowtransparency="true" marginwidth="0" marginheight="0"></iframe><div class="poweredBy" style="font-family: Arial, Helvetica, sans-serif;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Cotações de Moedas em Tempo Real</h3>
                    </div>
                    <div class="box-body ">
                        <iframe src="https://br.widgets.investing.com/live-currency-cross-rates?theme=darkTheme&pairs=1,3,2,4,7,5,8,6,1121152" width="100%" height="350" frameborder="0" allowtransparency="true" marginwidth="0" marginheight="0"></iframe><div class="poweredBy" style="font-family: Arial, Helvetica, sans-serif;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('style')
@endsection

@section('script')
@endsection
