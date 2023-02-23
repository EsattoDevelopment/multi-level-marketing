@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cadastro Usúarios</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{ route('titulo.update', $dados->id) }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Nome</label>
                                <input type="text" name="name" value="{{ old('name', $dados->name) }}" class="form-control" placeholder="Nome">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Acúmulo pessoal de milhas</label>
                                <input type="text" name="acumulo_pessoal_milhas" value="{{ old('acumulo_pessoal_milhas', $dados->acumulo_pessoal_milhas) }}" class="form-control"  placeholder="Acúmulo pessoal de milhas">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Minimo diretos aprovados</label>
                                <input type="text" name="min_diretos_aprovados" value="{{ old('min_diretos_aprovados', $dados->min_diretos_aprovados) }}" class="form-control"  placeholder="Minimo diretos aprovados">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Minimo pontuação perna menor</label>
                                <input type="text" name="min_pontuacao_perna_menor" value="{{ old('min_pontuacao_perna_menor', $dados->min_pontuacao_perna_menor) }}" class="form-control"  placeholder="Minimo pontuação perna menor">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Milhas para indicador <small>(patrocinador)</small></label>
                                <input type="text" name="milhas_indicador" value="{{ old('milhas_indicador', $dados->milhas_indicador) }}" class="form-control"  placeholder="Milhas para indicador">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Dinheiro para indicador {{ $sistema->moeda }}</label>
                                <input type="text" name="dinheiro_indicador" value="{{ old('dinheiro_indicador', $dados->dinheiro_indicador) }}" class="form-control"  placeholder="Dinheiro para indicador {{ $sistema->moeda }}">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Binário para o indicador</label>
                                <input type="text" name="binario_patrocinado" value="{{ old('binario_patrocinado', $dados->binario_patrocinado) }}" class="form-control"  placeholder="Binário para o indicador">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Percentual do Binário</label>
                                <input type="text" name="percentual_binario" value="{{ old('percentual_binario', $dados->percentual_binario) }}" class="form-control"  placeholder="Percentual do Binário">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Teto pagamento diário do binário</label>
                                <input type="text" name="teto_pagamento_sobre_binario" value="{{ old('teto_pagamento_sobre_binario', $dados->teto_pagamento_sobre_binario) }}" class="form-control"  placeholder="Teto pagamento diário do binário">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Teto financeiro mensal</label>
                                <input type="text" name="teto_mensal_financeiro" value="{{ old('teto_mensal_financeiro', $dados->teto_mensal_financeiro) }}" class="form-control"  placeholder="Teto financeiro mensal">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Bonus sobre os ganhos HVIP dos seus diretos</label>
                                <input type="text" name="bonus_hvip_diretos" value="{{ old('bonus_hvip_diretos', $dados->bonus_hvip_diretos) }}" class="form-control"  placeholder="Bonus sobre os ganhos HVIP dos seus diretos">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Cor do titulo</label>
                                <small><i>Utilize tabela de código <a target="_blank" href="http://erikasarti.net/html/tabela-cores/">hexadecimal</a></i></small></label><br>
                                <input type="text" name="cor" value="{{ old('cor', $dados->cor) }}" class="form-control"  placeholder="Cor do titulo">
                            </div>
                            <div class="form-group col-xs-12">
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
                            <div class="form-group col-xs-12">
                                <label>Titulo Superior</label>
                                <select class="form-control select2"  name="titulo_superior" data-placeholder="Selecione o titulo superior" style="width: 100%;">
                                    <option value="">Selecione um titulo</option>
                                    @foreach($titulos as $titulo)
                                        <option @if(old('titulo_superior', $dados->titulo_superior) == $titulo->id) selected @endif value="{{ $titulo->id }}">{{ $titulo->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><!-- /.box-body -->
                        <input type="hidden" name="_method" value="PUT">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
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
