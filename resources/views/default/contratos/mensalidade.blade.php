@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar mensalidade</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form galeria="form" action="{{ route('contratos.mensalidade.update', [$dados->contrato_id, $dados->id]) }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            <div class="form-group col-md-12">
                                <label>Vencimento</label>
                                <input type="text" name="dt_pagamento" class="form-control datepicker" value="{{ $data->dt_pagamento }}">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Valor</label>
                                <input type="text" name="valor" class="form-control" value="{{ $data->valor }}">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Data Pagamento</label>
                                <input type="text" name="dt_baixa" class="form-control datepicker" value="{{ $data->dt_baixa }}">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Valor pago</label>
                                <input type="text" name="valor_pago" class="form-control" value="{{ $data->valor_pago }}">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Usuário</label>
                                <input disabled="disabled" type="text" class="form-control" value="{{ $data->user->get()->name }}">
                            </div>

                        </div><!-- /.box-body -->

                        <input type="hidden" name="_method" value="PUT">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('contratos.edit', $dados->contrato_id) }}" class="btn btn-primary pull-right">Voltar</a>
                        </div>
                    </form>

                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css')}}">
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/i18n/pt-BR.js') }}"></script>

    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{ asset('plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js')}}"></script>

    <!-- InputMask -->
    <script src="../../plugins/input-mask/jquery.inputmask.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <script>

        $(function () {
            $("#user_id").select2({
                placeholder: 'Escolha um usuário',
                language: "pt-BR",
                minimumInputLength: 2,
                tags: false,
                ajax: {
                    delay: 250,
                    url: "{{ route('api.user.busca') }}",
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    data: function (params) {
                        var queryParameters = {
                            search: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: '#' + item.id + ' - ' + item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $("#item_id").select2({
                placeholder: 'Escolha um plano',
                language: "pt-BR",
                minimumInputLength: 3,
                tags: false,
                ajax: {
                    delay: 250,
                    url: "{{ route('api.item.busca') }}",
                    dataType: 'json',
                    type: "GET",
                    quietMillis: 50,
                    data: function (params) {
                        var queryParameters = {
                            search: params.term
                        }
                        return queryParameters;
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: '#' + item.id + ' - ' + item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $("input[name='dt_fim'], input[name='dt_inicio']").inputmask({
                mask: '99/99/9999',
                showTooltip: true,
                showMaskOnHover: true
            });

            $.fn.datepicker.defaults.language = 'pt-BR';

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });
        })

    </script>
@endsection