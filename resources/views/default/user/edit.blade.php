

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
                        <small><strong>Indicador</strong> {{ $dados->indicado }}</small>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{ route('user.update', $dados->id) }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="box-body">
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Dados do usuário</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <div class="box-body">
                                        <v-indicador patrocinio="{{ $dados->indicador_id }}"></v-indicador>
                                        <div class="form-group col-xs-12">
                                            <label for="status">Status</label> <br>
                                            <div class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-primary {{ old('status', $dados->status) == 1 ? 'active' : $dados->status == 1 ? 'active' : '' }}">
                                                    <input type="radio" value="1" {{ old('status', $dados->status) == 1 ? 'checked' : $dados->status == 1 ? 'checked' : ''  }} name="status"
                                                           autocomplete="off">Ativo
                                                </label>
                                                <label class="btn btn-primary {{ old('status', $dados->status) === 0 ? 'active' : $dados->status === 0 ? 'active' : '' }}">
                                                    <input type="radio" value="0" {{ old('status', $dados->status) === 0 ? 'checked' : $dados->status === 0 ? 'checked' : ''  }} name="status" autocomplete="off">Inativo
                                                </label>

                                                @if($sistema->campo_cpf)
                                                    <label class="btn btn-primary {{ old('status') === 3 ? 'active' : $dados->status === 3 ? 'active' : '' }}">
                                                        <input type="radio" value="3" {{ old('status') === 3 ? 'checked' : $dados->status === 3 ? 'checked' : ''  }} name="status" autocomplete="off">Inadimplente
                                                    </label>
                                                    <label class="btn btn-primary {{ old('status') === 4 ? 'active' : $dados->status === 4 ? 'active' : '' }}">
                                                        <input type="radio" value="4" {{ old('status') === 4 ? 'checked' : $dados->status === 4 ? 'checked' : ''  }} name="status" autocomplete="off">Desabilitado
                                                    </label>
                                                @endif

                                            </div>
                                        </div>
                                        {{--<div class="form-group col-xs-12">
                                            <label for="qualificado">Qualificado</label> <br>
                                            <div class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-primary {{ old('qualificado', $dados->qualificado) == 1 ? 'active' : $dados->qualificado == 1 ? 'active' : '' }}">
                                                    <input type="radio" value="1" {{ old('qualificado', $dados->qualificado) == 1 ? 'checked' : $dados->qualificado == 1 ? 'checked' : ''  }} name="qualificado"
                                                           autocomplete="off">Sim
                                                </label>
                                                <label class="btn btn-primary {{ old('qualificado', $dados->qualificado) == 0 ? 'active' : $dados->qualificado == 0 ? 'active' : '' }}">
                                                    <input type="radio" value="0" {{ old('qualificado', $dados->qualificado) == 0 ? 'checked' : $dados->qualificado == 0 ? 'checked' : ''  }} name="qualificado"
                                                           autocomplete="off">Não
                                                </label>
                                            </div>
                                        </div>--}}
                                        {{--<div class="form-group col-xs-12">
                                            <label for="tipo">Tipo de usuário</label> <br>
                                            <div class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-info {{ old('tipo') == 1 ? 'active' : $dados->tipo == 1 ? 'active' : '' }}">
                                                    <input type="radio" value="1" {{ old('tipo') == 1 ? 'checked' : $dados->tipo == 1 ? 'checked' : ''  }} name="tipo"
                                                           autocomplete="off">Comum
                                                </label>
                                                <label class="btn btn-info {{ old('tipo') === 2 ? 'active' : $dados->tipo === 2 ? 'active' : '' }}">
                                                    <input type="radio" value="2" {{ old('tipo') === 2 ? 'checked' : $dados->tipo === 2 ? 'checked' : ''  }} name="tipo" autocomplete="off">Empresa
                                                </label>
                                                <label class="btn btn-info {{ old('tipo') === 3 ? 'active' : $dados->tipo === 3 ? 'active' : '' }}">
                                                    <input type="radio" value="3" {{ old('tipo') === 3 ? 'checked' : $dados->tipo === 3 ? 'checked' : ''  }} name="tipo" autocomplete="off">Clinica
                                                </label>
                                                <label class="btn btn-info {{ old('tipo') === 4 ? 'active' : $dados->tipo === 4 ? 'active' : '' }}">
                                                    <input type="radio" value="4" {{ old('tipo') === 4 ? 'checked' : $dados->tipo === 4 ? 'checked' : ''  }} name="tipo" autocomplete="off">Call Center
                                                </label>
                                            </div>
                                        </div>--}}
                                        <div class="form-group">
                                            <label>Indicador</label>
                                            <select class="form-control" id="indicador" name="indicador_id">
                                                @if($dados->indicador_id) <option value="{{ $dados->indicador_id }}" selected="selected">{{ '#'.$dados->getRelation('indicador')->id .' - '. $dados->getRelation('indicador')->name }}</option> @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Titulo</label>
                                            <select class="form-control select2" name="titulo_id"
                                                    data-placeholder="Selecione um titulo" style="width: 100%;">
                                                @foreach($titulos as $titulo)
                                                    <option @if(old('titulo_id', $dados->titulo_id) == $titulo->id) selected @endif value="{{ $titulo->id }}">{{ $titulo->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Nome</label>
                                            <input type="text" name="name" value="{{ old('name', $dados->name) }}"
                                                   class="form-control" placeholder="Nome">
                                        </div>

                                        @if($sistema->sistema_saude)

                                            <div class="form-group">
                                                <label>Empresa</label>
                                                <select class="form-control" id="empresa" name="empresa_id">
                                                    @if($dados->empresa_id) <option value="{{ $dados->empresa_id }}" selected="selected">{{ '#'.$dados->getRelation('empresa')->id .' - '. $dados->getRelation('empresa')->name }}</option> @endif
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Clinica</label>
                                                <select class="form-control" id="clinica_id" name="clinica_id">
                                                    @if($dados->clinica_id) <option value="{{ $dados->clinica_id }}" selected="selected">{{ '#'.$dados->getRelation('clinica')->id .' - '. $dados->getRelation('clinica')->name }}</option> @endif
                                                </select>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Empresa</label>
                                            <input type="text" name="empresa" value="{{ old('empresa', $dados->empresa) }}"
                                                   class="form-control"
                                                   placeholder="Empresa">
                                        </div>

                                        {{--<div class="form-group">
                                            <label for="exampleInputEmail1">Usuário</label>
                                            <input type="text" name="username"
                                                   value="{{ old('username', $dados->username) }}" class="form-control"
                                                   placeholder="Usuário">
                                        </div>--}}
                                        {{--<div class="form-group">
                                            <label for="codigo">Número do contrato</label>
                                            <input type="text" name="codigo" value="{{ old('codigo', $dados->codigo) }}"
                                                   class="form-control"
                                                   placeholder="Código">
                                        </div>--}}

                                        @if($sistema->campo_cpf)
                                            <div class="form-group has-feedback">
                                                <label for="exampleInputEmail1">CPF</label>
                                                <input type="text" name="cpf" value="{{ old('cpf', $dados->cpf) }}"
                                                       class="form-control" placeholder="CPF">
                                            </div>
                                        @endif

                                        @if($sistema->campo_rg)
                                            <div class="form-group has-feedback">
                                                <label for="exampleInputEmail1">RG</label>
                                                <input type="text" name="rg" value="{{ old('rg', $dados->rg) }}"
                                                       class="form-control" placeholder="RG">
                                            </div>
                                        @endif
                                        {{--<div class="form-group">
                                            <label for="">CNPJ</label>
                                            <input type="text" name="cnpj"
                                                   value="{{ old('cnpj', $dados->cnpj) }}"
                                                   class="form-control" id="exampleInputEmail1" placeholder="CNPJ">
                                        </div>--}}
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Incrição estadual</label>
                                            <input type="text" name="inscricao_estadual"
                                                   value="{{ old('inscricao_estadual', $dados->inscricao_estadual) }}" class="form-control"
                                                   placeholder="Incrição estadual">
                                        </div>
                                        @if($sistema->campo_dtnasc)

                                            <div class="form-group has-feedback">
                                                <label for="exampleInputEmail1">Data de nascimento</label>
                                                <input type="text" required name="data_nasc"
                                                       value="{{ old('data_nasc', $dados->data_nasc) }}"
                                                       class="form-control datepicker" placeholder="Data de nascimento">
                                            </div>
                                        @endif

                                        @if($sistema->campo_rg)
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Profissão</label>
                                                <input type="text" name="profissao"
                                                       value="{{ old('profissao', $dados->profissao) }}"
                                                       class="form-control" id="exampleInputEmail1" placeholder="Profissão">
                                            </div>
                                            <div class="form-group">
                                                <label>Estado Civil</label> <small class="text-red">somente pessoa fisica</small>
                                                <select class="form-control select2" name="estado_civil"
                                                        data-placeholder="Selecione um estado civil" style="width: 100%;">
                                                    @foreach(config('constants.estado_civil') as $key => $value)
                                                        <option @if(old('estado_civil', $dados->estado_civil) == $key) selected
                                                                @endif value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Telefone</label>
                                            <input type="text" name="telefone"
                                                   value="{{ old('telefone', $dados->telefone) }}"
                                                   class="form-control" id="exampleInputEmail1" placeholder="Telefone">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Celular</label>
                                            <input type="text" name="celular"
                                                   value="{{ old('celular', $dados->celular) }}"
                                                   class="form-control" id="exampleInputEmail1" placeholder="Celular">
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Imagem</label>
                                            <input type="file" id="imagem" name="imagem">
                                        </div>
                                        @if($dados->image)
                                            <div class="product-img col-xs-12">
                                                <img src="{{ route('imagecache',['small', 'user/'.$dados->id.'/'.$dados->image] ) }}" alt="Product Image">
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($sistema->endereco)
                                    <div class="col-md-12">
                                        <!-- general form elements -->
                                        <div class="box box-warning">
                                            <div class="box-header with-border">
                                                <h3 class="box-title">Dados de endereço</h3>
                                            </div>
                                            <!-- /.box-header -->
                                            <!-- form start -->
                                            <div class="box-body">
                                                <div class="form-group col-md-3">
                                                    <label for="exampleInputEmail1">CEP</label>
                                                    <input type="text" {{ $sistema->endereco_obrigatorio ? 'required' : '' }} name="endereco[cep]"
                                                           value="{{ old('endereco.cep', isset($endereco->cep) ? $endereco->cep : '') }}"
                                                           class="form-control" id="exampleInputEmail1" placeholder="CEP">
                                                </div>
                                                <div class="form-group col-md-7">
                                                    <label for="exampleInputEmail1">Endereço</label>
                                                    <input type="text" name="endereco[logradouro]"
                                                           value="{{ old('endereco.logradouro', isset($endereco->logradouro) ? $endereco->logradouro : '' ) }}"
                                                           class="form-control" id="exampleInputEmail1"
                                                           placeholder="Endereço">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="exampleInputPassword1">Numero</label>
                                                    <input type="text" name="endereco[numero]"
                                                           value="{{ old('endereco.numero', isset($endereco->numero) ? $endereco->numero : '' ) }}"
                                                           class="form-control" id="exampleInputPassword1"
                                                           placeholder="Numero">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="exampleInputPassword1">Bairro</label>
                                                    <input type="text" name="endereco[bairro]"
                                                           value="{{ old('endereco.bairro', isset($endereco->bairro) ? $endereco->bairro : '' ) }}"
                                                           class="form-control" id="exampleInputPassword1"
                                                           placeholder="Bairro">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputPassword1">Cidade</label>
                                                    <input type="text" name="endereco[cidade]"
                                                           value="{{ old('endereco.cidade', isset($endereco->cidade) ? $endereco->cidade : '' ) }}"
                                                           class="form-control" id="exampleInputPassword1"
                                                           placeholder="Cidade">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="exampleInputPassword1">Estado</label>
                                                    <input type="text" name="endereco[estado]"
                                                           value="{{ old('endereco.estado', isset($endereco->estado) ? $endereco->estado : '' ) }}"
                                                           class="form-control" id="exampleInputPassword1"
                                                           placeholder="Estado">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="exampleInputPassword1">Complemento</label>
                                                    <input type="text" name="endereco[complemento]"
                                                           value="{{ old('endereco.complemento', isset($endereco->complemento) ? $endereco->complemento : '') }}"
                                                           class="form-control" id="exampleInputPassword1"
                                                           placeholder="Complemento">
                                                </div>
                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                        <!-- /.box -->
                                    </div>
                                @endif
                                <div class="col-md-12">
                                    <!-- general form elements -->
                                    <div class="box box-danger">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Dados de acesso</h3><br>
                                            <small>Troque a senha somente se necessário</small>
                                        </div>
                                        <!-- /.box-header -->
                                        <!-- form start -->
                                        <div class="box-body">
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Email</label>
                                                <input type="email" name="email"
                                                       value="{{ old('email', $dados->email) }}"
                                                       class="form-control" placeholder="E-mail">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Senha</label>
                                                <input type="password" name="password" class="form-control"
                                                       id="exampleInputPassword1" placeholder="Senha">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword2">Confirmar Senha</label>
                                                <input type="password" name="password_confirmation" class="form-control"
                                                       id="exampleInputPassword2" placeholder="Corfirmar Senha">
                                            </div>
                                            <div class="form-group">
                                                <label>Regras</label>
                                                {{--{{dd(old('roles'))}}--}}
                                                <select class="form-control select2" multiple="multiple" name="roles[]"
                                                        data-placeholder="Selecione as Regras" style="width: 100%;">
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" @if(old('roles')) {{ in_array($role->id, old('roles')) ? 'selected' : '' }} @else {{ $dados->hasRole($role->name) ? 'selected' : '' }} @endif >{{ $role->display_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($sistema->sistema_saude)

                                    <div  id="procedimentos" class="col-md-12 @if(old('tipo') && old('tipo') <> 3) hidden @elseif($dados->tipo <> 3) hidden @endif"">
                                    <!-- general form elements -->
                                    <div class="box box-success">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Procedimentos</h3><br>
                                            <small>Selecione os procedimentos que a clinica atende</small>
                                        </div>
                                        <!-- /.box-header -->
                                        <!-- form start -->
                                        <div class="box-body">
                                            <select class="form-control select2" multiple="multiple" id="clinica_procedimentos" name="clinica_procedimentos[]">
                                                @foreach($dados->procedimentos as $procedimento)
                                                    <option value="{{ $procedimento->id }}" selected>{{ $procedimento->codigo }} - {{ $procedimento->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                    {{--                                    <div class="overlay">--}}
                                    {{--                                        <i class="fa fa-refresh fa-spin"></i>--}}
                                    {{--                                    </div>--}}
                            </div>
                            @endif


                        </div><!-- /.box-body -->
                        <input type="hidden" name="_method" value="PUT">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a class="btn btn-default pull-right" href="{{ URL::previous() }}">Voltar</a>
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
            //Initialize Select2 Elements
            $(".select2").select2();

            function onProcedimentos(){
                $('#clinica_procedimentos').select2({
                    width: 'resolve',
                    placeholder: 'Escolha um procedimento',
                    language: "pt-BR",
                    minimumInputLength: 1,
                    tags: false,
                    ajax: {
                        delay: 250,
                        url: "{{ route('api.procedimentos.busca') }}",
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
                                        text: '#'+item.codigo + ' - ' + item.name,
                                        id: item.id
                                    }
                                })
                            };
                        },
                        cache: true
                    }
                });
            }

            onProcedimentos();

            $('input[name="tipo"]').change(function(){
                if ($(this).val() == 3) {
                    $("#procedimentos").removeClass('hidden').find('select').removeAttr('disabled');
                    $("#procedimentos-multi").prop("disabled", false);
                }else {
                    $("#procedimentos").addClass('hidden').find('select').attr('disabled', true);
                    $("#procedimentos-multi").prop("disabled", true);
                }
                onProcedimentos();
            });

            $("#clinica_id").select2({
                placeholder: 'Escolha uma clinica de atendimento',
                language: "pt-BR",
                minimumInputLength: 2,
                tags: false,
                ajax: {
                    delay: 250,
                    url: "{{ route('api.clinica.busca') }}",
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

            $("#indicador").select2({
                placeholder: 'Escolha um patrocinador',
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
                                    text: '#'+item.id + ' - ' + item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });

            $("#empresa").select2({
                placeholder: 'Escolha uma empresa',
                minimumInputLength: 2,
                language: "pt-BR",
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

            $("input[name='data_nasc']").inputmask({
                mask: '99/99/9999',
                showTooltip: true,
                showMaskOnHover: true
            });

            $.fn.datepicker.defaults.language = 'pt-BR';

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });

            $("input[name='endereco[cep]']").focusout(function () {
                cep = $(this).val();
                if (cep.length >= 8) {
                    get = $.get('{{route('cep')}}', {cep: cep});

                    get.done(function (data) {
                        $("input[name='endereco[logradouro]']").val(data.logradouro);
                        $("input[name='endereco[bairro]']").val(data.bairro);
                        $("input[name='endereco[cidade]']").val(data.cidade);
                        $("input[name='endereco[estado]']").val(data.uf);
                    }, 'json');
                }
            });

            $("input[name='telefone']").inputmask({
                mask: ['(99) 9999-9999', '(99) 99999-9999'],
                showTooltip: true,
                showMaskOnHover: true
            });


            $("input[name='celular']").inputmask({
                mask: ['(99) 9999-9999', '(99) 99999-9999'],
                showTooltip: true,
                showMaskOnHover: true
            });

            $("input[name='endereco[cep]']").inputmask({
                mask: '99999-999',
                showTooltip: true,
                showMaskOnHover: true
            });

            $("input[name='cpf']").inputmask({
                mask: ['999.999.999-99', '99.999.999/9999-99'],
                showTooltip: true,
                showMaskOnHover: true
            });

            $("input[name='cnpj']").inputmask({
                mask: '99.999.999/9999-99',
                showTooltip: true,
                showMaskOnHover: true
            });

        });
    </script>
@endsection
