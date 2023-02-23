@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('pacotes.update', $dados->id) }}" method="post">
                {!! csrf_field() !!}
                <!-- general form elements -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cadastro de plano</h3>
                        </div><!-- /.box-header -->
                        <!-- form start -->

                        <div class="box-body">

                            <div class="form-group col-xs-12">
                                <label for="language">Ativo</label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary  {{ old('status', $dados->status) == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1"  {{ old('status', $dados->status) == 1 ? 'checked' : ''  }} name="status" id="pt" autocomplete="off">Sim
                                    </label>
                                    <label class="btn btn-primary {{ old('status', $dados->status) === 0 ? 'active' : ''  }}">
                                        <input type="radio" value="0" {{ old('status', $dados->status) === 0 ? 'checked' : ''  }} name="status" id="en" autocomplete="off">Não
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="language">Internacional</label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary  {{ old('internacional', $dados->internacional) == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1"  {{ old('internacional', $dados->internacional) == 1 ? 'checked' : ''  }} name="internacional" id="pt" autocomplete="off">Sim
                                    </label>
                                    <label class="btn btn-primary {{ old('internacional', $dados->internacional) === 0 ? 'active' : ''  }}">
                                        <input type="radio" value="0" {{ old('internacional', $dados->internacional) === 0 ? 'checked' : ''  }} name="internacional" id="en" autocomplete="off">Não
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-xs-12 ">
                                <label for="language">Promoção</label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary  {{ old('promocao', $dados->promocao) == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1" {{ old('promocao', $dados->promocao) == 1 ? 'checked' : ''  }} name="promocao" id="pt" autocomplete="off">Sim
                                    </label>
                                    <label class="btn btn-primary @if(!old('promocao')) active @endif {{ old('promocao', $dados->promocao) === 0 ? 'active' : ''  }}">
                                        <input type="radio" value="0" @if(!old('promocao')) checked @endif {{ old('promocao', $dados->promocao) === 0 ? 'checked' : ''  }} name="promocao" id="en" autocomplete="off">Não
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-xs-12 ">
                                <label for="language">Escolha de local aberta</label><br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary  {{ old('local_selecionavel', $dados->local_selecionavel) == 1 ? 'active' : ''  }}">
                                        <input type="radio" value="1" {{ old('local_selecionavel', $dados->local_selecionavel) == 1 ? 'checked' : ''  }} name="local_selecionavel" id="pt" autocomplete="off">Sim
                                    </label>
                                    <label class="btn btn-primary @if(!old('local_selecionavel', $dados->local_selecionavel)) active @endif {{ old('local_selecionavel', $dados->local_selecionavel) === 0 ? 'active' : ''  }}">
                                        <input type="radio" value="0" @if(!old('local_selecionavel', $dados->local_selecionavel)) checked @endif {{ old('local_selecionavel', $dados->local_selecionavel) === 0 ? 'checked' : ''  }} name="local_selecionavel" id="en" autocomplete="off">Não
                                    </label>
                                </div>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>Estado</label>
                                <select class="form-control select2" name="estado" data-placeholder="Selecione um estado" style="width: 100%;">
                                    @foreach($estados as $uf)
                                        <option @if(old('estado', $dados->getRelation('cidade')->estado) == $uf->id) selected @endif value="{{ $uf->id }}">{{ $uf->nome }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>Cidade</label>
                                <select class="form-control select2" name="cidade_id" id="select-cidades" data-placeholder="Selecione um" style="width: 100%;">
                                    @if(old('estado'))
                                        @foreach(\App\Models\Cidade::where('estado', old('estado'))->get() as $city)
                                            <option @if(old('cidade_id') == $city->id) selected @endif value="{{ $city->id }}">{{ $city->nome }}</option>
                                        @endforeach
                                    @elseif($dados->cidade_id)
                                        @foreach(\App\Models\Cidade::where('estado', $dados->getRelation('cidade')->estado)->get() as $city)
                                            <option @if(old('cidade_id', $dados->cidade_id) == $city->id) selected @endif value="{{ $city->id }}">{{ $city->nome }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Chamada</label>
                                <textarea class="form-control" id="chamada" name="chamada" rows="3" maxlength="200" placeholder="Chamada...">{{ old('chamada', $dados->chamada) }}</textarea>
                                <div id="textarea_feedback"></div>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="descricao">Descrição</label>
                                <textarea class="textarea" name="descricao" placeholder="Descrição..." style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{ old('descricao', $dados->descricao) }}</textarea>
                            </div>

                            {{--<div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Valor em GMilhas</label>
                                <input type="text" name="valor_milhas" value="{{ old('valor_milhas', $dados->valor_milhas) }}" class="form-control" placeholder="Valor em GMilhas">
                            </div>--}}
{{--{{ dd($dados->getRelation('acomodacao')->where('id', 5)->first()) }}--}}

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Quantidade de dias fixo</label><br>
                                <small class="text-red"><b>Ao colocar dias fixo os valores das acomodações devem ser para o total de dias</b></small>
                                <br>
                                <small><i>Deixe em -1 para desativado</i></small>
                                <input type="text" name="dias" value="{{ old('dias', $dados->dias) }}" class="form-control" placeholder="Quantidade de dias fixo">
                            </div>


                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Acomodações disponíveis</label><br>
                                @foreach($tipo_acomodacao as $ta)
                                    <div class="col-xs-12 col-sm-6 col-md-3 text-center acomodacoes">
                                        <input type="checkbox" @if(old("acomodacao.{$ta->id}")) checked @else @if($dados->getRelation('acomodacao')->where('id', $ta->id)->first()) checked @endif @endif id="acomodacao_id_{{ $ta->id }}" name="acomodacao[{{ $ta->id }}][select]" value="{{ $ta->id }}"><br>
                                        {{ $ta->name }} <br>
                                        <input type="text" @if(!old("acomodacao.{$ta->id}", $dados->getRelation('acomodacao')->where('id', $ta->id)->first())) disabled="disabled" @endif id="valor_acomodacao_id_{{ $ta->id }}" name="acomodacao[{{ $ta->id }}][valor]" value="{{ old("acomodacao.{$ta->id}.valor", $dados->getRelation('acomodacao')->where('id', $ta->id)->first() ? $dados->getRelation('acomodacao')->where('id', $ta->id)->first()->getRelation('pivot')->valor : '') }}" class="form-control" placeholder="Valor em milhas">
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Quantidade de vagas</label><br>
                                <small><i>Deixe em -1 para ilimitado</i></small>
                                <input type="text" name="quantidade_vagas" value="{{ old('quantidade_vagas', $dados->quantidade_vagas) }}" class="form-control" placeholder="Quantidade de vagas">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Video</label><br>
                                <small><i>Código do youtube</i></small><br>
                                <input type="text" name="video" value="{{ old('video', $dados->video) }}" class="form-control" placeholder="Video">
                            </div>

                            <input type="hidden" name="tipo_pacote_id" value="{{ $dados->tipo_pacote_id }}">

                        </div><!-- /.box-body -->

                        <div class="box-footer">
                            <input type="hidden" name="_method" value="PUT">
                            <button type="submit" class="btn btn-primary">Salvar</button>

                            @if($dados->galeria_id)
                                <a href="{{ route('pacotes.galeria', $dados->id) }}" class="btn btn-success">Imagens <span class="badge">{{ $dados->galeria->imagensCount() }}</span></a>
                            @else
                                <a href="{{ route('pacotes.galeria.create', $dados->id) }}" class="btn btn-warning">Criar Galeria</a>
                            @endif

                            <a href="{{ $urlVolta }}" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div><!-- /.box -->

                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection
@section('script')
    <script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
    {{--<script src="/js/main.js"></script>--}}
    <script>
        $(function(){
            CKEDITOR.replaceAll('textarea');

            $('.acomodacoes input[type="checkbox"]').click(function(){
                if($(this).is(':checked')){
                    id = $(this).attr('id');
                    $('#valor_'+id).removeAttr('disabled');
                }else{
                    id = $(this).attr('id');
                    $('#valor_'+id).attr('disabled', 'disabled');
                }

            });

            function getCidades(estado){
                get = $.get('/pacotes/cidades/'+estado);

                get.done(function(data){

                    $('select[name="cidade_id"]').html('');

                    $.each(data, function(i, item){
                        $('select[name="cidade_id"]').append("<option value='" + item.id + "'>" + item.nome + "</option>");
                    });

                },'json');
            }

            $('select[name="estado"]').change(function(){
                valor = $(this).val();
                getCidades(valor);
            });

            @if(old('estado', false))
                getCidades(old('estado'));
            @endif

        });
    </script>
@endsection