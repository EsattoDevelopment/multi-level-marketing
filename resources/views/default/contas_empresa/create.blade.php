@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('contas_empresa.store') }}" method="post">
                {!! csrf_field() !!}
                <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Dados conta</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="language">Status</label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary  {{ old('status') == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1" {{ old('status') == 1 ? 'checked' : ''  }} name="status" id="pt" autocomplete="off">Ativo
                                    </label>
                                    <label class="btn btn-primary  {{ old('status') == 0 ? 'active' : ''  }}">
                                        <input type="radio" value="0" {{ old('status') == 0 ? 'checked' : ''  }} name="status" id="en" autocomplete="off">Inativo
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label>Banco</label>
                                <select class="form-control select2"  name="banco_id" data-placeholder="Selecione um banco" style="width: 100%;">
                                    @foreach($bancos as $banco)
                                        <option @if(old('banco_id') == $banco->id) selected @endif value="{{ $banco->id }}">{{ $banco->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Agencia</label>
                                <input type="text" name="agencia" value="{{ old('agencia') }}" class="form-control" placeholder="Agencia">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Agencia Digito</label><br>
                                <small><i>Se possuir</i></small></label><br>
                                <input type="text" name="agenciaDv" value="{{ old('agenciaDv') }}" class="form-control" placeholder="Agencia Digito">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Conta</label>
                                <input type="text" name="conta" value="{{ old('conta') }}" class="form-control" placeholder="Conta">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Conta digito</label><br>
                                <small><i>Se possuir (Brasdescon, HSBC, Itau)</i></small></label><br>
                                <input type="text" name="contaDv" value="{{ old('contaDv') }}" class="form-control" placeholder="Conta digito">
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="language">Utilizado para boleto? <br>
                                    <small><i>Esta conta sera utilizada para gerar boleto do respectivo banco. <br>A ativação do mesmo desativa as outras contas do mesmo banco.</i></small>
                                </label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary @if(!old('usar_boleto')) active @endif {{ old('usar_boleto') == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1" @if(!old('usar_boleto')) checked @endif {{ old('usar_boleto') == 1 ? 'checked' : ''  }} name="usar_boleto" id="pt" autocomplete="off">Sim
                                    </label>
                                    <label class="btn btn-primary {{ old('usar_boleto') === 0 ? 'active' : ''  }}">
                                        <input type="radio" value="0" {{ old('usar_boleto') === 0 ? 'checked' : ''  }} name="usar_boleto" id="en" autocomplete="off">Não
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="language">Aceita TED - Transferência bancária?<br>
                                    <small><i>Esta conta aceitará transferências bancárias. <br>Poderá ser da mesma ou de outras instituições bancárias.</i></small>
                                </label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary  {{ old('recebe_ted') == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1" {{ old('recebe_ted') == 1 ? 'checked' : ''  }} name="recebe_ted" id="pt" autocomplete="off">Sim
                                    </label>
                                    <label class="btn btn-primary  {{ old('recebe_ted') == 0 ? 'active' : ''  }}">
                                        <input type="radio" value="0" {{ old('recebe_ted') == 0 ? 'checked' : ''  }} name="recebe_ted" id="en" autocomplete="off">Não
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="favorecido">Nome do Favorecido da conta</label>
                                <input type="text" name="favorecido" value="{{ old('favorecido') }}" class="form-control" placeholder="Nome do favorecido da conta">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="cpf">CPF ou CNPJ do favorecido</label><br>
                                <input type="text" name="cpfcnpj" value="{{ old('cpfcnpj') }}" class="form-control" placeholder="CPF ou CNPJ do favorecido">
                            </div>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Dados boleto</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body">

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Vence em quanto dias?</label><br>
                                <small><i>Dias uteis</i></small></label><br>
                                <input type="text" name="dataVencimento" value="{{ old('dataVencimento') }}" class="form-control"  placeholder="Vence em quanto dias?">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Multa</label><br>
                                <small><i>Em porcentagem</i></small></label><br>
                                <input type="text" name="multa" value="{{ old('multa') }}" class="form-control"  placeholder="Multa">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Juros (porcentagem ao mês)</label><br>
                                <small><i>Em porcentagem</i></small></label><br>
                                <input type="text" name="juros" value="{{ old('juros') }}" class="form-control"  placeholder="Juros (porcentagem ao mês)">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Juros e multa após quantos dias</label><br>
                                <small><i>Em porcentagem</i></small></label><br>
                                <input type="text" name="juros_apos" value="{{ old('juros_apos') }}" class="form-control"  placeholder="Juros e multa após quantos dias">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Protestar após</label><br>
                                <small><i>Coloque 0 (zero) se não houver protesto</i></small></label><br>
                                <input type="text" name="diasProtesto" value="{{ old('diasProtesto') }}" class="form-control"  placeholder="Juros após quantos dias">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Carteira</label><br>
                                <small><i>Somente Banco do Brasil</i></small></label><br>
                                <input type="text" name="carteira" value="{{ old('carteira') }}" class="form-control"  placeholder="Carteira">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Convênio</label><br>
                                <small><i>Somente (Banco do Brasil)</i></small></label><br>
                                <input type="text" name="convenio" value="{{ old('convenio') }}" class="form-control"  placeholder="Convênio">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Variação carteira</label><br>
                                <small><i>Somente (Banco do Brasil)</i></small></label><br>
                                <input type="text" name="variacaoCarteira" value="{{ old('variacaoCarteira') }}" class="form-control"  placeholder="Variação carteira">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Range</label><br>
                                <small><i>Somente (HSBC)</i></small></label><br>
                                <input type="text" name="range" value="{{ old('range') }}" class="form-control"  placeholder="Range">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Código do cliente</label><br>
                                <small><i>Somente (Bradesco, CEF, Santander)</i></small></label><br>
                                <input type="text" name="codigoCliente" value="{{ old('codigoCliente') }}" class="form-control"  placeholder="Código do cliente">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">IOS</label><br>
                                <small><i>Somente (Santander).</i></small></label><br>
                                <input type="text" name="ios" value="{{ old('ios') }}" class="form-control"  placeholder="IOS">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Aceite</label>
                                <input type="text" name="aceite" value="{{ old('aceite') }}" class="form-control"  placeholder="Aceite">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Especie de documento</label>
                                <input type="text" name="especieDoc" value="{{ old('especieDoc') }}" class="form-control"  placeholder="Especie de documento">
                            </div>



                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Mensagem Linha 1</label>
                                <input type="text" name="msg1" value="{{ old('msg1') }}" class="form-control"  placeholder="Mensagem Linha 1">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Mensagem Linha 2</label>
                                <input type="text" name="msg2" value="{{ old('msg2') }}" class="form-control"  placeholder="Mensagem Linha 2">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Mensagem Linha 3</label>
                                <input type="text" name="msg3" value="{{ old('msg3') }}" class="form-control"  placeholder="Mensagem Linha 3">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Mensagem Linha 4</label>
                                <input type="text" name="msg4" value="{{ old('msg4') }}" class="form-control"  placeholder="Mensagem Linha 4">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Mensagem Linha 5</label>
                                <input type="text" name="msg5" value="{{ old('msg5') }}" class="form-control"  placeholder="Mensagem Linha 5">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Instruções 1</label>
                                <input type="text" name="inst1" value="{{ old('inst1') }}" class="form-control"  placeholder="Instruções 1">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Instruções 2</label>
                                <input type="text" name="inst2" value="{{ old('inst2') }}" class="form-control"  placeholder="Instruções 2">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Instruções 3</label>
                                <input type="text" name="inst3" value="{{ old('inst3') }}" class="form-control"  placeholder="Instruções 3">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Instruções 4</label>
                                <input type="text" name="inst4" value="{{ old('inst4') }}" class="form-control"  placeholder="Instruções 4">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Instruções 5</label>
                                <input type="text" name="inst5" value="{{ old('inst5') }}" class="form-control"  placeholder="Instruções 5">
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a class="btn btn-primary pull-right"
                               href="{{ route('contas_empresa.index') }}">Voltar
                            </a>
                        </div>
                    </div><!-- /.box -->
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

    <script src="../../plugins/input-mask/jquery.inputmask.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
        $("input[name='cpfcnpj']").inputmask({
            mask: ['999.999.999-99', '99.999.999/9999-99'],
            showTooltip: true,
            showMaskOnHover: true
        });
    </script>
@endsection