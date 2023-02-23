@extends('default.layout.main')

@section('content')

    <section class="content-header">
        <h1>
            Cadastrar Conta
            <a href="{{ route('dados-usuario.dados-bancarios') }}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-chevron-left"></i> VOLTAR</a>
        </h1>
    </section>

    <section class="content">
        @include('default.errors.errors')
        <div class="row">
            <form role="form"
                  action="{{ route('dados-usuario.dados-bancarios-store') }}"
                  method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <input type="hidden" name="user_id" value="{{ $usuario->id }}">
                @if($sistema->dados_bancarios)
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-danger">
                            <div class="box-header with-border">
                                <h3 class="box-title">Dados bancários</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body">
                                <div class="form-group col-xs-12">
                                    <label>Banco</label>
                                    <select class="form-control select2" name="d_bancarios[banco_id]"
                                            data-placeholder="Selecione um banco" style="width: 100%;">
                                        @foreach($bancos as $banco)
                                            <option @if(old('d_bancarios.banco_id', isset($dadosBancarios) ? $dadosBancarios->banco_id : '') == $banco->id) selected
                                                    @endif value="{{ $banco->id }}">{{ $banco->codigo }}
                                                - {{ $banco->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-xs-12 col-sm-6">
                                    <label>Tipo de conta</label><br>
                                    <label>
                                        <input type="radio" value="1" name="d_bancarios[tipo_conta]"
                                               class="flat-red" {{ old('d_bancarios.tipo_conta', isset($dadosBancarios->tipo_conta) ? $dadosBancarios->tipo_conta : '') == 1 || !old('d_bancarios.tipo_conta') ? 'checked' : '' }}>
                                        Corrente
                                    </label>
                                    <label>
                                        <input type="radio" value="2" name="d_bancarios[tipo_conta]"
                                               class="flat-red" {{ old('d_bancarios.tipo_conta', isset($dadosBancarios->tipo_conta) ? $dadosBancarios->tipo_conta : '') == 2 ? 'checked' : '' }}>
                                        Poupança
                                    </label>
                                </div>
                                <div class="form-group col-xs-12 col-sm-8">
                                    <label for="agencia">Agência</label>
                                    <input type="text" name="d_bancarios[agencia]"
                                           value="{{ old('d_bancarios.agencia', isset($dadosBancarios->agencia) ? $dadosBancarios->agencia : '') }}"
                                           class="form-control" placeholder="Agência">
                                </div>
                                <div class="form-group col-xs-12 col-sm-4">
                                    <label for="digito_agencia">Dígito Agência</label>
                                    <small class="text-red">Caso não exista digito coloque o numero 0 (zero)</small>
                                    <input type="text" name="d_bancarios[agencia_digito]"
                                           value="{{ old('d_bancarios.agencia_digito', isset($dadosBancarios->agencia_digito) ? $dadosBancarios->agencia_digito : '') }}"
                                           class="form-control"
                                           placeholder="Dígito Agência">
                                </div>
                                <div class="form-group col-xs-12 col-sm-8">
                                    <label for="conta">Conta</label>
                                    <input type="text" name="d_bancarios[conta]"
                                           value="{{ old('d_bancarios.conta', isset($dadosBancarios->conta) ? $dadosBancarios->conta : '') }}"
                                           class="form-control" placeholder="Conta">
                                </div>
                                <div class="form-group col-xs-12 col-sm-4">
                                    <label for="digito_conta">Dígito Conta</label>
                                    <small class="text-red">Caso não exista digito coloque o numero 0 (zero)</small>
                                    <input type="text" name="d_bancarios[conta_digito]"
                                           value="{{ old('d_bancarios.conta_digito', isset($dadosBancarios->conta_digito) ? $dadosBancarios->conta_digito : '') }}"
                                           class="form-control" placeholder="Dígito Conta">
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-lg btn-success btn-block">Salvar</button>
                            </div>
                        </div>
                        @endif
                    </div>
            </form>
        </div>
    </section>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/iCheck/square/red.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
@endsection

@section('script')
<script src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>
<script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
<script type="text/javascript">
    // Dropzone.autoDiscover = false;

    $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();

        $('input').iCheck({
            checkboxClass: 'icheckbox_square-red',
            radioClass: 'iradio_square-red',
            increaseArea: '20%' // optional
        });
    })
</script>
@endsection
