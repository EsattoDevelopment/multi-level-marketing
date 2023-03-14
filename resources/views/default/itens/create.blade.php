@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-xs-12">
                <!-- form start -->
                <form role="form" action="{{ route('item.store') }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Definição do item</h3>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                <label for="exampleInputEmail1">Nome <span class="text-red">*</span></label>
                                <input required type="text" name="name" value="{{ old('name') }}"
                                       class="form-control" placeholder="Nome">
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                <label for="exampleInputEmail1">Valor<span class="text-red">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                    <input required type="text" name="valor" value="{{ old('valor') }}"
                                           class="form-control" placeholder="Valor {{ $sistema->moeda }}">
                                </div>
                            </div>
                            @if($sistema->rede_binaria)

                                <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                    <label for="exampleInputEmail1">Pontos binários <span class="text-red">*</span></label><br>
                                    <small><i>Pontos pagos pela rede binária</i></small>
                                    <input required type="text" name="pontos_binarios"
                                           value="{{ old('pontos_binarios') }}"
                                           class="form-control" placeholder="Pontos binários">
                                </div>

                                <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                    <label for="exampleInputEmail1">Teto pagamento binários dia <span class="text-red">*</span></label><br>
                                    <small><i>Valor maximo pago ao rodar o binario </i></small>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                        <input required type="text" name="teto_binario_dia"
                                               value="{{ old('teto_binario_dia') }}"
                                               class="form-control" placeholder="Teto pagamento binários dia">
                                    </div>
                                </div>
                            @endif

                            {{--<div class="form-group col-xs-12 col-sm-6 col-md-6">
                                <label for="exampleInputEmail1">Teto de ganho<span class="text-red">*</span></label><br>
                                <small><i>Valor de ganhos totais permitido pelo item</i></small>
                                <div class="input-group">
                                    <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                    <input type="text" name="teto_ganho_geral"
                                           value="{{ old('teto_ganho_geral') }}"
                                           class="form-control" placeholder="Teto de ganho {{ $sistema->moeda }}">
                                </div>
                            </div>--}}

                            <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                <label>Tipo Pedido <span class="text-red">*</span></label><br>
                                <small><i>Primeiro pedido aparece somente para usuários sem nenhuma compra anterior</i></small>
                                <select required class="form-control select2" name="tipo_pedido_id"
                                        data-placeholder="Selecione uma categoria" style="width: 100%;">
                                    <option value="">Selecione um tipo</option>
                                    @foreach($tipo_pedidos as $tp)
                                        <option @if(old('tipo_pedido_id') == $tp->id) selected
                                                @endif value="{{ $tp->id }}">{{ $tp->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if($sistema->update_titulo)
                                <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                    <label>Item realizar upgrade para qual titulo? <span class="text-red">*</span></label><br>
                                    <small><i>Ao comprar o titulo escolhido o usuário terá um upgrade de titulo automático.</i></small>
                                    <select required class="form-control select2" name="avanca_titulo"
                                            data-placeholder="Selecione uma categoria" style="width: 100%;">
                                        <option value="">Selecione um titulo</option>
                                        @foreach($titulos as $titulo)
                                            <option @if(old('avanca_titulo') == $titulo->id) selected
                                                    @endif value="{{ $titulo->id }}">{{ $titulo->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if($sistema->item_direcionado)
                                <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <label>Direcionado para</label><br>
                                    <small><i>Item será apenas visto pelo usuário indicado (apenas para usuario empresa)</i></small>
                                    <select class="form-control" id="empresa" name="user_id" style="width: 100%">

                                    </select>
                                </div>
                            @endif

                            {{--<div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-3">
                                <label>Quantidade de parcelas</label><br>
                                <small><i>Quantidade de parcelas do contrato</i></small>
                                <input type="text" class="form-control" name="qtd_parcelas"
                                       value="{{ old('qtd_parcelas') }}">
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-3">
                                <label>Valor das parcelas</label><br>
                                <small><i>Valor fixo das parcelas</i></small>
                                <input type="text" class="form-control" name="vl_parcelas"
                                       value="{{ old('vl_parcelas') }}">
                            </div>--}}

                            @if($sistema->sistema_saude)
                                <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-3">
                                    <label>Tempo do contrato em dias</label><br>
                                    <small><i>Tempo de vigência</i></small>
                                    <input type="text" class="form-control" name="temp_contrato"
                                           value="{{ old('temp_contrato') }}">
                                </div>
                            @endif

                            <div class="form-group col-xs-12 col-sm-6 col-md-3">
                                <label for="ativo">Ativo para compra?</label> <br>
                                <small><i>Disponivel para compra?</i></small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="ativo" class="flat-red" {{ old('ativo')  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="ativo" class="flat-red" {{ old('ativo', 0)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>

                            {{--<div class="form-group col-xs-12 col-sm-6 col-md-3">
                                <label for="quitar_com_bonus">Quita com bônus?</label> <br>
                                <small><i>Pode ser quitado com bônus?</i></small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="quitar_com_bonus" class="flat-red" {{ old('quitar_com_bonus')  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="quitar_com_bonus" class="flat-red" {{ old('quitar_com_bonus', 0)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>
--}}
                            {{--<div class="form-group col-xs-12 col-sm-6 col-md-3">
                                <label for="ativo_qtd">Ativar quantidade para compra?</label> <br>
                                <small><i>Quantidade do item para compra</i></small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="ativo_qtd" class="flat-red" {{ old('ativo_qtd')  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="ativo_qtd" class="flat-red" {{ old('ativo_qtd', 0)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>--}}

                            <div class="form-group col-xs-12 col-sm-6 col-md-3">
                                <label for="language">Cor do item <br>
                                    <small><i>Ecolha uma cor para aparecer na página de compra.</i></small>
                                </label><br>
                                <input name="cor_item" value="{{ old('cor_item') }}" type="text" class="form-control colorpicker colorpicker-element">
                            </div>

                            <div class="form-group col-xs-12 col-sm-6 col-md-6 col-lg-3">
                                <label>Tipo de Item</label>
                                <select class="form-control select2" name="tipo_pacote"
                                        data-placeholder="Selecione um tipo de Item" style="width: 100%;">
                                    @foreach(config('constants.tipo_pacote') as $key => $value)
                                        <option @if(old('tipo_pacote') == $key) selected @endif value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>

           {{--                 <div class="form-group col-xs-3 col-sm-12 col-md-3">
                                <label for="">Qtd. mínima</label>
                                <input type="number" class="form-control" name="qtd_min" min="1"
                                       value="{{ old('qtd_min') }}">
                            </div>
                            <div class="form-group col-xs-3 col-sm-12 col-md-3">
                                <label for="">Qtd. máxima</label>
                                <input type="number" class="form-control" name="qtd_max" min="1"
                                       value="{{ old('qtd_max') }}">
                            </div>--}}

{{--                            <div class="form-group col-xs-12 col-sm-12 col-md-6 no-padding">
                                <div class="form-group col-xs-12 col-sm-12 col-md-12">
                                    <label for="language">Faixa por depósito</label>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                                <input type="text" class="form-control" name="faixa_deposito_min"
                                                       value="{{ old('faixa_deposito_min') }}" placeholder="Mínimo"
                                                       data-affixes-stay="true" data-prefix="" data-thousands="."
                                                       data-decimal=",">
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                                <input type="text" class="form-control" name="faixa_deposito_max"
                                                       value="{{ old('faixa_deposito_max') }}" placeholder="Máximo"
                                                       data-affixes-stay="true" data-prefix="" data-thousands="."
                                                       data-decimal=",">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>--}}

{{--                            <div class="form-group col-xs-12 col-sm-6 col-md-3">
                                <label for="language">Potencial Mensal (Teto)</label>
                                <div class="input-group">
                                    <input type="text" name="potencial_mensal_teto"
                                           value="{{ old('potencial_mensal_teto') }}" min="1" max="100"
                                           class="form-control" placeholder="">
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-sm-12 col-md-3">
                                <label for="language">Carência Mínima</label>
                                <div class="input-group">
                                    <input type="number" name="carencia_minima"
                                           value="{{ old('carencia_minima') }}" min="1"
                                           class="form-control">
                                    <span class="input-group-addon">dias</span>
                                </div>
                            </div>

                            <div class="form-group col-xs-12 col-sm-12 col-md-6 no-padding">
                                <div class="form-group col-xs-12 col-sm-12 col-md-6">
                                    <label for="language">Contrato em dias</label>
                                    <div class="input-group">
                                        <input type="number" name="contrato"
                                               value="{{ old('contrato') }}" min="1"
                                               class="form-control" placeholder="">
                                        <span class="input-group-addon">dias</span>
                                    </div>
                                </div>

                                <div class="form-group col-xs-12 col-sm-12 col-md-6">
                                    <label for="exampleInputEmail1">Contrato em meses<span class="text-red">*</span></label>
                                    <div class="input-group">
                                        <input type="number" min="1" required name="meses"
                                               value="{{ old('meses') }}"
                                               class="form-control" placeholder="Contrato em meses">
                                        <span class="input-group-addon">meses</span>

                                    </div>
                                </div>
                            </div>--}}

    {{--                        <div class="form-group col-xs-12 col-sm-12 col-md-12 no-padding">
                                <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                    <label for="language">Taxa de resgate</label>
                                    <div class="input-group">
                                        <input type="number" name="taxa_resgate"
                                               value="{{ old('taxa_resgate') }}" min="0" max="100"
                                               class="form-control" placeholder="">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>
                            </div>--}}

                            <div class="form-group col-xs-12">
                                <label for="image">Imagem</label>
                                <input type="file" id="imagem" name="imagem">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Chamada</label>
                                <textarea class="form-control" id="chamada" name="chamada" rows="3" maxlength="255"
                                          placeholder="Chamada...">{{ old('chamada') }}</textarea>
                                <div id="textarea_feedback"></div>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="descricao">Descrição</label>
                                <textarea class="textarea" name="descricao" placeholder="Descrição..."
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('descricao') }}</textarea>
                            </div>


                            <div class="form-group col-xs-12">
                                <label for="descricao2">Aviso</label>
                                <textarea class="textarea" name="descricao2" placeholder="Aviso..."
                                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('descricao2') }}</textarea>
                            </div>

                        </div><!-- /.box -->
                    </div>

                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Definição de bônus</h3>
                        </div>

                        <div class="box-body">
                            <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                <label for="ativo">Paga Bônus?</label> <br>
                                <small><i>Define se o item pagará qualquer tipo de bônus</i></small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="pagar_bonus" class="flat-red" {{ old('ativo', 0)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="pagar_bonus" class="flat-red" {{ old('ativo', 0)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>

                            <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                <label for="ativo">Paga Bônus do Título?</label> <br>
                                <small><i>Define se o item pagará o bônus cadastrado no título</i></small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="pagar_bonus_titulo" class="flat-red" {{ old('ativo', 0)  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="pagar_bonus_titulo" class="flat-red" {{ old('ativo', 0)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>

{{--                            @if($sistema->tipo_bonus_indicador == 1)
                                <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                    <label for="exampleInputEmail1">Bonus Indicador<span class="text-red">*</span></label><br>
                                    <small><i>(Patrocinador/Upline) em {{ $sistema->moeda }}</i></small>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                        <input type="text" name="bonus_indicador"
                                               value="{{ old('bonus_indicador') }}"
                                               class="form-control" placeholder="Bonus Indicador">
                                    </div>
                                </div>
                            @elseif($sistema->tipo_bonus_indicador == 2)

                                <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                    <label for="exampleInputEmail1">Bonus Indicador (Percentual) <span class="text-red">*</span></label><br>
                                    <small><i>Porcentagem do valor do item pago ao patrocinador</i></small>
                                    <div class="input-group">
                                        <span class="input-group-addon">%</span>
                                        <input type="text" name="bonus_indicador_percentual"
                                               value="{{ old('bonus_indicador_percentual') }}"
                                               class="form-control" placeholder="Bonus Indicador (Percentual)">
                                    </div>
                                </div>
                            @endif

                            @if($sistema->tipo_bonus_equiparacao == 1)

                                <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                    <label for="exampleInputEmail1">Bonus Equiparação<span class="text-red">*</span></label><br>
                                    <small><i>(Equiparação/Upline) em {{ $sistema->moeda }}</i></small>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                        <input type="text" name="bonus_equiparacao"
                                               value="{{ old('bonus_equiparacao') }}"
                                               class="form-control" placeholder="Bonus Equiparação">
                                    </div>
                                </div>
                            @elseif($sistema->tipo_bonus_equiparacao == 2)

                                <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                    <label for="exampleInputEmail1">Bonus Equiparação (Percentual) <span class="text-red">*</span></label><br>
                                    <small><i>Porcentagem do valor do item pago na equiparação</i></small>
                                    <div class="input-group">
                                        <span class="input-group-addon">%</span>
                                        <input type="text" name="bonus_equiparacao_percentual"
                                               value="{{ old('bonus_equiparacao_percentual') }}"
                                               class="form-control" placeholder="Bonus Equiparação (Percentual)">
                                    </div>
                                </div>
                            @endif--}}

                            <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                <label>Bônus de Adesão</label>
                                <select class="form-control select2"  name="configuracao_bonus_adesao_id" data-placeholder="Selecione a configuração do bônus de adesão" style="width: 100%;">
                                    <option value="0">Nenhum</option>
                                    @foreach($configuracaoBonus as $configbonus)
                                        <option @if(old('configuracao_bonus_adesao_id') == $configbonus->id) selected @endif value="{{ $configbonus->id }}">{{ $configbonus->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                <label>Bônus de Rentabilidade</label>
                                <select class="form-control select2"  name="configuracao_bonus_rentabilidade_id" data-placeholder="Selecione a configuração do bônus de rentabilidade" style="width: 100%;">
                                    <option value="0">Nenhum</option>
                                    @foreach($configuracaoBonus as $configbonus)
                                        <option @if(old('configuracao_bonus_rentabilidade_id') == $configbonus->id) selected @endif value="{{ $configbonus->id }}">{{ $configbonus->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    @if($sistema->pontos_pessoais_calculo_exibicao == 1 || $sistema->pontos_equipe_calculo_exibicao == 1)
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title">Definição de pontos</h3>
                            </div>

                            <div class="box-body">
                                @if($sistema->pontos_pessoais_calculo_exibicao == 1)
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label for="exampleInputEmail1">GMilhas Pessoais<span class="text-red">*</span></label><br>
                                        <small>Será multiplicado pela quantidade de itens</small>
                                        <div class="input-group">
                                            <input type="text" min="0" required name="pontos_pessoais"
                                                   value="{{ old('pontos_pessoais') }}"
                                                   class="form-control" placeholder="GMilhas Pessoais">
                                        </div>
                                    </div>
                                @endif

                                @if($sistema->pontos_equipe_calculo_exibicao == 1)
                                    <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                        <label for="exampleInputEmail1">GMilhas Equipe<span class="text-red">*</span></label><br>
                                        <small>Será multiplicado pela quantidade de itens</small>
                                        <div class="input-group">
                                            <input type="text" required min="0" name="pontos_equipe"
                                                   value="{{ old('pontos_equipe') }}"
                                                   class="form-control" placeholder="GMilhas Equipe">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

              {{--      <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Resgate mínimo de rentabilidade</h3>
                        </div>

                        <div class="box-body">

                            <div class="form-group col-xs-12 col-sm-6 col-md-3">
                                <label for="resgate_minimo_automatico">Resgate mínimo automático?</label> <br>
                                <small><i>Transferência automática de rendimentos para carteira quando o valor mínimo é atingido</i></small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="resgate_minimo_automatico" class="flat-red" {{ old('resgate_minimo_automatico')  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="resgate_minimo_automatico" class="flat-red" {{ old('resgate_minimo_automatico', 0)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>

                            <div class="form-group col-xs-12 col-sm-12 col-md-12 no-padding">
                                <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                    <label for="language">Resgate Mínimo <small><i>x% de P.M acum.</i></small></label>
                                    <div class="input-group">
                                        <input type="number" name="resgate_minimo"
                                               value="{{ old('resgate_minimo') }}" min="1" max="100"
                                               class="form-control">
                                        <span class="input-group-addon">%</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>--}}
{{--
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Finalização de contratos</h3>
                        </div>

                        <div class="box-body">
                            <div class="form-group col-xs-12 col-sm-6 col-md-3">
                                <label for="finaliza_contrato_automatico">Finaliza contrato automaticamente?</label> <br>
                                <small><i>Finaliza contratos automaticamente após sua vigência</i></small>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="finaliza_contrato_automatico" class="flat-red" {{ old('finaliza_contrato_automatico')  == 1 ? 'checked' : '' }}>
                                    Sim
                                </label>
                                <label>
                                    <input type="radio" value="0" name="finaliza_contrato_automatico" class="flat-red" {{ old('finaliza_contrato_automatico', 0)  == 0 ? 'checked' : '' }}>
                                    Não
                                </label>
                            </div>

                            <div class="form-group col-xs-12 col-sm-6 col-md-3">
                                <label for="dias_carencia_transferencia">Carência para transferência automática</label><br>
                                <small><i>Carência para transferência automática no valor do contrato para carteira (Este campo não esta sendo utilizado, implementação futura)</i></small>
                                <div class="input-group">
                                    <input type="number" name="dias_carencia_transferencia"
                                           value="{{ old('dias_carencia_transferencia') }}" min="0"
                                           class="form-control" placeholder="">
                                    <span class="input-group-addon">dias</span>
                                </div>
                            </div>

                            <div class="form-group col-xs-12 col-sm-6 col-md-3">
                                <label for="dias_carencia_saque">Carência para saque</label><br>
                                <small><i>Carência para saque em carteira (Este campo não esta sendo utilizado, implementação futura)</i></small>
                                <div class="input-group">
                                    <input type="number" name="dias_carencia_saque"
                                           value="{{ old('dias_carencia_saque', 5) }}" min="0"
                                           class="form-control" placeholder="">
                                    <span class="input-group-addon">dias</span>
                                </div>
                            </div>
                        </div>
                    </div>--}}

           {{--         <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Recontratação</h3>
                        </div>

                        <div class="box-body">
                            <div class="form-group col-xs-12 col-sm-6">
                                <div class="input-group">
                                    <label for="habilita_recontratacao_automatica">Habilita recontratação automática?</label> <br>
                                    <small><i>Habilita a função recontratação automática para clientes</i></small>
                                    <br>
                                    <label style="padding-right: 25px">
                                        <input type="radio" value="1" name="habilita_recontratacao_automatica" class="flat-red" {{ old('habilita_recontratacao_automatica')  == 1 ? 'checked' : '' }}>
                                        Sim
                                    </label>
                                    <label>
                                        <input type="radio" value="0" name="habilita_recontratacao_automatica" class="flat-red" {{ old('habilita_recontratacao_automatica')  == 0 ? 'checked' : '' }}>
                                        Não
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-xs-12 col-sm-6">
                                <div class="input-group">
                                    <label for="modo_recontratacao_automatica">Qual modo padrão para função "Recontratação Automática"?</label> <br>
                                    <small><i>Define o modo padrão para função recontratação automática na aquisição de novos contratos pelos clientes</i></small>
                                    <br>
                                    <label>
                                        <input type="radio" value="0" name="modo_recontratacao_automatica" class="flat-red" {{ old('modo_recontratacao_automatica')  == 0 ? 'checked' : '' }}>
                                        Não recontratar automaticamente.
                                    </label>
                                    <br>
                                    <label>
                                        <input type="radio" value="1" name="modo_recontratacao_automatica" class="flat-red" {{ old('modo_recontratacao_automatica')  == 1 ? 'checked' : '' }}>
                                        Recontratar automaticamente o valor original do contrato.
                                    </label>
                                    <br>
                                    <label>
                                        <input type="radio" value="2" name="modo_recontratacao_automatica" class="flat-red" {{ old('modo_recontratacao_automatica')  == 2 ? 'checked' : '' }}>
                                        Recontratar automaticamente o saldo final do contrato.
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
--}}
                    @if($sistema->sistema_saude)
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Sistema saúde</h3>
                            </div><!-- /.box-header -->
                            <div class="box-body">

                                @if($sistema->sistema_saude)
                                    <div class="form-group col-xs-12">
                                        <label>Valor da consulta</label><br>
                                        <input type="text" class="form-control" name="valor_consulta"
                                               value="{{ old('valor_consulta') }}">
                                    </div>

                                    <div class="form-group col-xs-12">
                                        <label>Valor da fisioterapia</label><br>
                                        <input type="text" class="form-control" name="valor_fisioterapia"
                                               value="{{ old('valor_fisioterapia') }}">
                                    </div>
                                @endif

                                @if($sistema->sistema_saude)
                                    <div class="form-group col-xs-12">
                                        <label for="descricao">Descrição Impressão</label>
                                        <textarea class="textarea" id="descricao_impressao" name="descricao_impressao"
                                                  placeholder="Descrição impressão..."
                                                  style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('descricao_impressao') }}</textarea>
                                    </div>
                                @endif

                                @if($sistema->sistema_saude)
                                    <div class="box box-warning">
                                        <div class="box-header with-border">
                                            <h3 class="box- title">Exames inclusos</h3>
                                        </div><!-- /.box-header -->
                                        <div class="box-body">
                                            <div class="form-group col-xs-12">
                                                <select id='exames' name="exames[]" multiple='multiple'>
                                                    @foreach($exames as $exame)
                                                        <option {->contains($exame) ? 'selected' : '' }} value='{{ $exame->id }}'>{{ $exame->nome }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="box box-default">
                        <div class="box-body">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('item.index') }}" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section><!-- /.content -->
@endsection

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/multiselect/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css') }}">

    <style>
        .custom-header {
            font-weight: 600;
            padding-left: 10px;
            color: #ffffff;
        }

        .ms-selectable .custom-header {
            background-color: #ff0000;
        }

        .ms-selection .custom-header {
            background-color: #0000ff;
        }
    </style>
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/i18n/pt-BR.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.13.0/full/ckeditor.js"></script>
    <script src="{{ asset('plugins/multiselect/jquery.quicksearch.min.js') }}"></script>
    <script src="{{ asset('plugins/multiselect/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="/plugins/iCheck/icheck.min.js"></script>
    <link rel="stylesheet" href="/plugins/iCheck/square/red.css">

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });

            $("input[name*='faixa_deposito_'").maskMoney();

            CKEDITOR.replace('descricao');
            CKEDITOR.replace('descricao2');

            @if($sistema->descricao_impressao) CKEDITOR.replace('descricao_impressao'); @endif

            //Colorpicker
            $('.colorpicker').colorpicker();

            $("#empresa").select2({
                placeholder: 'Escolha um usuario empresa',
                language: "pt-BR",
                minimumInputLength: 2,
                tags: false,
                ajax: {
                    delay: 250,
                    url: "{{ route('api.empresa.busca') }}",
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

            $('#exames').multiSelect({
                selectableHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Pesquisar'>",
                selectionHeader: "<input type='text' class='search-input' autocomplete='off' placeholder='Pesquisar'>",
                selectableFooter: "<div class='custom-header'>Não selecionados</div>",
                selectionFooter: "<div class='custom-header'>Selecionadas</div>",
                afterInit: function (ms) {
                    var that = this,
                        $selectableSearch = that.$selectableUl.prev(),
                        $selectionSearch = that.$selectionUl.prev(),
                        selectableSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selectable:not(.ms-selected)',
                        selectionSearchString = '#' + that.$container.attr('id') + ' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                        .on('keydown', function (e) {
                            if (e.which === 40) {
                                that.$selectableUl.focus();
                                return false;
                            }
                        });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                        .on('keydown', function (e) {
                            if (e.which == 40) {
                                that.$selectionUl.focus();
                                return false;
                            }
                        });
                },
                afterSelect: function () {
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function () {
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });
        });
    </script>
@endsection
