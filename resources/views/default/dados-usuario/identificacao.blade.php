@extends('default.layout.main')

@section('content')

    <section class="content-header">
        <h1>
            Documentos
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Enviar Documentos</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="notifications top-right"></div>
        <div class="row">
            <div>
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Comprovante de identificação <strong class="text-red">COM FOTO</strong></h3><br>
                            @if(Auth::user()->isEmpresa)
                                <small>Envie a imagem do CPF ou RG <strong class="text-red">(COM CPF)</strong> do responsável juridico pela empresa.</small>
                            @else
                                @if(Auth::user()->idade < 18)
                                    <small>Envie a imagem do CPF ou RG <strong class="text-red">(COM CPF)</strong>.</small>
                                @else
                                    <small>Envie a imagem de sua CNH ou RG <strong class="text-red">(COM CPF)</strong>.</small>
                                @endif
                            @endif
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body" id="identidade">
                            @if($usuario->status_cpf == 'validado')
                                <div class="alert alert-success" style="margin-bottom: 0;">
                                    <span>Sua documentação foi recebida corretamente, obrigado.</span>
                                </div>
                            @elseif($usuario->status_cpf == 'em_analise')
                                <div class="alert alert-warning">
                                    <span>Sua documentação está sendo analisada por nossa equipe.</span>
                                </div>
                                @if($usuario->image_cpf == null || $usuario->image_cpf == '')
                                    <div class="col-md-6">
                                        <div id="drop_identidade"></div>
                                        <button id="upload_identidade" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar a <strong>FRENTE</strong> do documento e enviar</button>
                                        <div id="progress_imagem_identidade" class="progress active" style="display: none;">
                                            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                @endif
                                @if($usuario->image_cpf_verso == null || $usuario->image_cpf_verso == '')
                                    <div class="col-md-6">
                                        <div id="drop_identidade_verso"></div>
                                        <button id="upload_identidade_verso" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar o <strong>VERSO</strong> do documento e enviar</button>
                                        <div id="progress_imagem_identidade_verso" class="progress active" style="display: none;">
                                            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                @endif
                            @elseif($usuario->status_cpf == null || $usuario->status_cpf == 'recusado')
                                @if($usuario->status_cpf == 'recusado')
                                    <div class="alert alert-error">
                                        <span>Sua documentação permanece pendente, por favor enviar novamente!</span>
                                    </div>
                                @endif
                                <div class="col-md-6" id="identidade_envio">
                                    <div id="drop_identidade"></div>
                                    <button id="upload_identidade" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar a <strong>FRENTE</strong> do documento e enviar</button>
                                    <div id="progress_imagem_identidade" class="progress active" style="display: none;">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="identidade_envio_verso">
                                    <div id="drop_identidade_verso"></div>
                                    <button id="upload_identidade_verso" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar o <strong>VERSO</strong> do documento e enviar</button>
                                    <div id="progress_imagem_identidade_verso" class="progress active" style="display: none;">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            @if(Auth::user()->isEmpresa)
                                <h3 class="box-title">Comprovante de endereço do responsável juridico pela empresa.</h3><br>
                                @else
                                <h3 class="box-title">Comprovante de endereço</h3><br>
                                @endif
                            <small>Comprovantes aceitos: água, luz, telefone, IPTU, internet, tv, extrato bancário, aluguel, condominio.</small>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body" id="endereco">
                            @if($usuario->status_comprovante_endereco == 'validado')
                                <div class="alert alert-success" style="margin-bottom: 0;">
                                    <span>Seu comprovante foi recebido corretamente, obrigado.</span>
                                </div>
                            @elseif($usuario->status_comprovante_endereco == 'em_analise')
                                <div class="alert alert-warning" style="margin-bottom: 0;">
                                    <span>Seu comprovante está sendo analisado por nossa equipe.</span>
                                </div>
                            @elseif($usuario->status_comprovante_endereco == null || $usuario->status_comprovante_endereco == 'recusado')
                                @if($usuario->status_comprovante_endereco == 'recusado')
                                    <div class="alert alert-error">
                                        <span>Seu comprovante permanece pendente, por favor enviar novamente!</span>
                                    </div>
                                @endif
                                <div id="drop_endereco"></div>
                                <button id="upload_endereco" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar comprovante e enviar</button>
                                <div id="progress_endereco" class="progress active" style="display: none;">
                                    <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <div class="col-md-12">
                                @if(Auth::user()->idade < 18)
                                    <h3 class="box-title">Foto do documento junto ao menor</h3><br>
                                    <small>Tire uma foto do documento de identificação junto ao menor <strong class="text-red">(FRENTE COM FOTO) </strong>como no exemplo abaixo:</small>
                                @else
                                    <h3 class="box-title">Selfie segurando o documento</h3><br>
                                    <small>Tire uma foto segurando o seu documento de identificação <strong class="text-red">(FRENTE COM FOTO) </strong>como no exemplo abaixo:</small>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <img src="{{asset('images/selfie.png')}}" width="100%">
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body" id="selfie">
                            @if($usuario->status_selfie == 'validado')
                                <div class="alert alert-success" style="margin-bottom: 0;">
                                    <span>Sua selfie foi recebida corretamente, obrigado.</span>
                                </div>
                            @elseif($usuario->status_selfie == 'em_analise')
                                <div class="alert alert-warning" style="margin-bottom: 0;">
                                    <span>Sua selfie está sendo analisada por nossa equipe.</span>
                                </div>
                            @elseif($usuario->status_selfie == null || $usuario->status_selfie == 'recusado')
                                @if($usuario->status_selfie == 'recusado')
                                    <div class="alert alert-error">
                                        <span>Sua selfie permanece pendente, por favor enviar novamente!</span>
                                    </div>
                                @endif
                                <div id="drop_selfie"></div>
                                <button id="upload_selfie" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar foto e enviar</button>
                                <div id="progress_selfie" class="progress active" style="display: none;">
                                    <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>

            </div>
        </div>
        @if(Auth::user()->idade < 18 && strlen(Auth::user()->cpf) < 15)
            <div class="row">
                <section class="content-header">
                    <h1>
                        Documentos do responsável legal
                    </h1>
                    <br>
                </section>
                <div class="col-sm-12 col-md-12">
                    <!-- general form elements -->
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Comprovante de identificação do Representante Legal <strong class="text-red">(COM FOTO)</strong></h3><br>
                            <small>Envie a imagem de sua CNH ou RG <strong class="text-red">(COM CPF)</strong>.</small>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body" id="identidade_responsavel">
                            @if($responsavel->status_documento == 'validado')
                                <div class="alert alert-success" style="margin-bottom: 0;">
                                    <span>Sua documentação foi recebida corretamente, obrigado.</span>
                                </div>
                            @elseif($responsavel->status_documento == 'em_analise')
                                <div class="alert alert-warning">
                                    <span>Sua documentação está sendo analisada por nossa equipe.</span>
                                </div>
                                @if($responsavel->documento == null || $responsavel->documento == '')
                                    <div class="col-sm-6 col-md-6">
                                        <div id="drop_identidade_responsavel"></div>
                                        <button id="upload_identidade_responsavel" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar a FRENTE do documento e enviar</button>
                                        <div id="progress_imagem_identidade_responsavel" class="progress active" style="display: none;">
                                            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                @endif
                                @if($responsavel->documento_verso == null || $responsavel->documento_verso == '')
                                    <div class="col-sm-6 col-md-6">
                                        <div id="drop_identidade_responsavel_verso"></div>
                                        <button id="upload_identidade_responsavel_verso" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar o VERSO do documento e enviar</button>
                                        <div id="progress_imagem_identidade_responsavel_verso" class="progress active" style="display: none;">
                                            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                @endif
                            @elseif($responsavel->status_documento == null || $responsavel->status_documento == 'recusado')
                                @if($responsavel->status_documento == 'recusado')
                                    <div class="alert alert-error">
                                        <span>Sua documentação permanece pendente, por favor enviar novamente!</span>
                                    </div>
                                @endif
                                <div class="col-sm-6 col-md-6">
                                    <div id="drop_identidade_responsavel"></div>
                                    <button id="upload_identidade_responsavel" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar a FRENTE do documento e enviar</button>
                                    <div id="progress_imagem_identidade_responsavel" class="progress active" style="display: none;">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div id="drop_identidade_responsavel_verso"></div>
                                    <button id="upload_identidade_responsavel_verso" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar o VERSO do documento e enviar</button>
                                    <div id="progress_imagem_identidade_responsavel_verso" class="progress active" style="display: none;">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <div class="col-sm-12 col-md-12">
                    <!-- general form elements -->
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <div class="col-md-12">
                                <h3 class="box-title">Selfie do Representante Legal</h3><br>
                                <small>Tire uma foto segurando o seu documento de identificação <strong class="text-red">(FRENTE COM FOTO) </strong>como no exemplo abaixo::</small>
                            </div>
                            <div class="col-md-4">
                                <img src="{{asset('images/selfie.png')}}" width="100%">
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body" id="selfie_responsavel">
                            @if($responsavel->status_selfie == 'validado')
                                <div class="alert alert-success" style="margin-bottom: 0;">
                                    <span>Sua selfie foi recebida corretamente, obrigado.</span>
                                </div>
                            @elseif($responsavel->status_selfie == 'em_analise')
                                <div class="alert alert-warning" style="margin-bottom: 0;">
                                    <span>Sua selfie está sendo analisada por nossa equipe.</span>
                                </div>
                            @elseif($responsavel->status_selfie == null || $responsavel->status_selfie == 'recusado')
                                @if($responsavel->status_selfie == 'recusado')
                                    <div class="alert alert-error">
                                        <span>Sua selfie permanece pendente, por favor enviar novamente!</span>
                                    </div>
                                @endif
                                <div id="drop_selfie_responsavel"></div>
                                <button id="upload_selfie_responsavel" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar foto e enviar</button>
                                <div id="progress_selfie_responsavel" class="progress active" style="display: none;">
                                    <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <div class="col-sm-12 col-md-12">
                    <!-- general form elements -->
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Comprovante da Representação Legal</h3><br>
                            <small>Envie uma IMAGEM ou PDF do documento que comprove a responsabilidade sobre o menor. Ex: Certidão de nascimento, Tutela, documento judicial.</small>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body" id="representacao_responsavel">
                            @if($responsavel->status_documento_representacao == 'validado')
                                <div class="alert alert-success" style="margin-bottom: 0;">
                                    <span>Sua documentação foi recebida corretamente, obrigado.</span>
                                </div>
                            @elseif($responsavel->status_documento_representacao == 'em_analise')
                                <div class="alert alert-warning" style="margin-bottom: 0;">
                                    <span>Sua documentação está sendo analisada por nossa equipe.</span>
                                </div>
                            @elseif($responsavel->status_documento_representacao == null || $responsavel->status_documento_representacao == 'recusado')
                                @if($responsavel->status_documento_representacao == 'recusado')
                                    <div class="alert alert-error">
                                        <span>Sua documentação permanece pendente, por favor enviar novamente!</span>
                                    </div>
                                @endif
                                <div id="drop_representacao_responsavel"></div>
                                <button id="upload_representacao_responsavel" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar comprovante e enviar</button>
                                <div id="progress_imagem_representacao_responsavel" class="progress active" style="display: none;">
                                    <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        @endif

