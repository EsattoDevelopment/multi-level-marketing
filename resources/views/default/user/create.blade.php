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
                    <form role="form" action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
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
                                        <div class="form-group col-xs-12">
                                            <label for="status">Ativo</label> <br>
                                            <div class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-primary @if(!old('status')) active @endif {{ old('status') == 1 ? 'active' : ''  }}">
                                                    <input type="radio" value="1" @if(!old('status')) checked
                                                           @endif {{ old('status') == 1 ? 'checked' : ''  }} name="status"
                                                           autocomplete="off">Sim
                                                </label>
                                                <label class="btn btn-primary {{ old('status') === 0 ? 'active' : ''  }}">
                                                    <input type="radio" value="0"
                                                           {{ old('status') === 0 ? 'checked' : ''  }} name="status" autocomplete="off">Não
                                                </label>
                                            </div>
                                        </div>

                                        @if($sistema->sistema_saude)
                                            <div class="form-group col-xs-12">
                                                <label for="status">Tipo de usuário</label> <br>
                                                <div class="btn-group" data-toggle="buttons">
                                                    <label class="btn btn-info @if(!old('tipo')) active @endif {{ old('tipo') == 1 ? 'active' : ''  }}">
                                                        <input type="radio" value="1" @if(!old('tipo')) checked
                                                               @endif {{ old('tipo') == 1 ? 'checked' : ''  }} name="tipo"
                                                               autocomplete="off">Comum
                                                    </label>
                                                    <label class="btn btn-info {{ old('tipo') == 2 ? 'active' : ''  }}">
                                                        <input type="radio" value="2"
                                                               {{ old('tipo') == 2 ? 'checked' : ''  }} name="tipo" autocomplete="off">Empresa
                                                    </label>
                                                    <label class="btn btn-info {{ old('tipo') == 3 ? 'active' : ''  }}">
                                                        <input type="radio" value="3"
                                                               {{ old('tipo') == 3 ? 'checked' : ''  }} name="tipo" autocomplete="off">Clinica
                                                    </label>
                                                    <label class="btn btn-info {{ old('tipo') == 4 ? 'active' : ''  }}">
                                                        <input type="radio" value="4"
                                                               {{ old('tipo') == 4 ? 'checked' : ''  }} name="tipo" autocomplete="off">Call Center
                                                    </label>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label>Indicador</label>
                                            <select class="form-control" id="indicador" name="indicador_id">
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Titulo</label>
                                            <select class="form-control select2" name="titulo_id"
                                                    data-placeholder="Selecione um titulo" style="width: 100%;">
                                                @foreach($titulos as $titulo)
                                                    <option @if(old('titulo_id') == $titulo->id) selected @endif value="{{ $titulo->id }}">{{ $titulo->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        {{--<v-indicador @change="onChange($event.target.value)"></v-indicador>--}}
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Nome</label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                   class="form-control"
                                                   placeholder="Nome">
                                        </div>

                                        @if($sistema->sistema_saude)
                                            <div class="form-group">
                                                <label>Empresa</label>
                                                <select class="form-control" id="empresa" name="empresa_id">
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Clinica</label>
                                                <select class="form-control" id="clinica_id" name="clinica_id">
                                                </select>
                                            </div>                                        <div class="form-group">
                                                <label>Empresa</label>
                                                <select class="form-control" id="empresa" name="empresa_id">
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Clinica</label>
                                                <select class="form-control" id="clinica_id" name="clinica_id">
                                                </select>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Empresa</label>
                                            <input type="text" name="empresa" value="{{ old('empresa') }}"
                                                   class="form-control"
                                                   placeholder="Empresa">
                                        </div>

                                        {{--<div class="form-group">
                                            <label for="exampleInputEmail1">Usuário</label>
                                            <input type="text" name="username" value="{{ old('username') }}"
                                                   class="form-control"
                                                   placeholder="Usuário">
                                        </div>--}}

                                        {{--<div class="form-group">
                                            <label for="codigo">Número do contrato</label>
                                            <input type="text" name="codigo" value="{{ old('codigo') }}"
                                                   class="form-control"
                                                   placeholder="Código">
                                        </div>--}}

                                        @if($sistema->campo_cpf)
                                            <div class="form-group">
                                                <label for="">CPF/CNPJ</label>
                                                <input type="text" name="cpf"
                                                       value="{{ old('cpf') }}"
                                                       class="form-control" id="exampleInputEmail1" placeholder="CPF">
                                            </div>
                                        @endif

                                        @if($sistema->campo_rg)
                                            <div class="form-group">
                                                <label for="">RG</label>
                                                <input type="text" name="rg"
                                                       value="{{ old('rg') }}"
                                                       class="form-control" id="exampleInputEmail1" placeholder="RG">
                                            </div>
                                        @endif
                                        {{--<div class="form-group">
                                            <label for="">CNPJ</label>
                                            <input type="text" name="cnpj"
                                                   value="{{ old('cnpj') }}"
                                                   class="form-control" id="exampleInputEmail1" placeholder="CNPJ">
                                        </div>--}}
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Incrição estadual</label>
                                            <input type="text" name="inscricao_estadual"
                                                   value="{{ old('inscricao_estadual') }}" class="form-control"
                                                   placeholder="Incrição estadual">
                                        </div>
                                        @if($sistema->campo_dtnasc)
                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Data de Nascimento</label>
                                                <input type="text" name="data_nasc"
                                                       value="{{ old('data_nasc') }}"
                                                       class="form-control datepicker" id="exampleInputEmail1"
                                                       placeholder="Data de Nascimento">
                                            </div>
                                        @endif
                                        @if($sistema->campo_rg)

                                            <div class="form-group">
                                                <label for="exampleInputEmail1">Profissão</label>
                                                <input type="text" name="profissao"
                                                       value="{{ old('profissao') }}"
                                                       class="form-control" id="exampleInputEmail1" placeholder="Profissão">
                                            </div>
                                            <div class="form-group">
                                                <label>Estado Civil</label> <small class="text-red">somente pessoa fisica</small>
                                                <select class="form-control select2" name="estado_civil"
                                                        data-placeholder="Selecione um estado civil" style="width: 100%;">
                                                    @foreach(config('constants.estado_civil') as $key => $value)
                                                        <option @if(old('estado_civil') == $key) selected
                                                                @endif value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Telefone</label>
                                            <input type="text" name="telefone"
                                                   value="{{ old('telefone') }}"
                                                   class="form-control" id="exampleInputEmail1" placeholder="Telefone">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Celular</label>
                                            <input type="text" name="celular"
                                                   value="{{ old('celular') }}"
                                                   class="form-control" id="exampleInputEmail1" placeholder="Celular">
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Imagem</label>
                                            <input type="file" id="imagem" name="imagem">
                                        </div>

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
                                                           value="{{ old('endereco.cep') }}"
                                                           class="form-control" id="exampleInputEmail1" placeholder="CEP">
                                                </div>
                                                <div class="form-group col-md-7">
                                                    <label for="exampleInputEmail1">Endereço</label>
                                                    <input type="text" name="endereco[logradouro]"
                                                           value="{{ old('endereco.logradouro') }}"
                                                           class="form-control" id="exampleInputEmail1"
                                                           placeholder="Endereço">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label for="exampleInputPassword1">Numero</label>
                                                    <input type="text" name="endereco[numero]"
                                                           value="{{ old('endereco.numero') }}"
                                                           class="form-control" id="exampleInputPassword1"
                                                           placeholder="Numero">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="exampleInputPassword1">Bairro</label>
                                                    <input type="text" name="endereco[bairro]"
                                                           value="{{ old('endereco.bairro') }}"
                                                           class="form-control" id="exampleInputPassword1"
                                                           placeholder="Bairro">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="exampleInputPassword1">Cidade</label>
                                                    <input type="text" name="endereco[cidade]"
                                                           value="{{ old('endereco.cidade') }}"
                                                           class="form-control" id="exampleInputPassword1"
                                                           placeholder="Cidade">
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label for="exampleInputPassword1">Estado</label>
                                                    <input type="text" name="endereco[estado]"
                                                           value="{{ old('endereco.estado') }}"
                                                           class="form-control" id="exampleInputPassword1"
                                                           placeholder="Estado">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="exampleInputPassword1">Complemento</label>
                                                    <input type="text" name="endereco[complemento]"
                                                           value="{{ old('endereco.complemento') }}"
                                                           class="form-control" id="exampleInputPassword1"
                                                           placeholder="Estado">
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
                                                <input type="email" name="email" value="{{ old('email') }}"
                                                       class="form-control" placeholder="E-mail">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Senha</label>
                                                <input type="password" required name="password" class="form-control"
                                                       id="exampleInputPassword1" placeholder="Senha">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword2">Confirmar Senha</label>
                                                <input type="password" required name="password_confirmation" class="form-control"
                                                       id="exampleInputPassword2" placeholder="Corfirmar Senha">
                                            </div>
                                            <div class="form-group">
                                                <label>Regras</label>
                                                <select class="form-control select2" required multiple="multiple" name="roles[]"
                                                        data-placeholder="Selecione as Regras" style="width: 100%;">
                                                    @foreach($roles as $role)
                                                        <option {{ in_array($role->id, old('roles', [])) }} value="{{ $role->id }}">{{ $role->display_name }}</option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <!-- /.box-body -->
                                        </div>
                                        <!-- /.box -->
                                    </div><!-- /.box-body -->

                                @if($sistema->sistema_saude)
                                    <!-- procedimentos -->
                                        <div id="procedimentos" class="col-md-12 @if(!old('tipo') || old('tipo') <> 3) hidden @endif">
                                            <!-- general form elements -->
                                            <div class="box box-success">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">Procedimentos</h3><br>
                                                    <small>Selecione os procedimentos que a clinica atende</small>
                                                </div>
                                                <!-- /.box-header -->
                                                <!-- form start -->
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <select class="form-control select2" multiple="multiple" id="clinica_procedimentos" name="clinica_procedimentos[]" @if(!old('tipo') || old('tipo') <> 3) disabled @endif></select>
                                                    </div>
                                                </div>
                                                <!-- /.box-body -->
                                            </div>
                                            <!-- /.box -->
                                        </div>
                                @endif

                                    <div class="box-footer">
                                        <button type="submit" class="btn btn-primary">Salvar</button>
                                        <a class="btn btn-default pull-right"
                                           href="{{ route('user.index') }}">Voltar</a>
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

            $('input[name="tipo"]').change(function(){
                if ($(this).val() == 3) {
                    $("#procedimentos").removeClass('hidden').find('select').removeAttr('disabled');
                    $("#procedimentos-multi").prop("disabled", false);
                }else {
                    $("#procedimentos").addClass('hidden').find('select').attr('disabled', true);
                    $("#procedimentos-multi").prop("disabled", true);
                }

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
                                    text: '#' + item.id + ' - ' + item.name,
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
