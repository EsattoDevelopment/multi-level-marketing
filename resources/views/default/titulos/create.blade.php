@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cadastro Título</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{ route('titulo.store') }}" method="post">
                        {!! csrf_field() !!}
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="status"><span>Habilita rede</span></label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ old('habilita_rede') == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1" {{ old('habilita_rede') == 1 ? 'checked' : ''  }} name="habilita_rede" autocomplete="off"><span>Sim</span>
                                    </label>
                                    <label class="btn btn-primary {{ old('habilita_rede', 0) == 0 ? 'active' : ''  }}">
                                        <input type="radio" value="0" {{ old('habilita_rede', 0) == 0 ? 'checked' : ''  }} name="habilita_rede" autocomplete="off">Não
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Nome <strong class="text-red">*</strong></label><br>
                                <small><i>Nome exibido</i></small>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Nome">
                            </div>

                            @if($sistema->matriz_fechada || $sistema->matriz_unilevel)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Minimo diretos ativos (Matriz) <strong class="text-red">*</strong></label><br>
                                    <small><i>Minimo de diretos ativos para subir para esse titulo</i></small>
                                    <input type="text" name="min_diretos_aprovados_matriz" value="{{ old('min_diretos_aprovados_matriz') }}" class="form-control"  placeholder="Minimo diretos ativos (Matriz)">
                                </div>
                            @endif

                            @if($sistema->rede_binaria)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Minimo diretos ativos (Binario) <strong class="text-red">*</strong></label><br>
                                    <small><i>Minimo de diretos ativos na perna menor para subir para esse titulo</i></small>
                                    <input type="text" name="min_diretos_aprovados_binario_perna" value="{{ old('min_diretos_aprovados_binario_perna') }}" class="form-control"  placeholder="Minimo diretos ativos (Binario)">
                                </div>

                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Minimo pontuação (Binário) <strong class="text-red">*</strong></label><br>
                                    <small><i>Pontuação minima na perna menor para subir para esse titulo</i></small>
                                    <input type="text" name="min_pontuacao_perna_menor" value="{{ old('min_pontuacao_perna_menor') }}" class="form-control"  placeholder="Minimo pontuação">
                                </div>
                            @endif

                            @if($sistema->sistema_viagem)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Acúmulo pessoal de milhas</label>
                                    <input type="text" name="acumulo_pessoal_milhas" value="{{ old('acumulo_pessoal_milhas') }}" class="form-control"  placeholder="Acúmulo pessoal de milhas">
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Milhas para indicador <small>(patrocinador)</small></label>
                                    <input type="text" name="milhas_indicador" value="{{ old('milhas_indicador') }}" class="form-control"  placeholder="Milhas para indicador">
                                </div>
                            @endif

                            @if($sistema->tipo_bonus_indicador == 1)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Bonus para patrocinador  <span class="text-red">*</span></label><br>
                                    <small><i>Bonus para patrocinador quando direto subir de titulo</i></small>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                        <input type="text" name="bonus_indicador" value="{{ old('bonus_indicador') }}" class="form-control"  placeholder="Bonus para patrocinador">
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

                            @if($sistema->rede_binaria)
                                {{--<div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Binário para o patrocinador</label>
                                    <input type="text" name="binario_patrocinado" value="{{ old('binario_patrocinado') }}" class="form-control"  placeholder="Binário para o indicador">
                                </div>--}}
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Percentual dos pontos <strong class="text-red">*</strong></label>
                                    <input type="text" name="percentual_binario" value="{{ old('percentual_binario') }}" class="form-control"  placeholder="Percentual dos pontos">
                                </div>
                            @endif

                            @if($sistema->rede_binaria)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Teto pagamento diário do binário <span class="text-red">*</span></label>
                                    <input type="text" name="teto_pagamento_sobre_binario" value="{{ old('teto_pagamento_sobre_binario') }}" class="form-control"  placeholder="Teto pagamento diário do binário">
                                </div>
                            @endif

                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Teto financeiro mensal <strong class="text-red">*</strong></label>
                                <input type="text" name="teto_mensal_financeiro" value="{{ old('teto_mensal_financeiro') }}" class="form-control"  placeholder="Teto financeiro mensal">
                            </div>

                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Valor equiparação</label>
                                <input type="text" name="equiparacao_percentual" value="{{ old('equiparacao_percentual') }}" class="form-control"  placeholder="Valor equiparação">
                            </div>

                            @if($sistema->sistema_viagem)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="exampleInputEmail1">Bonus sobre os ganhos HVIP dos seus diretos</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{ $sistema->moeda }}</span>
                                        <input type="text" name="bonus_hvip_diretos" value="{{ old('bonus_hvip_diretos') }}" class="form-control"  placeholder="Bonus sobre os ganhos HVIP dos seus diretos">
                                    </div>
                                </div>
                            @endif

                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Cor do titulo <strong class="text-red">*</strong></label>
                                <small><i>Utilize tabela de código <a target="_blank" href="http://erikasarti.net/html/tabela-cores/">hexadecimal</a></i></small></label><br>
                                <input type="text" name="cor" value="{{ old('cor') }}" class="form-control"  placeholder="Cor do titulo">
                            </div>

                            @if($sistema->update_titulo)
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label>Titulo Superior</label>
                                    <select class="form-control select2"  name="titulo_superior" data-placeholder="Selecione o titulo superior" style="width: 100%;">
                                        <option value="0">Nenhum</option>
                                        @foreach($titulos as $titulo)
                                            <option @if(old('titulo_superior') == $titulo->id) selected @endif value="{{ $titulo->id }}">{{ $titulo->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-xs-12 col-sm-6">
                                    <label for="language">Titulo Inicial? <br>
                                        <small><i>Este titulo será atribuido a todos os ingressantes</i></small></label><br>
                                    <div class="btn-group" data-toggle="buttons">
                                        <label class="btn btn-primary  {{ old('titulo_inicial', 1) == 1 ? 'active' : ''  }}">
                                            <input type="radio" value="1"  {{ old('titulo_inicial', 1) == 1 ? 'checked' : ''  }} name="titulo_inicial" id="pt" autocomplete="off">Sim
                                        </label>
                                        <label class="btn btn-primary {{ old('titulo_inicial', 1) == 0 ? 'active' : ''  }}">
                                            <input type="radio" value="0" {{ old('titulo_inicial', 1) == 0 ? 'checked' : ''  }} name="titulo_inicial" id="en" autocomplete="off">Não
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="language">Recebe pontos? <br>
                                    <small><i>Utilizado geralmente para titulo incial (Titulo de cadastro)</i></small></label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ old('recebe_pontuacao', 1) == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1"  {{ old('recebe_pontuacao', 1) == 1 ? 'checked' : ''  }} name="recebe_pontuacao" id="pt" autocomplete="off">Sim
                                    </label>
                                    <label class="btn btn-primary {{ old('recebe_pontuacao', 1) == 0 ? 'active' : ''  }}">
                                        <input type="radio" value="0" {{ old('recebe_pontuacao', 1) == 0 ? 'checked' : ''  }} name="recebe_pontuacao" id="en" autocomplete="off">Não
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
                                        <option @if(old('configuracao_bonus_adesao_id') == $configbonus->id) selected @endif value="{{ $configbonus->id }}">{{ $configbonus->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label>Bônus de Rentabilidade</label>
                                <select class="form-control select2"  name="configuracao_bonus_rentabilidade_id" data-placeholder="Selecione a configuração do bônus de rentabilidade" style="width: 100%;">
                                    <option value="0">Nenhum</option>
                                    @foreach($configuracaoBonus as $configbonus)
                                        <option @if(old('configuracao_bonus_rentabilidade_id') == $configbonus->id) selected @endif value="{{ $configbonus->id }}">{{ $configbonus->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12">
                                <h4>Parâmetros Para Update Automático de Título</h4>
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Quantidade de pontos pessoais <strong class="text-red">*</strong></label><br>
                                <input type="text" name="pontos_pessoais_update" value="{{ old('pontos_pessoais_update') }}" class="form-control" placeholder="Quantidade de pontos pessoais">
                            </div>
                            <div class="form-group col-xs-12 col-sm-6">
                                <label for="exampleInputEmail1">Quantidade de pontos de equipe <strong class="text-red">*</strong></label><br>
                                <input type="text" name="pontos_equipe_update" value="{{ old('pontos_equipe_update') }}" class="form-control" placeholder="Quantidade de pontos de equipe">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Quantidade de diretos por título <strong class="text-red">*</strong></label><br>
                            </div>
                            @if($titulos)
                                @foreach($titulos as $titulo)
                                    <div class="form-group col-xs-12 col-sm-6">
                                        <label for="exampleInputEmail1">{{$titulo->name}}</label><br>
                                        <input type="text" name="titulos_update[{{$titulo->id}}]" value="{{ old('titulos_update')[$titulo->id] }}" class="form-control" placeholder="Quantidade de diretos">
                                    </div>
                                @endforeach
                            @endif
                        </div><!-- /.box-body -->
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
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>
@endsection