{{--        @if(Auth::user()->isEmpresa)
            <div class="row">
                <section class="content-header">
                    <h1>
                        Documento da Empresa
                    </h1>
                    <br>
                </section>
                <div class="col-sm-12 col-md-12">
                    <!-- general form elements -->
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Comprovante de identificação do Representante Legal <strong class="text-red">(COM FOTO)</strong></h3><br>
                            <small>Envie a imagem de sua CNH ou RG <strong class="text-red">(COM CPF)</strong>.</small>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body" id="identidade_responsavel">
                            @if($responsavel->status_documento == 'validado')
                                <div class="alert alert-success" style="margin-bottom: 0;">
                                    <span>Sua documentação foi recebida corretamente, obrigado.</span>
                                </div>
                            @elseif($responsavel->status_documento == 'em_analise')
                                <div class="alert alert-warning">
                                    <span>Sua documentação está sendo analisada por nossa equipe.</span>
                                </div>
                                @if($responsavel->documento == null || $responsavel->documento == '')
                                    <div class="col-sm-6 col-md-6">
                                        <div id="drop_identidade_responsavel"></div>
                                        <button id="upload_identidade_responsavel" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar a FRENTE do documento e enviar</button>
                                        <div id="progress_imagem_identidade_responsavel" class="progress active" style="display: none;">
                                            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                @endif
                                @if($responsavel->documento_verso == null || $responsavel->documento_verso == '')
                                    <div class="col-sm-6 col-md-6">
                                        <div id="drop_identidade_responsavel_verso"></div>
                                        <button id="upload_identidade_responsavel_verso" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar o VERSO do documento e enviar</button>
                                        <div id="progress_imagem_identidade_responsavel_verso" class="progress active" style="display: none;">
                                            <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                        </div>
                                    </div>
                                @endif
                            @elseif($responsavel->status_documento == null || $responsavel->status_documento == 'recusado')
                                @if($responsavel->status_documento == 'recusado')
                                    <div class="alert alert-error">
                                        <span>Sua documentação permanece pendente, por favor enviar novamente!</span>
                                    </div>
                                @endif
                                <div class="col-sm-6 col-md-6">
                                    <div id="drop_identidade_responsavel"></div>
                                    <button id="upload_identidade_responsavel" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar a FRENTE do documento e enviar</button>
                                    <div id="progress_imagem_identidade_responsavel" class="progress active" style="display: none;">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div id="drop_identidade_responsavel_verso"></div>
                                    <button id="upload_identidade_responsavel_verso" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar o VERSO do documento e enviar</button>
                                    <div id="progress_imagem_identidade_responsavel_verso" class="progress active" style="display: none;">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <div class="col-sm-12 col-md-12">
                    <!-- general form elements -->
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <div class="col-md-12">
                                <h3 class="box-title">Selfie do Representante Legal</h3><br>
                                <small>Tire uma foto segurando o seu documento de identificação <strong class="text-red">(FRENTE COM FOTO) </strong>como no exemplo abaixo::</small>
                            </div>
                            <div class="col-md-4">
                                <img src="{{asset('images/selfie.png')}}" width="100%">
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body" id="selfie_responsavel">
                            @if($responsavel->status_selfie == 'validado')
                                <div class="alert alert-success" style="margin-bottom: 0;">
                                    <span>Sua selfie foi recebida corretamente, obrigado.</span>
                                </div>
                            @elseif($responsavel->status_selfie == 'em_analise')
                                <div class="alert alert-warning" style="margin-bottom: 0;">
                                    <span>Sua selfie está sendo analisada por nossa equipe.</span>
                                </div>
                            @elseif($responsavel->status_selfie == null || $responsavel->status_selfie == 'recusado')
                                @if($responsavel->status_selfie == 'recusado')
                                    <div class="alert alert-error">
                                        <span>Sua selfie permanece pendente, por favor enviar novamente!</span>
                                    </div>
                                @endif
                                <div id="drop_selfie_responsavel"></div>
                                <button id="upload_selfie_responsavel" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar foto e enviar</button>
                                <div id="progress_selfie_responsavel" class="progress active" style="display: none;">
                                    <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <div class="col-sm-12 col-md-12">
                    <!-- general form elements -->
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Comprovante da Representação Legal</h3><br>
                            <small>Envie uma IMAGEM ou PDF do documento que comprove a responsabilidade sobre o menor. Ex: Certidão de nascimento, Tutela, documento judicial.</small>
                        </div>
                        <!-- /.box-header -->
                        <!-- form start -->
                        <div class="box-body" id="representacao_responsavel">
                            @if($responsavel->status_documento_representacao == 'validado')
                                <div class="alert alert-success" style="margin-bottom: 0;">
                                    <span>Sua documentação foi recebida corretamente, obrigado.</span>
                                </div>
                            @elseif($responsavel->status_documento_representacao == 'em_analise')
                                <div class="alert alert-warning" style="margin-bottom: 0;">
                                    <span>Sua documentação está sendo analisada por nossa equipe.</span>
                                </div>
                            @elseif($responsavel->status_documento_representacao == null || $responsavel->status_documento_representacao == 'recusado')
                                @if($responsavel->status_documento_representacao == 'recusado')
                                    <div class="alert alert-error">
                                        <span>Sua documentação permanece pendente, por favor enviar novamente!</span>
                                    </div>
                                @endif
                                <div id="drop_representacao_responsavel"></div>
                                <button id="upload_representacao_responsavel" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar comprovante e enviar</button>
                                <div id="progress_imagem_representacao_responsavel" class="progress active" style="display: none;">
                                    <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                                </div>
                            @endif
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        @endif--}}
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="https://rawgit.com/enyo/dropzone/master/dist/dropzone.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/css/bootstrap-notify.min.css">
@endsection

