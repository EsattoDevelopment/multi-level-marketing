@extends('default.layout.main')

@section('content')

    <section class="content-header">
        <h1>
            Dados pessoais
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dados pessoais</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="notifications top-right"></div>
        <div class="row">
            <div class="col-md-12">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header"
                         style="background-color: #{{ $usuario->getRelation('titulo')->cor }}">
                        <h3 class="widget-user-username">{{ $usuario->name }}</h3>
                        <h5 class="widget-user-desc">{{ $usuario->getRelation('titulo')->name }}</h5>
                        @if($usuario->status == 0)
                            <h4 class="text-red">Pendente</h4>
                        @endif
                    </div>
                    <div class="widget-user-image">
                        @if($usuario->image)
                            <img class="img-circle"
                                 src="{{ route('imagecache',['fotoclube','user/'.$usuario->image]) }}"
                                 alt="{{ $usuario->name }}">
                        @else
                            <img class="img-circle" src="{{ route('imagecache',['fotoclube',"user-img.jpg"]) }}"
                                 alt="{{ $usuario->name }}">
                        @endif
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-6 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">{{ $usuario->email }}</h5>
                                    <span class="description-text">E-mail</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            @if($sistema->campo_cpf)
                                @if(!$usuario->estrangeiro)
                                @role('usuario-comum')
                                <div class="@if(!$usuario->estrangeiro) col-sm-6 @else col-sm-12 @endif border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">{{ $usuario->cpf }}</h5>
                                        <span class="description-text">CPF/CNPJ</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                @endrole
                                @role('user-empresa')
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">{{ $usuario->cnpj }}</h5>
                                        <span class="description-text">CNPJ</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                @endrole
                                @endif
                            @endif
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
        @include('default.errors.errors')
        <div class="row">
            <div>
                <form role="form"
                      action="{{ route('dados-usuario.pessoais.update') }}"
                      method="post" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                    <input type="hidden" name="user_id" value="{{ $usuario->id }}">
                    <input type="hidden" name="d_bancarios[user_id]" value="{{ $usuario->id }}">
                    <input type="hidden" name="_method" value="PUT">

                    {{--Dados pessoais--}}
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title">Dados pessoais</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body">
                                <div class="form-group {{ $usuario->isEmpresa ? 'col-md-3' : 'col-md-6' }}">
                                    <label for="exampleInputEmail1">Nome <strong class="text-red">*</strong></label>
                                    <input type="text" name="pessoal[name]"
                                           value="{{ old('pessoal.name', $usuario->getOriginal()['name'] ?? '') }}"
                                           class="form-control" {{ $usuario->documento ? 'disabled="disabled"' : '' }} placeholder="Nome">
                                </div>

                                @if($usuario->isEmpresa)
                                    <div class="form-group {{ $usuario->isEmpresa ? 'col-md-3' : 'col-md-6' }}">
                                        <label for="exampleInputEmail1">Nome Empresa <strong class="text-red">*</strong></label>
                                        <input type="text" name="pessoal[empresa]"
                                               value="{{ old('pessoal.empresa', $usuario->empresa ?? '') }}"
                                               class="form-control" {{ $usuario->documento ? 'disabled="disabled"' : '' }} placeholder="Nome Empresa">
                                    </div>
                                @endif

                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">E-mail <strong class="text-red">*</strong></label>
                                    <input type="text" name="pessoal[email]"
                                           value="{{ old('pessoal.email', $usuario->email ?? '') }}"
                                           class="form-control" {{ $usuario->documento ? 'disabled="disabled"' : '' }} placeholder="E-mail">
                                </div>

                                @if($sistema->campo_cpf)
                                    @if(!$usuario->estrangeiro)
                                    @role('usuario-comum')
                                    <div class="form-group col-xs-12 col-md-3">
                                        <label for="exampleInputEmail1">CPF/CNPJ</label>
                                        <input type="text" name="pessoal[cpf]" readonly {{ $usuario->documento ? 'disabled="disabled"' : '' }}
                                        value="{{ old('pessoal.cpf', $usuario->cpf ?? '') }}"
                                               class="form-control" placeholder="CPF">
                                    </div>
                                    @endrole

                                    @role('user-empresa')
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">CNPJ</label>
                                        <input type="text" name="pessoal[cnpj]"
                                               value="{{ old('pessoal.cnpj', $usuario->cnpj ?? '') }}"
                                               class="form-control" placeholder="CNPJ">
                                    </div>
                                    @endrole
                                    @endif
                                @endif

                                @if($sistema->campo_rg)
                                    @if(!$usuario->estrangeiro)
                                    @role('usuario-comum')
                                    <div class="form-group col-xs-12 col-md-3">
                                        <label for="exampleInputEmail1">RG</label>
                                        <input type="text" name="pessoal[rg]"
                                               value="{{ old('pessoal.rg', $usuario->rg ?? '') }}"
                                               class="form-control" {{ $usuario->documento ? 'disabled="disabled"' : '' }} placeholder="RG">
                                    </div>
                                    @endrole
                                    @endif
                                @endif

                                @if($sistema->campo_dtnasc)
                                    <div class="form-group col-md-6">
                                        <label for="exampleInputEmail1">Data de Nascimento <strong class="text-red">*</strong></label>
                                        <input type="text" name="pessoal[data_nasc]"
                                               value="{{ old('pessoal.data_nasc', $usuario->data_nasc ?? '') }}"
                                               class="form-control datepicker"
                                               placeholder="Data de Nascimento" {{ $usuario->documento ? 'disabled="disabled"' : '' }}>
                                    </div>
                                @endif

                                @if($sistema->campo_rg)
                                    <div class="form-group col-md-3">
                                        <label for="exampleInputEmail1">Profissão</label>
                                        <input type="text" name="pessoal[profissao]"
                                               value="{{ old('pessoal.profissao', $usuario->profissao ??  '') }}"
                                               class="form-control" placeholder="Profissão" {{ $usuario->documento ? 'disabled="disabled"' : '' }}>
                                    </div>
                                @endif

                                <div class="form-group col-md-3">
                                    <label>Estado Civil</label> <small class="text-red">somente pessoa fisica</small>
                                    <select class="form-control select2" name="pessoal[estado_civil]" {{ $usuario->documento ? 'disabled="disabled"' : '' }}
                                    data-placeholder="Selecione um estado civil" style="width: 100%;">
                                        @foreach(config('constants.estado_civil') as $key => $value)
                                            <option @if(old('pessoal.estado_civil', isset($usuario) ? $usuario->estado_civil : '') == $key) selected
                                                    @endif value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Telefone Fixo</label>
                                    <input type="text" name="pessoal[telefone]"
                                           value="{{ old('pessoal.telefone', isset($usuario->telefone) ? $usuario->telefone : '') }}"
                                           class="form-control" placeholder="Telefone">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Celular <strong class="text-red">*</strong></label>
                                    <input required type="text" name="pessoal[celular]"
                                           value="{{ old('pessoal.celular', isset($usuario->celular) ? $usuario->celular : '') }}"
                                           class="form-control" placeholder="Celular">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="image">Foto de perfil</label><br>
                                    <button id="upload_imagem" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar foto e enviar</button>
                                    <div id="progress_imagem" class="progress active" style="display: none;">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>

                    @if($sistema->rede_binaria)
                        <div class="col-md-12">
                            <!-- general form elements -->
                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Lado Preferencial (Binário) <strong class="text-red">*</strong></h3>
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <div class="box-body">
                                    <div class="form-group col-xs-12">
                                        <label style="padding-right: 25px">
                                            <input type="radio" value="1" name="equipe_preferencial" class="flat-red" {{ old('equipe_preferencial', $usuario->equipe_preferencial)  == 1 ? 'checked' : '' }}>
                                            Binário esquerdo
                                        </label>
                                        <label>
                                            <input type="radio" value="2" name="equipe_preferencial" class="flat-red" {{ old('equipe_preferencial', $usuario->equipe_preferencial)  == 2 ? 'checked' : '' }}>
                                            Binário direito
                                        </label>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                    @endif

                    @if(Auth::user()->idade < 18 || strlen(Auth::user()->cpf) > 14)
                        @include('default.dados-usuario.blocos.responsavel')
                    @endif

                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-info"></i> Notificações</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <div class="box-body">
                                <div class="form-group col-md-12">
                                    <label>
                                        <input type="checkbox" name="pessoal[avisa_recebimento_rentabilidade]" class="" value="1"
                                                {{ old('pessoal.avisa_recebimento_rentabilidade', $usuario->avisa_recebimento_rentabilidade) == 1 ? 'checked' : ''  }}>
                                        Autorizo que a {{ ucfirst(env('COMPANY_NAME_SHORT', 'empresa')) }} envie informações importantes para meu e-mail.
                                    </label>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                    </div>

                    <div class="col-xs-12">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success btn-block btn-lg pull-left">Salvar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/red.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css')}}">
    <link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/css/bootstrap-notify.min.css">
    <style>.dz-preview{visibility: hidden; display: none;}</style>
