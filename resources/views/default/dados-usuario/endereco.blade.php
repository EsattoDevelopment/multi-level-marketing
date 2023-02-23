@extends('default.layout.main')

@section('content')

    <section class="content-header">
        <h1>
            Endereço
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Endereço</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="notifications top-right"></div>
        @include('default.errors.errors')
        <div class="row">
            <div>
                <form role="form"
                      @if($usuario->editar_endereco)
                      action="{{ route('dados-usuario.endereco.update') }}" method="post"
                      @endif
                      enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <input type="hidden" name="user_id" value="{{ $usuario->id }}">
                    <input type="hidden" name="endereco[user_id]" value="{{ $usuario->id }}">
                    <input type="hidden" name="_method" value="PUT">

                    @if($sistema->endereco)
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Informe seu endereço de correspondência</h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <div class="box-body">
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputEmail1">CEP @if(!$hasPedido) <strong class="text-red">*</strong>@endif</label>
                                        <input type="text" {{ $sistema->endereco_obrigatoria ? 'required' : '' }} name="endereco[cep]"
                                               value="{{ old('endereco.cep', isset($endereco->cep) ? $endereco->cep : '') }}"
                                               class="form-control" placeholder="CEP" {{ $usuario->editar_endereco ? '' : 'disabled="disabled"' }}>
                                    </div>
                                    <div class="form-group col-md-7">
                                        <label for="exampleInputEmail1">Endereço @if(!$hasPedido) <strong class="text-red">*</strong>@endif</label>
                                        <input type="text" name="endereco[logradouro]"
                                               value="{{ old('endereco.logradouro', isset($endereco->logradouro) ? $endereco->logradouro : '') }}"
                                               class="form-control" placeholder="Endereço" {{ $usuario->editar_endereco ? '' : 'disabled="disabled"' }}>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="exampleInputPassword1">Numero @if(!$hasPedido) <strong class="text-red">*</strong>@endif</label>
                                        <input type="text" name="endereco[numero]"
                                               value="{{ old('endereco.numero', isset($endereco->numero) ? $endereco->numero : '') }}"
                                               class="form-control" placeholder="Numero" {{ $usuario->editar_endereco ? '' : 'disabled="disabled"' }}>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputPassword1">Bairro @if(!$hasPedido) <strong class="text-red">*</strong>@endif</label>
                                        <input type="text" name="endereco[bairro]"
                                               value="{{ old('endereco.bairro', isset($endereco->bairro) ? $endereco->bairro : '') }}"
                                               class="form-control" placeholder="Bairro" {{ $usuario->editar_endereco ? '' : 'disabled="disabled"' }}>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputPassword1">Cidade @if(!$hasPedido) <strong class="text-red">*</strong>@endif</label>
                                        <input type="text" name="endereco[cidade]"
                                               value="{{ old('endereco.cidade', isset($endereco->cidade) ? $endereco->cidade : '') }}"
                                               class="form-control" placeholder="Cidade" {{ $usuario->editar_endereco ? '' : 'disabled="disabled"' }}>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputPassword1">Estado @if(!$hasPedido) <strong class="text-red">*</strong>@endif</label>
                                        <input type="text" name="endereco[estado]"
                                               value="{{ old('endereco.estado', isset($endereco->estado) ? $endereco->estado : '') }}"
                                               class="form-control" placeholder="Estado" {{ $usuario->editar_endereco ? '' : 'disabled="disabled"' }}>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="exampleInputPassword1">Complemento</label>
                                        <input type="text" name="endereco[complemento]"
                                               value="{{ old('endereco.complemento', isset($endereco->complemento) ? $endereco->complemento : '') }}"
                                               class="form-control" placeholder="Complemento" {{ $usuario->editar_endereco ? '' : 'disabled="disabled"' }}>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                    @endif

                    @if($usuario->editar_endereco)
                        <div class="col-xs-12">
                            <div class="box-footer">
                                <button type="submit" class="btn btn-success btn-block btn-lg pull-left">Salvar</button>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <!-- InputMask -->
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.min.js?v=50') }}"></script>
    <script type="text/javascript">
        $(function () {
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

            $("input[name='endereco[cep]']").inputmask({
                mask: '99999-999',
                showTooltip: true,
                showMaskOnHover: true,
                clearIncomplete: true
            });
        })
    </script>
@endsection
