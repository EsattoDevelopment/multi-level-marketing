@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar contrato</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form galeria="form" action="{{ route('contratos.update', $dados->id) }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            <div class="form-group col-md-12">
                                <label>Usuário</label>
                                <select class="form-control" id="user_id" name="user_id">
                                    <option value="{{ $dados->user_id }}"
                                            selected="selected">{{ '#'.$dados->getRelation('usuario')->id .' - '. $dados->getRelation('usuario')->name }}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Plano</label>
                                <select class="form-control" id="item_id" name="item_id">
                                    <option value="{{ $dados->item_id }}"
                                            selected="selected">{{ '#'.$dados->getRelation('item')->id .' - '. $dados->getRelation('item')->name }}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">Data de inicio do contrato</label>
                                <input type="text" name="dt_inicio"
                                       value="{{ old('dt_inicio', $dados->dt_inicio) }}"
                                       class="form-control datepicker" id="exampleInputEmail1"
                                       placeholder="Data de inicio do contrato">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">Data de fim do contrato</label>
                                <input type="text" name="dt_fim"
                                       value="{{ old('dt_fim', $dados->dt_fim) }}"
                                       class="form-control datepicker" id="exampleInputEmail1"
                                       placeholder="Data de fim do contrato">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="exampleInputEmail1">Data da primeira parcela</label>
                                <input type="text" name="dt_parcela"
                                       value="{{ old('dt_parcela', $dados->dt_parcela) }}"
                                       class="form-control datepicker" id="exampleInputEmail1"
                                       placeholder="Data da primeira parcela">
                            </div>

                        </div><!-- /.box-body -->

                        <div class="box-body">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Mensalidades</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="table-responsive">
                                        <table class="table no-margin">
                                            <thead>
                                            <tr>
                                                <th>Vencimento</th>
                                                <th>Valor</th>
                                                <th>Valor pago</th>
                                                <th>Pago em</th>
                                                <th>Status</th>
                                                <th>Ação</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($dados->getRelation('mensalidades') as $mensal)
                                                <tr>
                                                    <td>{{ $mensal->dt_pagamento }}</td>
                                                    <td>{{ mascaraMoeda($sistema->moeda, $mensal->valor, 2, true) }}</td>
                                                    <td>{{ isset($mensal->valor_pago) ? mascaraMoeda($sistema->moeda, $mensal->valor_pago, 2, true) : '0.00' }}</td>
                                                    <td>{{ $mensal->dt_baixa }}</td>
                                                    <td>
                                                        <span class="label label-{{ $mensal->status_cor }}">{{ $mensal->status }}</span>
                                                    </td>

                                                    <td><a href="{{ route('mensalidade.edit', $mensal) }}"
                                                           class="btn btn-default">{{ in_array($mensal->getOriginal()['status'], [4,5]) ? 'Visualizar' : 'Editar' }}</a></td>
                                                    {{--@if($mensal->getOriginal()['status'] < 4)
                                                        <td><a href="{{ route('mensalidade.show', $mensal) }}"
                                                               target="_blank" class="btn btn-warning">Boleto avulso</a>
                                                        </td>
                                                    @endif--}}
                                                </tr>
                                            @endforeach
                                            {{--<tr>
                                                <td><a href="pages/examples/invoice.html">OR1848</a></td>
                                                <td>Samsung Smart TV</td>
                                                <td><span class="label label-warning">Pending</span></td>
                                                <td>
                                                    <div class="sparkbar" data-color="#f39c12" data-height="20">90,80,-90,70,61,-83,68</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><a href="pages/examples/invoice.html">OR7429</a></td>
                                                <td>iPhone 6 Plus</td>
                                                <td><span class="label label-danger">Delivered</span></td>
                                                <td>
                                                    <div class="sparkbar" data-color="#f56954" data-height="20">90,-80,90,70,-61,83,63</div>
                                                </td>
                                            </tr>--}}
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.table-responsive -->
                                </div>
                                <!-- /.box-footer -->
                            </div>
                        </div>


                        <input type="hidden" name="_method" value="PUT">
                        <div class="box-footer">
                            @if($dados->status <> 5)
                                <button type="submit" class="btn btn-primary">Salvar</button>
                            @endif
                          {{--  @if(in_array($dados->status, [1,2]))
                                <a href="{{ route('contratos.mensalidades.gerar', $dados->id) }}" target="_blank"
                                   class="btn btn-success ">Gerar mensalidades</a>
                            @endif--}}

                                {{--TODO verificar--}}
                            {{--<a href="javascript:;" target="_blank"
                               class="btn btn-warning"> <i class="fa fa-print"></i> Imprimir contrato</a>--}}
                            <a href="{{ URL::previous() }}" class="btn btn-primary pull-right">Voltar</a>
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