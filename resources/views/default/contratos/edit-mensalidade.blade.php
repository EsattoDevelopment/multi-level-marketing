@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editar mensalidade</h3><br><br>
                        <b>Usuário</b>: {{ $dados->getRelation('contrato')->getRelation('usuario')->name }} <br>
                        <b>Numero contrato</b>: {{ $dados->getRelation('contrato')->getRelation('usuario')->codigo }} <br>
                        <b>Parcela</b>: {{ $dados->parcela }}
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form galeria="form" action="{{ route('mensalidade.update', $dados->id) }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="box-body">
                            <div class="form-group col-md-12">
                                <label class="text-red">Gera bonificações <br>
                                <small>Deixe em não, caso seja edição de mensalidade ou mensalidade retroativa que já pagou os bônus</small>
                                </label>
                                <select class="form-control" id="status" name="paga_bonus">
                                    <option value="1" >Sim</option>
                                    <option value="2" selected="selected">Não</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ $dados->getOriginal()['status'] == 1 ? 'selected="selected"' : '' }}>Aguardando</option>
                                    <option value="2" {{ $dados->getOriginal()['status'] == 2 ? 'selected="selected"' : '' }}>Proxima</option>
                                    <option value="3" {{ $dados->getOriginal()['status'] == 3 ? 'selected="selected"' : '' }}>Atrasada</option>
                                    <option value="4" {{ $dados->getOriginal()['status'] == 4 ? 'selected="selected"' : '' }}>Paga</option>
                                    <option value="5" {{ $dados->getOriginal()['status'] == 5 ? 'selected="selected"' : '' }}>Cancelada</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label class="text-red">Forma de pagamento</label>
                                <select class="form-control" required id="metodo_pagamento_id" name="metodo_pagamento_id">
                                    @foreach($metodo_pagamento as $mp)
                                        <option value="{{ $mp->id }}" {{ old('metodo_pagamento_id', $dados->metodo_pagamento_id) == $mp->id ? 'selected="selected"' : '' }} >{{ $mp->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Data vencimento</label>
                                <input type="text" name="dt_pagamento" value="{{ $dados->dt_pagamento }}" class="form-control datepicker">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Valor</label>
                                <input type="text" name="valor" value="{{ $dados->valor }}" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Valor pago</label>
                                <input type="text" name="valor_pago" value="{{ $dados->valor_pago }}" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Data do pagamento</label>
                                <input type="text" name="dt_baixa" value="{{ $dados->dt_baixa }}" class="form-control datepicker">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Código de barras</label>
                                <input type="text" name="codigo_de_barras" value="{{ $dados->codigo_de_barras }}" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Nosso numero</label>
                                <input type="text" name="nosso_numero" value="{{ $dados->nosso_numero }}" class="form-control">
                            </div>

                            <div class="form-group col-md-12">
                                <label>Numero do documento</label>
                                <input type="text" name="numero_documento" value="{{ $dados->numero_documento }}" class="form-control">
                            </div>

                        </div><!-- /.box-body -->

                        <input type="hidden" name="_method" value="PUT">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('contratos.edit') }}" class="btn btn-primary pull-right">Voltar</a>
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
                $("input[name='dt_pagamento'], input[name='dt_baixa']").inputmask({
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