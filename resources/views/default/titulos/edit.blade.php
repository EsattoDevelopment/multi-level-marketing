@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edição de Titulos</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{ route('titulo.update', $dados->id) }}" method="post">
                        {!! csrf_field() !!}
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="status"><span>Habilita rede</span></label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ old('habilita_rede', $dados->habilita_rede) == true ? 'active' : ($dados->habilita_rede == true ? 'active' : '') }}">
                                        <input type="radio" value="1" {{ old('habilita_rede', $dados->habilita_rede) == true ? 'checked' : ($dados->habilita_rede == true ? 'checked' : '')  }} name="habilita_rede" autocomplete="off"><span>Sim</span>
                                    </label>
                                    <label class="btn btn-primary {{ old('habilita_rede', $dados->habilita_rede) == false ? 'active' : ($dados->habilita_rede == false ? 'active' : '') }}">
                                        <input type="radio" value="0" {{ old('habilita_rede', $dados->habilita_rede) == false ? 'checked' : ($dados->habilita_rede == false ? 'checked' : '')  }} name="habilita_rede" autocomplete="off"><span>Não</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Nome</label>                                <br>
                                <small><i>Nome exibido</i></small>
                                <input type="text" name="name" value="{{ old('name', $dados->name) }}" class="form-control" placeholder="Nome">
                            </div>

                            @if($sistema->sistema_viagem)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Acúmulo pessoal de milhas</label>
                                    <input type="text" name="acumulo_pessoal_milhas" value="{{ old('acumulo_pessoal_milhas', $dados->acumulo_pessoal_milhas) }}" class="form-control"  placeholder="Acúmulo pessoal de milhas">
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Milhas para indicador <small>(patrocinador)</small></label>
                                    <input type="text" name="milhas_indicador" value="{{ old('milhas_indicador', $dados->milhas_indicador) }}" class="form-control"  placeholder="Milhas para indicador">
                                </div>
                            @endif

                            @if($sistema->matriz_fechada || $sistema->matriz_unilevel)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Minimo diretos ativos (Matriz)</label><br>
                                    <small><i>Minimo de diretos ativos para subir para esse titulo</i></small>
                                    <input type="text" name="min_diretos_aprovados_matriz" value="{{ old('min_diretos_aprovados_matriz', $dados->min_diretos_aprovados_matriz) }}" class="form-control"  placeholder="Minimo diretos ativos (Matriz)">
                                </div>
                            @endif

                            @if($sistema->rede_binaria)

                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Minimo diretos ativos (Binario)</label><br>
                                    <small><i>Minimo de diretos ativos na perna menor para subir para esse titulo</i></small>
                                    <input type="text" name="min_diretos_aprovados_binario_perna" value="{{ old('min_diretos_aprovados_binario_perna', $dados->min_diretos_aprovados_binario_perna) }}" class="form-control"  placeholder="Minimo diretos ativos (Binário)">
                                </div>

                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Minimo pontuação</label><br>
                                    <small><i>Pontuação minima na perna menor para subir para esse titulo</i></small>
                                    <input type="text" name="min_pontuacao_perna_menor" value="{{ old('min_pontuacao_perna_menor', $dados->min_pontuacao_perna_menor) }}" class="form-control"  placeholder="Minimo pontuação">
                                </div>
                            @endif

                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Bonus para patrocinador</label><br>
                                <small><i>Bonus para patrocinador quando direto subir de titulo</i></small>
                                <div class="input-group">
                                    <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                    <input type="text" name="bonus_indicador" value="{{ old('bonus_indicador', $dados->bonus_indicador) }}" class="form-control"  placeholder="Dinheiro para patrocinador">
                                </div>

                            </div>

                            {{--                   @elseif($sistema->tipo_bonus_indicador == 2)

                                                   <div class="form-group col-xs-12 col-sm-6 col-md-6">
                                                       <label for="exampleInputEmail1">Bonus Indicador (Percentual) <span class="text-red">*</span></label><br>
                                                       <small><i>Porcentagem do valor do item pago ao patrocinador</i></small>
                                                       <div class="input-group">
                                                           <span class="input-group-addon">%</span>
                                                           <input type="text" name="bonus_indicador_percentual"
                                                                  value="{{ old('bonus_indicador_percentual', $dados->bonus_indicador_percentual) }}"
                                                                  class="form-control" placeholder="Bonus Indicador (Percentual)">
                                                       </div>
                                                   </div>
                                               @endif--}}

                            @if($sistema->rede_binaria)
                                {{--<div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Binário para o patrocinador</label><br>
                                    <small><i>Bonus para patrocinador quando direto subir de titulo</i></small>
                                    <input type="text" name="binario_patrocinado" value="{{ old('binario_patrocinado', $dados->binario_patrocinado) }}" class="form-control"  placeholder="Binário para o patrocinador">
                                </div>--}}

                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Percentual do binário</label>
                                    <input type="text" name="percentual_binario" value="{{ old('percentual_binario', $dados->percentual_binario) }}" class="form-control"  placeholder="Percentual dos pontos">
                                </div>

                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Teto pagamento diário do binário</label>
                                    <input type="text" name="teto_pagamento_sobre_binario" value="{{ old('teto_pagamento_sobre_binario', $dados->teto_pagamento_sobre_binario) }}" class="form-control"  placeholder="Teto pagamento diário do binário">
                                </div>
                            @endif

                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Teto financeiro mensal</label>
                                <input type="text" name="teto_mensal_financeiro" value="{{ old('teto_mensal_financeiro', $dados->teto_mensal_financeiro) }}" class="form-control"  placeholder="Teto financeiro mensal">
                            </div>

                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Valor equiparação</label>
                                <input type="text" name="equiparacao_percentual" value="{{ old('equiparacao_percentual', $dados->equiparacao_percentual) }}" class="form-control"  placeholder="Valor equiparação">
                            </div>

                            @if($sistema->sistema_viagem)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Bonus sobre os ganhos HVIP dos seus diretos</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                        <input type="text" name="bonus_hvip_diretos" value="{{ old('bonus_hvip_diretos', $dados->bonus_hvip_diretos) }}" class="form-control"  placeholder="Bonus sobre os ganhos HVIP dos seus diretos">
                                    </div>
                                </div>
                            @endif
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Cor do titulo</label>
                                <input type="text" name="cor" value="{{ old('cor',$dados->cor) }}" class="form-control colorpicker colorpicker-element"
                                       placeholder="Cor do titulo">
                            </div>
                            @if($sistema->update_titulo)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label>Titulo Superior</label>
                                    <select class="form-control select2"  name="titulo_superior" data-placeholder="Selecione o titulo superior" style="width: 100%;">
                                        <option value="0">Nenhum</option>
                                        @foreach($titulos as $titulo)
                                            <option @if(old('titulo_superior', $dados->titulo_superior) == $titulo->id) selected @endif value="{{ $titulo->id }}">{{ $titulo->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if($sistema->update_titulo)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="language">Titulo Inicial? <br>
                                        <small><i>Este titulo será atribuido a todos os ingressantes</i></small></label><br>
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary {{ old('titulo_inicial', $dados->titulo_inicial) == 1 ? 'active' : ''  }}">
                                            <input type="radio" value="1"  {{ old('titulo_inicial', $dados->titulo_inicial) == 1 ? 'checked' : ''  }} name="titulo_inicial" id="pt" autocomplete="off">Sim
                                        </label>
                                        <label class="btn btn-primary {{ old('titulo_inicial', $dados->titulo_inicial) == 0 ? 'active' : ''  }}">
                                            <input type="radio" value="0" {{ old('titulo_inicial', $dados->titulo_inicial) == 0 ? 'checked' : ''  }} name="titulo_inicial" id="en" autocomplete="off">Não
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="language">Recebe pontos? <br>
                                    <small><i>Utilizado geralmente para titulo incial (Titulo de cadastro)</i></small></label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ old('recebe_pontuacao', $dados->recebe_pontuacao) == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1"  {{ old('recebe_pontuacao', $dados->recebe_pontuacao) == 1 ? 'checked' : ''  }} name="recebe_pontuacao" id="pt" autocomplete="off">Sim
                                    </label>
                                    <label class="btn btn-primary {{ old('recebe_pontuacao', $dados->recebe_pontuacao) == 0 ? 'active' : ''  }}">
                                        <input type="radio" value="0" {{ old('recebe_pontuacao', $dados->recebe_pontuacao) == 0 ? 'checked' : ''  }} name="recebe_pontuacao" id="en" autocomplete="off">Não
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <h4>Parâmetros Para Pagar Bônus</h4>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>Bônus de Adesão</label>
                                <select class="form-control select2"  name="configuracao_bonus_adesao_id" data-placeholder="Selecione a configuração do bônus de adesão" style="width: 100%;">
                                    <option value="0">Nenhum</option>
                                    @foreach($configuracaoBonus as $configbonus)
                                        <option @if(old('configuracao_bonus_adesao_id', $dados->configuracao_bonus_adesao_id) == $configbonus->id) selected @endif value="{{ $configbonus->id }}">{{ $configbonus->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>Bônus de Rentabilidade</label>
                                <select class="form-control select2"  name="configuracao_bonus_rentabilidade_id" data-placeholder="Selecione a configuração do bônus de rentabilidade" style="width: 100%;">
                                    <option value="0">Nenhum</option>
                                    @foreach($configuracaoBonus as $configbonus)
                                        <option @if(old('configuracao_bonus_rentabilidade_id', $dados->configuracao_bonus_rentabilidade_id) == $configbonus->id) selected @endif value="{{ $configbonus->id }}">{{ $configbonus->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12">
                                <h4>Parâmetros Para Update Automático de Título</h4>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Quantidade de GMilhas pessoais <strong class="text-red">*</strong></label><br>
                                <input type="text" name="pontos_pessoais_update" value="{{ old('pontos_pessoais_update', $dados->pontos_pessoais_update) }}" class="form-control" placeholder="Quantidade de GMilhas pessoais">
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Quantidade de GMilhas de equipe <strong class="text-red">*</strong></label><br>
                                <input type="text" name="pontos_equipe_update" value="{{ old('pontos_equipe_update', $dados->pontos_equipe_update) }}" class="form-control" placeholder="Quantidade de GMilhas de equipe">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Quantidade de diretos por título <strong class="text-red">*</strong></label><br>
                            </div>
                            @foreach($titulos as $titulo)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">{{$titulo->name}}</label><br>
                                    <input type="text" name="titulos_update[{{$titulo->id}}]" value="{{ old('titulos_update.'.$titulo->id, $dados->titulos_update[$titulo->id] ?? '')}}" @endisset class="form-control" placeholder="Quantidade de diretos">
                                </div>
                            @endforeach
                        </div><!-- /.box-body -->
                        <input type="hidden" name="_method" value="PUT">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('titulo.index') }}" class="btn btn-default pull-right">Voltar</a>
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
    <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css') }}">
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });

        //Colorpicker
        $('.colorpicker').colorpicker();
    </script>
@endsection