@section('script')
    <script src="{{ asset('js/backend/bootstrap-confirmation.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js"></script>
    <script type="text/javascript">
        Dropzone.autoDiscover = false;

        $(function () {
            $("div#drop_identidade").dropzone({
                url: '{{ route('dados-usuario.identificacao.store') }}',
                headers: {
                    "X-CSRF-TOKEN": "{!! csrf_token() !!}"
                },
                method: 'POST',
                params: {
                    action: 'identidade'
                },
                paramName: 'imagem',
                resizeWidth: 800,
                resizeQuality: 0.6,
                acceptedFiles: ".jpeg,.jpg,.png",
                clickable: "#upload_identidade",
                addedfile: function(file) {
                    $("#progress_imagem_identidade").show();
                    $("#upload_identidade").hide();
                },
                totaluploadprogress: function(totalUploadProgress, totalBytes, totalBytesSent) {
                    $("#progress_imagem_identidade > .progress-bar").css({'width': totalUploadProgress + "%"});
                },
                complete: function(file) {
                    $("#progress_imagem_identidade").hide();
                    $("#progress_imagem_identidade > .progress-bar").css({'width': "0%"});
                    $("#upload_identidade").show();
                },
                error: function(errorMessage) {
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-error"></i> Erro ao enviar seu comprovante, tente novamente. '},
                        type: 'error'
                    }).show();
                },
                success: function(file, retorno) {
                    $(".user-panel > .image > img, .user-menu img.user-image, .widget-user-image > img").attr('src', retorno.imagem);

                    $("#identidade_envio").html(`<div class="alert alert-`+ retorno.flash +`" style="margin-bottom: 0">`+ retorno.msg +`</div>`);

                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-check"></i> Seu comprovante foi enviado com sucesso! '},
                        type: 'success'
                    }).show();
                }
            });

            $("div#drop_identidade_verso").dropzone({
                url: '{{ route('dados-usuario.identificacao.store') }}',
                headers: {
                    "X-CSRF-TOKEN": "{!! csrf_token() !!}"
                },
                method: 'POST',
                params: {
                    action: 'identidade_verso'
                },
                paramName: 'imagem',
                resizeWidth: 800,
                resizeQuality: 0.6,
                acceptedFiles: ".jpeg,.jpg,.png",
                clickable: "#upload_identidade_verso",
                addedfile: function(file) {
                    $("#progress_imagem_identidade_verso").show();
                    $("#upload_identidade_verso").hide();
                },
                totaluploadprogress: function(totalUploadProgress, totalBytes, totalBytesSent) {
                    $("#progress_imagem_identidade_verso > .progress-bar").css({'width': totalUploadProgress + "%"});
                },
                complete: function(file) {
                    $("#progress_imagem_identidade_verso").hide();
                    $("#progress_imagem_identidade.verso > .progress-bar").css({'width': "0%"});
                    $("#upload_identidade.verso").show();
                },
                error: function(errorMessage) {
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-error"></i> Erro ao enviar seu comprovante, tente novamente. '},
                        type: 'error'
                    }).show();
                },
                success: function(file, retorno) {
                    $(".user-panel > .image > img, .user-menu img.user-image, .widget-user-image > img").attr('src', retorno.imagem);

                    $("#identidade_envio_verso").html(`<div class="alert alert-`+ retorno.flash +`" style="margin-bottom: 0">`+ retorno.msg +`</div>`);

                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-check"></i> Seu comprovante foi enviado com sucesso! '},
                        type: 'success'
                    }).show();
                }
            });

            $("div#drop_endereco").dropzone({
                url: '{{ route('dados-usuario.identificacao.store') }}',
                headers: {
                    "X-CSRF-TOKEN": "{!! csrf_token() !!}"
                },
                method: 'POST',
                params: {
                    action: 'endereco'
                },
                paramName: 'imagem',
                resizeWidth: 800,
                resizeQuality: 0.6,
                acceptedFiles: ".jpeg,.jpg,.png,.pdf",
                clickable: "#upload_endereco",
                addedfile: function(file) {
                    $("#progress_endereco").show();
                    $("#upload_endereco").hide();
                },
                totaluploadprogress: function(totalUploadProgress, totalBytes, totalBytesSent) {
                    $("#progress_endereco > .progress-bar").css({'width': totalUploadProgress + "%"});
                },
                complete: function(file) {
                    $("#progress_endereco").hide();
                    $("#progress_endereco > .progress-bar").css({'width': "0%"});
                    $("#upload_endereco").show();
                },
                error: function(errorMessage) {
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-error"></i> Erro ao enviar seu comprovante, tente novamente. '},
                        type: 'error'
                    }).show();
                },
                success: function(file, retorno) {
                    $(".user-panel > .image > img, .user-menu img.user-image, .widget-user-image > img").attr('src', retorno.imagem);
                    $("#endereco").html(`<div class="alert alert-`+ retorno.flash +`" style="margin-bottom: 0">`+ retorno.msg +`</div>`);
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-check"></i> Seu comprovante foi enviado com sucesso! '},
                        type: 'success'
                    }).show();
                }
            });

            $("div#drop_selfie").dropzone({
                url: '{{ route('dados-usuario.identificacao.store') }}',
                headers: {
                    "X-CSRF-TOKEN": "{!! csrf_token() !!}"
                },
                method: 'POST',
                params: {
                    action: 'selfie'
                },
                paramName: 'imagem',
                resizeWidth: 800,
                resizeQuality: 0.6,
                acceptedFiles: ".jpeg,.jpg,.png",
                clickable: "#upload_selfie",
                addedfile: function(file) {
                    $("#progress_selfie").show();
                    $("#upload_selfie").hide();
                },
                totaluploadprogress: function(totalUploadProgress, totalBytes, totalBytesSent) {
                    $("#progress_selfie > .progress-bar").css({'width': totalUploadProgress + "%"});
                },
                complete: function(file) {
                    $("#progress_selfie").hide();
                    $("#progress_selfie > .progress-bar").css({'width': "0%"});
                    $("#upload_selfie").show();
                },
                error: function(errorMessage) {
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-error"></i> Erro ao enviar sua selfie, tente novamente. '},
                        type: 'error'
                    }).show();
                },
                success: function(file, retorno) {
                    $(".user-panel > .image > img, .user-menu img.user-image, .widget-user-image > img").attr('src', retorno.imagem);
                    $("#selfie").html(`<div class="alert alert-`+ retorno.flash +`" style="margin-bottom: 0">`+ retorno.msg +`</div>`);
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-check"></i> Sua selfie foi enviada com sucesso! '},
                        type: 'success'
                    }).show();
                }
            });

            $("div#drop_selfie_responsavel").dropzone({
                url: '{{ route('dados-usuario.identificacao.store') }}',
                headers: {
                    "X-CSRF-TOKEN": "{!! csrf_token() !!}"
                },
                method: 'POST',
                params: {
                    action: 'selfie_responsavel'
                },
                paramName: 'imagem',
                resizeWidth: 800,
                resizeQuality: 0.6,
                acceptedFiles: ".jpeg,.jpg,.png",
                clickable: "#upload_selfie_responsavel",
                addedfile: function(file) {
                    $("#progress_selfie_responsavel").show();
                    $("#upload_selfie_responsavel").hide();
                },
                totaluploadprogress: function(totalUploadProgress, totalBytes, totalBytesSent) {
                    $("#progress_selfie_responsavel > .progress-bar").css({'width': totalUploadProgress + "%"});
                },
                complete: function(file) {
                    $("#progress_selfie_responsavel").hide();
                    $("#progress_selfie_responsavel > .progress-bar").css({'width': "0%"});
                    $("#upload_selfie_responsavel").show();
                },
                error: function(errorMessage) {
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-error"></i> Erro ao enviar sua selfie, tente novamente. '},
                        type: 'error'
                    }).show();
                },
                success: function(file, retorno) {
                    $(".user-panel > .image > img, .user-menu img.user-image, .widget-user-image > img").attr('src', retorno.imagem);
                    $("#selfie_responsavel").html(`<div class="alert alert-`+ retorno.flash +`" style="margin-bottom: 0">`+ retorno.msg +`</div>`);
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-check"></i> Sua selfie foi enviada com sucesso! '},
                        type: 'success'
                    }).show();
                }
            });

            $("div#drop_identidade_responsavel").dropzone({
                url: '{{ route('dados-usuario.identificacao.store') }}',
                headers: {
                    "X-CSRF-TOKEN": "{!! csrf_token() !!}"
                },
                method: 'POST',
                params: {
                    action: 'identidade_responsavel'
                },
                paramName: 'imagem',
                resizeWidth: 800,
                resizeQuality: 0.6,
                acceptedFiles: ".jpeg,.jpg,.png",
                clickable: "#upload_identidade_responsavel",
                addedfile: function(file) {
                    $("#progress_imagem_identidade_responsavel").show();
                    $("#upload_identidade_responsavel").hide();
                },
                totaluploadprogress: function(totalUploadProgress, totalBytes, totalBytesSent) {
                    $("#progress_imagem_identidade_responsavel > .progress-bar").css({'width': totalUploadProgress + "%"});
                },
                complete: function(file) {
                    $("#progress_imagem_identidade_responsavel").hide();
                    $("#progress_imagem_identidade_responsavel > .progress-bar").css({'width': "0%"});
                    $("#upload_identidade_responsavel").show();
                },
                error: function(errorMessage) {
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-error"></i> Erro ao enviar identidade do responsavel, tente novamente. '},
                        type: 'error'
                    }).show();
                },
                success: function(file, retorno) {
                    $(".user-panel > .image > img, .user-menu img.user-image, .widget-user-image > img").attr('src', retorno.imagem);
                    $("#identidade_responsavel").html(`<div class="alert alert-`+ retorno.flash +`" style="margin-bottom: 0">`+ retorno.msg +`</div>`);
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-check"></i> Identidade do responsavel foi enviada com sucesso! '},
                        type: 'success'
                    }).show();
                }
            });

            $("div#drop_identidade_responsavel_verso").dropzone({
                url: '{{ route('dados-usuario.identificacao.store') }}',
                headers: {
                    "X-CSRF-TOKEN": "{!! csrf_token() !!}"
                },
                method: 'POST',
                params: {
                    action: 'identidade_responsavel_verso'
                },
                paramName: 'imagem',
                resizeWidth: 800,
                resizeQuality: 0.6,
                acceptedFiles: ".jpeg,.jpg,.png",
                clickable: "#upload_identidade_responsavel_verso",
                addedfile: function(file) {
                    $("#progress_imagem_identidade_responsavel_verso").show();
                    $("#upload_identidade_responsavel_verso").hide();
                },
                totaluploadprogress: function(totalUploadProgress, totalBytes, totalBytesSent) {
                    $("#progress_imagem_identidade_responsavel_verso > .progress-bar").css({'width': totalUploadProgress + "%"});
                },
                complete: function(file) {
                    $("#progress_imagem_identidade_responsavel_verso").hide();
                    $("#progress_imagem_identidade_responsavel_verso > .progress-bar").css({'width': "0%"});
                    $("#upload_identidade_responsavel_verso").show();
                },
                error: function(errorMessage) {
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-error"></i> Erro ao enviar identidade do responsavel, tente novamente. '},
                        type: 'error'
                    }).show();
                },
                success: function(file, retorno) {
                    $(".user-panel > .image > img, .user-menu img.user-image, .widget-user-image > img").attr('src', retorno.imagem);
                    $("#identidade_responsavel_verso").html(`<div class="alert alert-`+ retorno.flash +`" style="margin-bottom: 0">`+ retorno.msg +`</div>`);
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-check"></i> Identidade do responsavel foi enviada com sucesso! '},
                        type: 'success'
                    }).show();
                }
            });

            $("div#drop_representacao_responsavel").dropzone({
                url: '{{ route('dados-usuario.identificacao.store') }}',
                headers: {
                    "X-CSRF-TOKEN": "{!! csrf_token() !!}"
                },
                method: 'POST',
                params: {
                    action: 'representacao_responsavel'
                },
                paramName: 'imagem',
                resizeWidth: 800,
                resizeQuality: 0.6,
                acceptedFiles: ".jpeg,.jpg,.png,.pdf",
                clickable: "#upload_representacao_responsavel",
                addedfile: function(file) {
                    $("#progress_imagem_representacao_responsavel").show();
                    $("#upload_representacao_responsavel").hide();
                },
                totaluploadprogress: function(totalUploadProgress, totalBytes, totalBytesSent) {
                    $("#progress_imagem_representacao_responsavel > .progress-bar").css({'width': totalUploadProgress + "%"});
                },
                complete: function(file) {
                    $("#progress_imagem_representacao_responsavel").hide();
                    $("#progress_imagem_representacao_responsavel > .progress-bar").css({'width': "0%"});
                    $("#upload_representacao_responsavel").show();
                },
                error: function(errorMessage) {
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-error"></i> Erro ao enviar identidade do responsavel, tente novamente. '},
                        type: 'error'
                    }).show();
                },
                success: function(file, retorno) {
                    $(".user-panel > .image > img, .user-menu img.user-image, .widget-user-image > img").attr('src', retorno.imagem);
                    $("#representacao_responsavel").html(`<div class="alert alert-`+ retorno.flash +`" style="margin-bottom: 0">`+ retorno.msg +`</div>`);
                    $(".top-right").notify({
                        message: { html: '<i class="fa fa-check"></i> Identidade do responsavel foi enviada com sucesso! '},
                        type: 'success'
                    }).show();
                }
            });

        });

    </script>
@endsection