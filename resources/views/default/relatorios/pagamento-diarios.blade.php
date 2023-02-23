@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Relatório de recebimentos
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Relatórios</li>
            <li class="active">recebimentos</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Relatórios de recebimentos</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="{{ route('relatorio.pagamento-diarios') }}" method="post" target="_blank">
                        {{ csrf_field() }}

                        <div class="box-body">
                            <div class="form-group col-lg-3 col-xs-12">
                                <label>Tipo</label>
                                <select class="form-control select2" name="sort_by" data-placeholder="Selecione um tipo de recebimento" style="width: 100%;">
                                    <option value="3">Todos</option>
                                    <option value="1">Adesão/Renovação/Inclusão de dependentes</option>
                                    <option value="5">Balanço (Adesão/Estorno)</option>
                                    <option value="2">Mensalidade</option>
                                    <option value="4">Estornos</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-xs-12">
                                <label>Forma de pagamento</label>
                                <select class="form-control select2" name="pagamento" data-placeholder="Selecione uma forma de pagamento" style="width: 100%;">
                                    <option value="0">Todos</option>
                                    <option value="8">Dinheiro</option>
                                    <option value="1">Boleto</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3 col-xs-12">
                                <label>Data Inicio</label>
                                <input type="text" name="inicio" id="from" class="form-control" required value="">
                            </div>
                            <div class="form-group col-lg-3 col-xs-12">
                                <label>Data Final</label>
                                <input type="text" name="fim" id="to" class="form-control" required value="">
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" name="tipo" value="1" class="btn btn-primary">Gerar</button>
                            {{--<button type="submit" name="tipo" value="2" class="btn btn-primary">Gerar em Excel</button>--}}
                        </div>
                    </form>
                </div>

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

@endsection

@section('script')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script src="../../plugins/input-mask/jquery.inputmask.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <script>

        $(function () {

            $("#from, #to").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});

            var dateFormat = "dd/mm/yy",

            from = $("#from")
                    .datepicker({
                        changeMonth: true,
                        changeYear: true,
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
                    defaultDate: "+1w",
                    changeMonth: true,
                    changeYear: true,
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

            function getDate( element ) {
                var date;
                try {
                    date = $.datepicker.parseDate( dateFormat, element.value );
                } catch( error ) {
                    date = null;
                }

                return date;
            }
        })

    </script>
@endsection