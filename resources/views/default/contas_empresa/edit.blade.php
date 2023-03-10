@extends('default.layout.main')

@section('content')
    <section class="content">
        @include('default.errors.errors')
        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('contas_empresa.update', $dados->id) }}" method="post">
                {!! csrf_field() !!}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Dados conta</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="language">Status</label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ old('status', $dados->status) == 1 ? 'active' : ($dados->status == 1 ? 'active' : '') }}">
                                        <input type="radio" value="1" {{ old('status', $dados->status) == 1 ? 'checked' : ($dados->status == 1 ? 'checked' : '')  }} name="status" autocomplete="off">Ativo
                                    </label>
                                    <label class="btn btn-primary {{ old('status', $dados->status) == 0 ? 'active' : ($dados->status == 0 ? 'active' : '') }}">
                                        <input type="radio" value="0" {{ old('status', $dados->status) == 0 ? 'checked' : ($dados->status == 0 ? 'checked' : '')  }} name="status" autocomplete="off">Inativo
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label>Banco</label>
                                <select class="form-control select2"  name="banco_id" data-placeholder="Selecione um banco" style="width: 100%;">
                                    @foreach($bancos as $banco)
                                        <option @if(old('banco_id', $dados->banco_id) == $banco->id) selected @endif value="{{ $banco->id }}">{{ $banco->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Agencia</label>
                                <input type="text" name="agencia" value="{{ old('agencia', $dados->agencia) }}" class="form-control" placeholder="Agencia">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Agencia Digito</label><br>
                                <small><i>Se possuir</i></small><br>
                                <input type="text" name="agenciaDv" value="{{ old('agenciaDv', $dados->agenciaDv) }}" class="form-control" placeholder="Agencia Digito">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Conta</label>
                                <input type="text" name="conta" value="{{ old('conta', $dados->conta) }}" class="form-control" placeholder="Conta">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Conta digito</label><br>
                                <small><i>Se possuir (Brasdescon, HSBC, Itau)</i></small><br>
                                <input type="text" name="contaDv" value="{{ old('contaDv', $dados->contaDv) }}" class="form-control" placeholder="Conta digito">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Chave Pix</label><br>
                                <small><i>Email, Telefone, Chave aleat??ria</i></small><br>
                                <input type="text" name="chave_pix" value="{{ old('chave_pix', $dados->chave_pix) }}" class="form-control" placeholder="Chave Pix">
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="language">Utilizado para boleto? <br>
                                    <small><i>Esta conta sera utilizada para gerar boleto do respectivo banco. A ativa????o do mesmo desativa as outras contas do mesmo banco.</i></small></label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ old('usar_boleto', $dados->usar_boleto) == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1" {{ old('usar_boleto', $dados->usar_boleto) == 1 ? 'checked' : ''  }} name="usar_boleto" id="pt" autocomplete="off">Sim
                                    </label>
                                    <label class="btn btn-primary {{ old('usar_boleto', $dados->usar_boleto) === 0 ? 'active' : ''  }}">
                                        <input type="radio" value="0" {{ old('usar_boleto', $dados->usar_boleto) === 0 ? 'checked' : ''  }} name="usar_boleto" id="en" autocomplete="off">N??o
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="language">Aceita TED - Transfer??ncia banc??ria?<br>
                                    <small><i>Esta conta aceitar?? transfer??ncias banc??rias. <br>Poder?? ser da mesma ou de outras institui????es banc??rias.</i></small>
                                </label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ old('recebe_ted', $dados->recebe_ted) == 1 ? 'active' : ($dados->recebe_ted == 1 ? 'active' : '') }}">
                                        <input type="radio" value="1" {{ old('recebe_ted', $dados->recebe_ted) == 1 ? 'checked' : ($dados->recebe_ted == 1 ? 'checked' : '')  }} name="recebe_ted" autocomplete="off">Sim
                                    </label>
                                    <label class="btn btn-primary {{ old('recebe_ted', $dados->recebe_ted) == 0 ? 'active' : ($dados->recebe_ted == 0 ? 'active' : '') }}">
                                        <input type="radio" value="0" {{ old('recebe_ted', $dados->recebe_ted) == 0 ? 'checked' : ($dados->recebe_ted == 0 ? 'checked' : '')  }} name="recebe_ted" autocomplete="off">N??o
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="favorecido">Nome do Favorecido da conta</label>
                                <input type="text" name="favorecido" value="{{ old('favorecido', $dados->favorecido) }}" class="form-control" placeholder="Nome do favorecido da conta">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="cpf">CPF ou CNPJ do favorecido</label><br>
                                <input type="text" name="cpfcnpj" value="{{ old('cpfcnpj', $dados->cpfcnpj) }}" class="form-control" placeholder="CPF ou CNPJ do favorecido">
                            </div>
                        </div>
                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Dados boleto</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Vence em quanto dias?</label><br>
                                <small><i>Dias uteis</i></small><br>
                                <input type="text" name="dataVencimento" value="{{ old('dataVencimento', $dados->dataVencimento) }}" class="form-control"  placeholder="Vence em quanto dias?">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Multa</label><br>
                                <small><i>Em porcentagem</i></small><br>
                                <input type="text" name="multa" value="{{ old('multa', $dados->multa) }}" class="form-control"  placeholder="Multa">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Juros (porcentagem ao m??s)</label><br>
                                <small><i>Em porcentagem</i></small><br>
                                <input type="text" name="juros" value="{{ old('juros', $dados->juros) }}" class="form-control"  placeholder="Juros (porcentagem ao m??s)">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Juros e multa ap??s quantos dias</label><br>
                                <small><i>Em porcentagem</i></small><br>
                                <input type="text" name="juros_apos" value="{{ old('juros_apos', $dados->juros_apos) }}" class="form-control"  placeholder="Juros e multa ap??s quantos dias">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Protestar ap??s</label><br>
                                <small><i>Coloque 0 (zero) se n??o houver protesto</i></small><br>
                                <input type="text" name="diasProtesto" value="{{ old('diasProtesto', $dados->diasProtesto) }}" class="form-control"  placeholder="Juros ap??s quantos dias">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Carteira</label><br>
                                <small><i>Somente Banco do Brasil</i></small><br>
                                <input type="text" name="carteira" value="{{ old('carteira', $dados->carteira) }}" class="form-control"  placeholder="Carteira">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Conv??nio</label><br>
                                <small><i>Somente (Banco do Brasil)</i></small><br>
                                <input type="text" name="convenio" value="{{ old('convenio', $dados->convenio) }}" class="form-control"  placeholder="Conv??nio">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Varia????o carteira</label><br>
                                <small><i>Somente (Banco do Brasil)</i></small><br>
                                <input type="text" name="variacaoCarteira" value="{{ old('variacaoCarteira', $dados->variacaoCarteira) }}" class="form-control"  placeholder="Varia????o carteira">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Range</label><br>
                                <small><i>Somente (HSBC)</i></small><br>
                                <input type="text" name="range" value="{{ old('range', $dados->range) }}" class="form-control"  placeholder="Range">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">C??digo do cliente</label><br>
                                <small><i>Somente (Bradesco, CEF, Santander)</i></small><br>
                                <input type="text" name="codigoCliente" value="{{ old('codigoCliente', $dados->codigoCliente) }}" class="form-control"  placeholder="C??digo do cliente">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">IOS</label><br>
                                <small><i>Somente (Santander).</i></small><br>
                                <input type="text" name="ios" value="{{ old('ios', $dados->ios) }}" class="form-control"  placeholder="IOS">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Aceite</label>
                                <input type="text" name="aceite" value="{{ old('aceite', $dados->aceite) }}" class="form-control"  placeholder="Aceite">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Especie de documento</label>
                                <input type="text" name="especieDoc" value="{{ old('especieDoc', $dados->especieDoc) }}" class="form-control"  placeholder="Especie de documento">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Mensagem Linha 1</label>
                                <input type="text" name="msg1" value="{{ old('msg1', $dados->msg1) }}" class="form-control"  placeholder="Mensagem Linha 1">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Mensagem Linha 2</label>
                                <input type="text" name="msg2" value="{{ old('msg2', $dados->msg2) }}" class="form-control"  placeholder="Mensagem Linha 2">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Mensagem Linha 3</label>
                                <input type="text" name="msg3" value="{{ old('msg3', $dados->msg3) }}" class="form-control"  placeholder="Mensagem Linha 3">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Mensagem Linha 4</label>
                                <input type="text" name="msg4" value="{{ old('msg4', $dados->msg4) }}" class="form-control"  placeholder="Mensagem Linha 4">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Mensagem Linha 5</label>
                                <input type="text" name="msg5" value="{{ old('msg5', $dados->msg5) }}" class="form-control"  placeholder="Mensagem Linha 5">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Instru????es 1</label>
                                <input type="text" name="inst1" value="{{ old('inst1', $dados->inst1) }}" class="form-control"  placeholder="Instru????es 1">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Instru????es 2</label>
                                <input type="text" name="inst2" value="{{ old('inst2', $dados->inst2) }}" class="form-control"  placeholder="Instru????es 2">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Instru????es 3</label>
                                <input type="text" name="inst3" value="{{ old('inst3', $dados->inst3) }}" class="form-control"  placeholder="Instru????es 3">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Instru????es 4</label>
                                <input type="text" name="inst4" value="{{ old('inst4', $dados->inst4) }}" class="form-control"  placeholder="Instru????es 4">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Instru????es 5</label>
                                <input type="text" name="inst5" value="{{ old('inst5', $dados->inst5) }}" class="form-control"  placeholder="Instru????es 5">
                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" name="_method" value="PUT">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a class="btn btn-primary pull-right" href="{{ route('contas_empresa.index') }}">Voltar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
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
