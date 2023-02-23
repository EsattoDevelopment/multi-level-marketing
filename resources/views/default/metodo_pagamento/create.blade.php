@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('metodo_pagamento.store') }}" method="post">
                {!! csrf_field() !!}
                <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cadastro de Método de Pagamento</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="status">Status</label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ old('status') == 1 ? 'active' : ''}}">
                                        <input type="radio" value="1" {{ old('status') == 1 ? 'checked' : ''}} name="status"
                                               autocomplete="off">Ativo
                                    </label>
                                    <label class="btn btn-primary {{ old('status') == 0 ? 'active' : ''}}">
                                        <input type="radio" value="0" {{ old('status') == 0 ? 'checked' : ''}} name="status"
                                               autocomplete="off">Inativo
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="name">Nome</label><br>
                                <small>Aparecerá no label do botão de pagamento</small>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Nome">
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="">Descrição da taxa</label><br>
                                <small>Aparecerá na descrição do pagamento quando o valor da taxa for maior que 0.00</small>
                                <input type="text" name="taxa_descricao" value="{{ old('taxa_descricao') }}" class="form-control" placeholder="Descrição da taxa">
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="">Valor Fixo da Taxa </label><br>
                                <small> Será somado ao valor do pedido<b class="text-red"> (deixar zerado caso já tenha um campo especifico. Ex: Boleto)</b></small>
                                <div class="input-group">
                                    <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                    <input type="text" class="form-control" name="taxa_valor" placeholder="0,00"
                                           value="{{ old('valor_taxa', '0.00') }}"
                                           data-affixes-stay="true" data-prefix="" data-thousands=""
                                           data-decimal=".">
                                </div>
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="">Valor Percentual da Taxa </label><br>
                                <small> Será somado ao valor do pedido<b class="text-red"> (deixar zerado caso já tenha um campo especifico. Ex: Boleto)</b></small>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="taxa_porcentagem" placeholder="0,00"
                                           value="{{ old('taxa_porcentagem', '0.00') }}"
                                           data-affixes-stay="true" data-prefix="" data-thousands=""
                                           data-decimal=".">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h4 class="box-title">Configurações adicionais</h4>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="nome_codigo_conta">Nome ou código da conta</label>
                                <input type="text" name="nome_codigo_conta" value="{{ old('nome_codigo_conta') }}" class="form-control" placeholder="Nome ou código da conta">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="codigo_carteira">Código da carteira</label>
                                <input type="text" name="codigo_carteira" value="{{ old('codigo_carteira') }}" class="form-control" placeholder="Código da carteira">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="configuracao">Token comum</label>
                                <input type="text" name="configuracao[]" value="{{ old('configuracao') }}" class="form-control"  placeholder="token comum">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="configuracao">Token para Transferência</label>
                                <input type="text" name="configuracao[]" value="{{ old('configuracao') }}" class="form-control"  placeholder="token para transferência">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="configuracao">Token para TED</label>
                                <input type="text" name="configuracao[]" value="{{ old('configuracao') }}" class="form-control"  placeholder="token para TED">
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </div>
                </form>

            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
@endsection

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/i18n/pt-BR.js') }}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>
@endsection