@endsection

@section('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/backend/tabelas.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/backend/bootstrap-confirmation.js') }}" type="text/javascript"></script>
    <!-- InputMask -->
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.min.js?v=50') }}"></script>
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js' ) }}"></script>
    <script src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js"></script>
    <script type="text/javascript">
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone(document.body, {
            url: '{{ route('dados-usuario.imagem') }}',
            headers: {
                "X-CSRF-TOKEN": "{!! csrf_token() !!}"
            },
            method: 'POST',
            paramName: 'imagem',
            resizeWidth: 300,
            resizeQuality: 0.6,
            acceptedFiles: ".jpeg,.jpg,.png",
            clickable: "#upload_imagem"
        });

        myDropzone.on("addedfile", function(file) {
            $("#progress_imagem").show();
            $("#upload_imagem").hide();
        });

        myDropzone.on("totaluploadprogress", function(totalUploadProgress, totalBytes, totalBytesSent) {
            $("#progress_imagem > .progress-bar").css({'width': totalUploadProgress + "%"});
        });

        myDropzone.on("complete", function(file) {
            $("#progress_imagem").hide();
            $("#progress_imagem > .progress-bar").css({'width': "0%"});
            $("#upload_imagem").show();
        });

        myDropzone.on("error", function(errorMessage) {
            $(".top-right").notify({
                message: { html: '<i class="fa fa-error"></i> Erro ao enviar sua foto, tente novamente. '},
                type: 'error'
            }).show();
        });

        myDropzone.on("success", function(file, retorno) {
            $(".user-panel > .image > img, .user-menu img.user-image, .widget-user-image > img, .dropdown-menu > .user-header > img").attr('src', retorno.imagem);
            $(".top-right").notify({
                message: { html: '<i class="fa fa-check"></i> Sua nova foto foi enviada com sucesso! '},
                type: 'success'
            }).show();
        });

        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            $("input[name='pessoal[telefone]']").inputmask({
                mask: ['(99) 9999-9999', '(99) 99999-9999'],
                showTooltip: true,
                showMaskOnHover: true,
                clearIncomplete: true
            });

            $("input[name='pessoal[telefone2]']").inputmask({
                mask: ['(99) 9999-9999', '(99) 99999-9999'],
                showTooltip: true,
                showMaskOnHover: true,
                clearIncomplete: true
            });

            $("input[name='pessoal[celular]'], input[name='responsavel[telefone]']").inputmask({
                mask: ['(99) 9999-9999', '(99) 99999-9999'],
                showTooltip: true,
                showMaskOnHover: true,
                clearIncomplete: true
            });


            $("input[name='pessoal[cpf]'], input[name='responsavel[cpf]']").inputmask({
                mask: ['999.999.999-99', '99.999.999/9999-99'],
                showTooltip: true,
                showMaskOnHover: true,
                clearIncomplete: true
            });

            $("input[name='pessoal[responsavel]']").inputmask({
                mask: '999.999.999-99',
                showTooltip: true,
                showMaskOnHover: true,
                clearIncomplete: true
            });

            $("input[name='pessoal[cnpj]']").inputmask({
                mask: '99.999.999/9999-99',
                showTooltip: true,
                showMaskOnHover: true,
                clearIncomplete: true
            });

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });

            $('input[name="equipe_preferencial"][value="{{ old('equipe_preferencial', $usuario->equipe_preferencial) == 1 ? 1 : 2 }}"]').iCheck('check');

            $.fn.datepicker.defaults.language = 'pt-BR';

            $("input[name='pessoal[data_nasc]'], input[name='responsavel[data_nasc]']").inputmask({
                mask: '99/99/9999',
                showTooltip: true,
                showMaskOnHover: true,
                clearIncomplete: true
            });

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });
        })
    </script>
@endsection
