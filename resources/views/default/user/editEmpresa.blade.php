@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edição de Colaboradores</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    {{--<form role="form" action="{{ route('user.update.empresa', $dados->id) }}" method="post" enctype="multipart/form-data">--}}
                        {!! csrf_field() !!}
                        <div class="box-body">
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="box box-info">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Dados do Colaboradores</h3>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <div class="box-body">
                                        {{--<v-indicador patrocinio="{{ $dados->indicador_id }}"></v-indicador>--}}
                                        <div class="form-group col-xs-12">
                                            <label for="status">Ativo</label> <br>
                                            <div class="btn-group" data-toggle="buttons">
                                                <label class="btn btn-primary disabled {{ old('status') == 1 ? 'active' : $dados->status == 1 ? 'active' : '' }}">
                                                    <input  type="radio" value="1" {{ old('status') == 1 ? 'checked' : $dados->status == 1 ? 'checked' : ''  }} name="status"
                                                           autocomplete="off">Sim
                                                </label>
                                                <label class="btn btn-primary disabled {{ old('status') === 0 ? 'active' : $dados->status === 0 ? 'active' : '' }}">
                                                    <input disabled type="radio" value="0" {{ old('status') === 0 ? 'checked' : $dados->status === 0 ? 'checked' : ''  }} name="status" autocomplete="off">Não
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Nome</label>
                                            <input disabled type="text" name="name" value="{{ old('name', $dados->name) }}"
                                                   class="form-control" placeholder="Nome">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Usuário</label>
                                            <input disabled type="text" name="username"
                                                   value="{{ old('username', $dados->username) }}" class="form-control"
                                                   placeholder="Usuário">
                                        </div>
                                        <div class="form-group">
                                            <label for="codigo">Código</label>
                                            <input disabled type="text" name="codigo" value="{{ old('codigo', $dados->codigo) }}"
                                                   class="form-control"
                                                   placeholder="Código">
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label for="exampleInputEmail1">CPF</label>
                                            <input disabled type="text" name="cpf" value="{{ old('cpf', $dados->cpf) }}"
                                                   class="form-control" placeholder="CPF">
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label for="exampleInputEmail1">RG</label>
                                            <input disabled type="text" name="rg" value="{{ old('rg', $dados->rg) }}"
                                                   class="form-control" placeholder="RG">
                                        </div>
                                        <div class="form-group has-feedback">
                                            <label for="exampleInputEmail1">Data de nascimento</label>
                                            <input disabled type="text" required name="data_nasc"
                                                   value="{{ old('data_nasc', $dados->data_nasc) }}"
                                                   class="form-control datepicker" placeholder="Data de nascimento">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Profissão</label>
                                            <input disabled type="text" name="profissao"
                                                   value="{{ old('profissao', $dados->profissao) }}"
                                                   class="form-control" id="exampleInputEmail1" placeholder="Profissão">
                                        </div>
                                        <div class="form-group">
                                            <label>Estado Civil</label> <small class="text-red">somente pessoa fisica</small>
                                            <select disabled class="form-control select2" name="estado_civil"
                                                    data-placeholder="Selecione um estado civil" style="width: 100%;">
                                                @foreach(config('constants.estado_civil') as $key => $value)
                                                    <option @if(old('estado_civil', $dados->estado_civil) == $key) selected
                                                            @endif value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Telefone</label>
                                            <input disabled type="text" name="telefone"
                                                   value="{{ old('telefone', $dados->telefone) }}"
                                                   class="form-control" id="exampleInputEmail1" placeholder="Telefone">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Celular</label>
                                            <input disabled type="text" name="celular"
                                                   value="{{ old('celular', $dados->celular) }}"
                                                   class="form-control" id="exampleInputEmail1" placeholder="Celular">
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Imagem</label>
                                            <input disabled type="file" id="imagem" name="imagem">
                                        </div>
                                        @if($dados->image)
                                            <div class="product-img col-xs-12">
                                                <img src="{{ route('imagecache',['small', 'user/'.$dados->id.'/'.$dados->image] ) }}" alt="Product Image">
                                            </div>
                                        @endif
                                    </div>
                                </div>

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
                                                <input disabled type="text" required name="endereco[cep]"
                                                       value="{{ old('endereco.cep', $endereco->cep ? $endereco->cep : '') }}"
                                                       class="form-control" id="exampleInputEmail1" placeholder="CEP">
                                            </div>
                                            <div class="form-group col-md-7">
                                                <label for="exampleInputEmail1">Endereço</label>
                                                <input disabled type="text" name="endereco[logradouro]"
                                                       value="{{ old('endereco.logradouro', $endereco->logradouro) }}"
                                                       class="form-control" id="exampleInputEmail1"
                                                       placeholder="Endereço">
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label for="exampleInputPassword1">Numero</label>
                                                <input disabled type="text" name="endereco[numero]"
                                                       value="{{ old('endereco.numero', $endereco->numero) }}"
                                                       class="form-control" id="exampleInputPassword1"
                                                       placeholder="Numero">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="exampleInputPassword1">Bairro</label>
                                                <input disabled type="text" name="endereco[bairro]"
                                                       value="{{ old('endereco.bairro', $endereco->bairro) }}"
                                                       class="form-control" id="exampleInputPassword1"
                                                       placeholder="Bairro">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="exampleInputPassword1">Cidade</label>
                                                <input disabled type="text" name="endereco[cidade]"
                                                       value="{{ old('endereco.cidade', $endereco->cidade) }}"
                                                       class="form-control" id="exampleInputPassword1"
                                                       placeholder="Cidade">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label for="exampleInputPassword1">Estado</label>
                                                <input disabled type="text" name="endereco[estado]"
                                                       value="{{ old('endereco.estado', $endereco->estado) }}"
                                                       class="form-control" id="exampleInputPassword1"
                                                       placeholder="Estado">
                                            </div>
                                        </div>
                                        <!-- /.box-body -->
                                    </div>
                                    <!-- /.box -->
                                </div>

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
                                                <input disabled type="email" name="email"
                                                       value="{{ old('email', $dados->email) }}"
                                                       class="form-control" placeholder="E-mail">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword1">Senha</label>
                                                <input disabled type="password" name="password" class="form-control"
                                                       id="exampleInputPassword1" placeholder="Senha">
                                            </div>
                                            <div class="form-group">
                                                <label for="exampleInputPassword2">Confirmar Senha</label>
                                                <input disabled type="password" name="password_confirmation" class="form-control"
                                                       id="exampleInputPassword2" placeholder="Corfirmar Senha">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <input disabled type="hidden" name="_method" value="PUT">
                            <div class="box-footer">
                               {{-- <button type="submit" class="btn btn-primary">Salvar</button>--}}
                                <a class="btn btn-default pull-right" href="{{ route('user.empresa') }}">Voltar</a>
                            </div>
                    {{--</form>--}}
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css')}}">
@endsection

@section('script')

    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{ asset('plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js')}}"></script>

    <!-- InputMask -->
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.extensions.js')}}"></script>

    <script>
        $(function () {

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
                mask: '999.999.999-99',
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