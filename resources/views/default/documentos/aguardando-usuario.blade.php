@extends('default.layout.main')

@section('content')

    <section class="content-header">
        @if($usuario != null)
            @if($usuario->status_cpf =='em_analise' || $usuario->status_comprovante_endereco == 'em_analise' || $usuario->status_selfie == 'em_analise')
                <h1>
                    <span class="text-red">Dados Pessoais</span>
                </h1>
            @endif
        @endif
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li>Documentos</li>
            <li><a href="{{route('documentos.associado.aguardando')}}">Aguardando Aprovação</a></li>
            <li class="active">Verificação de documentos</li>
        </ol>
            <br>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="notifications top-right"></div>
        <div class="row col-md-12">
            <div class="box">
                <div class="box-header">Dados do usuário</div>
                <div class="box-body">
                    <div class="col-xs-12  col-md-6">
                        <label for="">Nome: </label><span> {{$usuarioatual->name}}</span><br>
                        <label for="">CPF: </label><span> {{$usuarioatual->cpf}}</span><br>
                        @if($usuarioatual->rg != '')
                            <label for="">RG: </label><span> {{$usuarioatual->rg}}</span><br>
                        @endif
                        <label for="">Data Nascimento: </label><span> {{$usuarioatual->data_nasc}}</span><br>
                    </div>
                    @if($usuarioatual->status_cpf == 'validado')
                        <div class="col-xs-12 col-md-6">
                            <a data-fancybox href="{{route('imagecache',['visualizardoc', $usuarioatual->image_cpf])}}">
                                <img {{--class="img-circle"--}}
                                     src="{{ route('imagecache',['fotoclube', $usuarioatual->image_cpf]) }}"
                                     alt="{{$usuarioatual->image_cpf }}">
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <form name="doc" method="post" action="{{route('documentos.associado.aguardando.confirmacao')}}">
            {!! csrf_field() !!}
            @if($usuario != null)
                <input type="hidden" name="user_id" value="{{$usuario->id}}">
            @else
                <input type="hidden" name="user_id" value="{{$responsavel->user_id}}">
            @endif
            @if($responsavel != null)
                <input type="hidden" name="responsavel_id" value="{{$responsavel->id}}">
            @endif
            <div class="row">
                <div>
                    @if($usuario != null)
                        @if($usuario->status_cpf == 'em_analise')
                            <div class="col-md-4">
                                <!-- general form elements -->
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><i class="glyphicon glyphicon-file text-orange"></i>Documentos</h3><br>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <div class="box-body" id="identidade">
                                        <div class="form-group col-xs-12">
                                            <label for="">Nome: </label><span> {{$usuario->name}}</span><br>
                                            <label for="">CPF: </label><span> {{$usuario->cpf}}</span><br>
                                            @if($usuario->rg != '')
                                                <label for="">RG: </label><span> {{$usuario->rg}}</span><br>
                                            @endif
                                            <label for="">Data Nascimento: </label><span> {{$usuario->data_nasc}}</span><br>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <div class="form-group col-xs-6">
                                                <label for="">Frente </label>
                                            </div>
                                            <div class="form-group col-xs-6">
                                                <label for="">Verso </label>
                                            </div>
                                            <div class="form-group col-xs-6">
                                                @if($usuario->image_cpf != null || $usuario->image_cpf != '')
                                                    <a data-fancybox href="{{route('imagecache',['visualizardoc', $usuario->image_cpf])}}">
                                                        <img {{--class="img-circle"--}}
                                                             src="{{ route('imagecache',['fotoclube', $usuario->image_cpf]) }}"
                                                             alt="{{$usuario->image_cpf }}">
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="form-group col-xs-6">
                                                @if($usuario->image_cpf_verso != null || $usuario->image_cpf_verso != '')
                                                    <a data-fancybox href="{{route('imagecache',['visualizardoc', $usuario->image_cpf_verso])}}">
                                                        <img {{--class="img-circle"--}}
                                                             src="{{ route('imagecache',['fotoclube', $usuario->image_cpf_verso]) }}"
                                                             alt="{{$usuario->image_cpf_verso }}">
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label style="padding-right: 25px">
                                                <input type="radio" value="1" name="documento_aprovacao" class="flat-red" {{ old('documento_aprovacao', 2)  == 1 ? 'checked' : '' }}>
                                                Aprovar
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="documento_aprovacao" class="flat-red" {{ old('documento_aprovacao', 2)  == 0 ? 'checked' : '' }}>
                                                Recusar
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="documento_aprovacao" class="flat-red" {{ old('documento_aprovacao', 2)  == 2 ? 'checked' : '' }}>
                                                Em análise
                                            </label>
                                        </div>
                                        <div class="form-group col-xs-12 hidden" id="motivo_documento_aprovacao">
                                            <label for="motivo_documento_aprovacao">Motivo</label>
                                            <textarea name="motivo_documento_aprovacao" cols="20" class="form-control" placeholder="Motivo pelo qual o documento foi recusado" rows="3">{{ old('motivo_documento_aprovacao') }}</textarea>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        @endif

                        @if($usuario->status_selfie == 'em_analise')
                            <div class="col-md-4">
                                <!-- general form elements -->
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><i class="fa fa-user"></i> Selfie</h3><br>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <div class="box-body" id="selfie">
                                        <div class="form-group col-xs-6">
                                            <label for="">Documento </label>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label for="">Selfie </label>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <a data-fancybox href="{{route('imagecache',['visualizardoc', $usuario->image_cpf])}}">
                                                <img {{--class="img-circle"--}}
                                                     src="{{ route('imagecache',['fotoclube', $usuario->image_cpf]) }}"
                                                     alt="{{$usuario->image_cpf }}">
                                            </a>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <a data-fancybox href="{{route('imagecache',['visualizardoc', $usuario->image_selfie])}}">
                                                <img {{--class="img-circle"--}}
                                                     src="{{ route('imagecache',['fotoclube', $usuario->image_selfie]) }}"
                                                     alt="{{$usuario->image_selfie }}">
                                            </a>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label style="padding-right: 25px">
                                                <input type="radio" value="1" name="selfie_aprovacao" class="flat-red" {{ old('selfie_aprovacao', 2)  == 1 ? 'checked' : '' }}>
                                                Aprovar
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="selfie_aprovacao" class="flat-red" {{ old('selfie_aprovacao', 2)  == 0 ? 'checked' : '' }}>
                                                Recusar
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="selfie_aprovacao" class="flat-red" {{ old('selfie_aprovacao', 2)  == 2 ? 'checked' : '' }}>
                                                Em análise
                                            </label>
                                        </div>
                                        <div class="form-group col-xs-12 hidden" id="motivo_selfie_aprovacao">
                                            <label for="motivo_selfie_aprovacao">Motivo</label>
                                            <textarea name="motivo_selfie_aprovacao" cols="20" class="form-control" placeholder="Motivo pelo qual o documento foi recusado" rows="3">{{ old('motivo_selfie_aprovacao') }}</textarea>

                                            {{--<input type="text" name="motivo_selfie_aprovacao" value="{{ old('motivo_selfie_aprovacao') }}" class="form-control" placeholder="Motivo pelo qual o documento foi recusado">--}}
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        @endif

                        @if($usuario->status_comprovante_endereco == 'em_analise')
                            <div class="col-md-4">
                                <!-- general form elements -->
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><i class="fa fa-street-view"></i> Comprovante de Endereço</h3><br>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <div class="box-body" id="endereco">
                                        <div class="form-group col-xs-12">
                                            <label for="">Endereço: </label><span> {{$usuario->getRelation('endereco')->logradouro}}, {{$usuario->getRelation('endereco')->numero}}</span><br>
                                            <label for="">Bairro: </label><span> {{$usuario->getRelation('endereco')->bairro}}</span><br>
                                            <label for="">Cidade: </label><span> {{$usuario->getRelation('endereco')->cidade}} - {{$usuario->getRelation('endereco')->estado}}</span><br>
                                            <label for="">CEP: </label><span> {{$usuario->getRelation('endereco')->cep}}</span><br>
                                            @if($usuario->getRelation('endereco')->complemento != '')
                                                <label for="">Complemento: </label><span> {{$usuario->getRelation('endereco')->complemento}}</span><br>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-12">
                                            @if(getExtensaoDocumento($usuario->image_comprovante_endereco) == 'pdf')
                                                <a target="_blank" class="btn btn-warning" href="{{route('documentos.associado.ver.comprovante', [$usuario->id, 1])}}">
                                                    <span class="fa fa-file-pdf-o text-red"> </span>
                                                    Visualizar Comprovante
                                                </a>
                                            @else
                                                <a data-fancybox href="{{route('imagecache',['visualizardoc', $usuario->image_comprovante_endereco])}}">
                                                    <img {{--class="img-circle"--}}
                                                         src="{{ route('imagecache',['fotoclube', $usuario->image_comprovante_endereco]) }}"
                                                         alt="{{$usuario->image_comprovante_endereco }}">
                                                </a>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label style="padding-right: 25px">
                                                <input type="radio" value="1" name="endereco_aprovacao" class="flat-red" {{ old('endereco_aprovacao', 2)  == 1 ? 'checked' : '' }}>
                                                Aprovar
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="endereco_aprovacao" class="flat-red" {{ old('endereco_aprovacao', 2)  == 0 ? 'checked' : '' }}>
                                                Recusar
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="endereco_aprovacao" class="flat-red" {{ old('endereco_aprovacao', 2)  == 2 ? 'checked' : '' }}>
                                                Em análise
                                            </label>
                                        </div>
                                        <div class="form-group col-xs-12 hidden" id="motivo_endereco_aprovacao">
                                            <label for="motivo_endereco_aprovacao">Motivo</label>
                                            <textarea name="motivo_endereco_aprovacao" cols="20" class="form-control" placeholder="Motivo pelo qual o documento foi recusado" rows="3">{{ old('motivo_endereco_aprovacao') }}</textarea>

                                            {{--<input type="text" name="motivo_endereco_aprovacao" value="{{ old('motivo_endereco_aprovacao') }}" class="form-control" placeholder="Motivo pelo qual o documento foi recusado">--}}
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        @endif
                    @endif

                    @if($responsavel != null)
                        <div class="col-md-12">
                            <h3>
                                <span class="text-red">Dados do Responsável</span>
                            </h3>
                        </div>
                        @if($responsavel->status_documento == 'em_analise')
                            <div class="col-md-4">
                                <!-- general form elements -->
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><i class="glyphicon glyphicon-file text-orange"></i>Documento Responsável</h3><br>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <div class="box-body" id="identidade">
                                        <div class="form-group col-xs-12">
                                            <label for="">Nome Responsável: </label><span> {{$responsavel->nome}}</span><br>
                                            <label for="">CPF: </label><span> {{$responsavel->cpf}}</span><br>
                                            @if($responsavel->rg != '')
                                                <label for="">RG: </label><span> {{$responsavel->rg}}</span><br>
                                            @endif
                                            <label for="">Data Nascimento: </label><span> {{$responsavel->nascimento}}</span><br>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label for="">Frente </label>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label for="">Verso </label>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            @if($responsavel->documento != null || $responsavel->documento != '')
                                                <a data-fancybox href="{{route('imagecache',['visualizardoc', $responsavel->documento])}}">
                                                    <img {{--class="img-circle"--}}
                                                         src="{{ route('imagecache',['fotoclube', $responsavel->documento]) }}"
                                                         alt="{{$responsavel->documento }}">
                                                </a>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-6">
                                            @if($responsavel->documento_verso != null || $responsavel->documento_verso != '')
                                                <a data-fancybox href="{{route('imagecache',['visualizardoc', $responsavel->documento_verso])}}">
                                                    <img {{--class="img-circle"--}}
                                                         src="{{ route('imagecache',['fotoclube', $responsavel->documento_verso]) }}"
                                                         alt="{{$responsavel->documento_verso }}">
                                                </a>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label style="padding-right: 25px">
                                                <input type="radio" value="1" name="responsavel_documento_aprovacao" class="flat-red" {{ old('responsavel_documento_aprovacao', 2)  == 1 ? 'checked' : '' }}>
                                                Aprovar
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="responsavel_documento_aprovacao" class="flat-red" {{ old('responsavel_documento_aprovacao', 2)  == 0 ? 'checked' : '' }}>
                                                Recusar
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="responsavel_documento_aprovacao" class="flat-red" {{ old('responsavel_documento_aprovacao', 2)  == 2 ? 'checked' : '' }}>
                                                Em análise
                                            </label>
                                        </div>
                                        <div class="form-group col-xs-12 hidden" id="motivo_responsavel_documento_aprovacao">
                                            <label for="motivo_responsavel_documento_aprovacao">Motivo</label>
                                            <textarea name="motivo_responsavel_documento_aprovacao" cols="20" class="form-control" placeholder="Motivo pelo qual o documento foi recusado" rows="3">{{ old('motivo_responsavel_documento_aprovacao') }}</textarea>

                                            {{--<input type="text" name="motivo_responsavel_documento_aprovacao" value="{{ old('motivo_responsavel_documento_aprovacao') }}" class="form-control" placeholder="Motivo pelo qual o documento foi recusado">--}}
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        @endif

                        @if($responsavel->status_selfie == 'em_analise')
                            <div class="col-md-4">
                                <!-- general form elements -->
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><i class="fa fa-user"></i> Selfie</h3><br>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <div class="box-body" id="selfie">
                                        <div class="form-group col-xs-6">
                                            <label for="">Documento </label>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <label for="">Selfie </label>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <a data-fancybox href="{{route('imagecache',['visualizardoc', $responsavel->usuario->image_cpf])}}">
                                                <img {{--class="img-circle"--}}
                                                     src="{{ route('imagecache',['fotoclube', $responsavel->usuario->image_cpf]) }}"
                                                     alt="{{$responsavel->usuario->image_cpf }}">
                                            </a>
                                        </div>
                                        <div class="form-group col-xs-6">
                                            <a data-fancybox href="{{route('imagecache',['visualizardoc', $responsavel->selfie])}}">
                                                <img {{--class="img-circle"--}}
                                                     src="{{ route('imagecache',['fotoclube', $responsavel->selfie]) }}"
                                                     alt="{{$responsavel->selfie }}">
                                            </a>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label style="padding-right: 25px">
                                                <input type="radio" value="1" name="responsavel_selfie_aprovacao" class="flat-red" {{ old('responsavel_selfie_aprovacao', 2)  == 1 ? 'checked' : '' }}>
                                                Aprovar
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="responsavel_selfie_aprovacao" class="flat-red" {{ old('responsavel_selfie_aprovacao', 2)  == 0 ? 'checked' : '' }}>
                                                Recusar
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="responsavel_selfie_aprovacao" class="flat-red" {{ old('responsavel_selfie_aprovacao', 2)  == 2 ? 'checked' : '' }}>
                                                Em análise
                                            </label>
                                        </div>
                                        <div class="form-group col-xs-12 hidden" id="motivo_responsavel_selfie_aprovacao">
                                            <label for="motivo_responsavel_selfie_aprovacao">Motivo</label>
                                            <textarea name="motivo_responsavel_selfie_aprovacao" cols="20" class="form-control" placeholder="Motivo pelo qual o documento foi recusado" rows="3">{{ old('motivo_responsavel_selfie_aprovacao') }}</textarea>

                                            {{--<input type="text" name="motivo_responsavel_selfie_aprovacao" value="{{ old('motivo_responsavel_selfie_aprovacao') }}" class="form-control" placeholder="Motivo pelo qual o documento foi recusado">--}}
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        @endif

                        @if($responsavel->status_documento_representacao == 'em_analise')
                            <div class="col-md-4">
                                <!-- general form elements -->
                                <div class="box box-warning">
                                    <div class="box-header with-border">
                                        <h3 class="box-title"><i class="fa fa-street-view"></i> Documento de Representação Legal</h3><br>
                                    </div>
                                    <!-- /.box-header -->
                                    <!-- form start -->
                                    <div class="box-body" id="endereco">
                                        <div class="form-group col-xs-12">
                                            <label for="">Documento </label>
                                        </div>
                                        <div class="form-group col-xs-12">
                                            @if(getExtensaoDocumento($responsavel->documento_representacao) == 'pdf')
                                                <a target="_blank" class="btn btn-warning" href="{{route('documentos.associado.ver.comprovante', [$responsavel->id, 2])}}">
                                                    <span class="fa fa-file-pdf-o text-red"> </span>
                                                    Visualizar Comprovante
                                                </a>
                                            @else
                                                <a data-fancybox href="{{route('imagecache',['visualizardoc', $responsavel->documento_representacao])}}">
                                                    <img {{--class="img-circle"--}}
                                                         src="{{ route('imagecache',['fotoclube', $responsavel->documento_representacao]) }}"
                                                         alt="{{$responsavel->documento_representacao }}">
                                                </a>
                                            @endif
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label style="padding-right: 25px">
                                                <input type="radio" value="1" name="responsavel_documento_legal_aprovacao" class="flat-red" {{ old('responsavel_documento_legal_aprovacao', 2)  == 1 ? 'checked' : '' }}>
                                                Aprovar
                                            </label>
                                            <label>
                                                <input type="radio" value="0" name="responsavel_documento_legal_aprovacao" class="flat-red" {{ old('responsavel_documento_legal_aprovacao', 2)  == 0 ? 'checked' : '' }}>
                                                Recusar
                                            </label>
                                            <label>
                                                <input type="radio" value="2" name="responsavel_documento_legal_aprovacao" class="flat-red" {{ old('responsavel_documento_legal_aprovacao', 2)  == 2 ? 'checked' : '' }}>
                                                Em análise
                                            </label>
                                        </div>
                                        <div class="form-group col-xs-12 hidden" id="motivo_responsavel_documento_legal_aprovacao">
                                            <label for="motivo_responsavel_documento_legal_aprovacao">Motivo</label>
                                            <textarea name="motivo_responsavel_documento_legal_aprovacao" cols="20" class="form-control" placeholder="Motivo pelo qual o documento foi recusado" rows="3">{{ old('motivo_responsavel_documento_legal_aprovacao') }}</textarea>

                                            {{--<input type="text" name="motivo_responsavel_documento_legal_aprovacao" value="{{ old('motivo_responsavel_documento_legal_aprovacao') }}" class="form-control" placeholder="Motivo pelo qual o documento foi recusado">--}}
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                        @endif
                    @endif

                    @if($usuario != null)
                        @if($usuario->getRelation('dadosBancarios')->where('status_comprovante', 'em_analise')->count() > 0)
                            <div class="col-md-12">
                                <h3>
                                    <span class="text-red">Dados Bancários</span>
                                </h3>
                            </div>
                            @foreach($usuario->getRelation('dadosBancarios')->where('status_comprovante', 'em_analise') as $conta)
                                <div class="col-md-4">
                                    <div class="box box-default">
                                        <div class="box-body box-profile">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <h2 class="page-header" style="margin-top: 0; margin-bottom: 5px;">
                                                        <i class="fa fa-bank"></i> {{ $conta->bancoReferencia->nome }}
                                                    </h2>
                                                </div>
                                            </div>
                                            <div class="row" style="padding-bottom: 5px; border-bottom: 1px solid #eee; margin-bottom: 10px;">
                                                <div class="col-xs-4 col-sm-4">
                                                    <strong>Banco</strong><br>{{ $conta->bancoReferencia->codigo }}
                                                </div>
                                                <div class="col-xs-4 col-sm-4">
                                                    <strong>Agência</strong><br>{{ $conta->agencia }}-{{ $conta->agencia_digito }}
                                                </div>
                                                <div class="col-xs-4 col-sm-4">
                                                    <strong>Conta</strong><br>{{ $conta->conta }}-{{ $conta->conta_digito }}
                                                </div>
                                            </div>
                                            <div class="form-group col-xs-12">
                                                <a data-fancybox href="{{route('imagecache',['visualizardoc', $conta->imagem_comprovante])}}">
                                                    <img {{--class="img-circle"--}}
                                                         src="{{ route('imagecache',['fotoclube', $conta->imagem_comprovante]) }}"
                                                         alt="{{$conta->imagem_comprovante }}">
                                                </a>
                                            </div>
                                            <div class="form-group col-xs-12">
                                                <label style="padding-right: 25px">
                                                    <input type="radio" value="1" name="banco_aprovacao[{{$conta->id}}]" class="flat-red">
                                                    Aprovar
                                                </label>
                                                <label>
                                                    <input type="radio" value="0" name="banco_aprovacao[{{$conta->id}}]" class="flat-red">
                                                    Recusar
                                                </label>
                                                <label>
                                                    <input type="radio" value="2" name="banco_aprovacao[{{$conta->id}}]" class="flat-red" checked>
                                                    Em análise
                                                </label>
                                            </div>
                                            <div class="form-group col-xs-12 hidden" id="motivo_banco_aprovacao_{{$conta->id}}">
                                                <label for="motivo_banco_aprovacao_{{$conta->id}}">Motivo</label>
                                                <textarea name="motivo_banco_aprovacao_{{$conta->id}}" cols="20" class="form-control" placeholder="Motivo pelo qual o documento foi recusado" rows="3">{{ old('motivo_banco_aprovacao_'.$conta->id) }}</textarea>

                                                {{--<input type="text" name="motivo_banco_aprovacao_{{$conta->id}}" value="" class="form-control" placeholder="Motivo pelo qual o documento foi recusado">--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endif
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-check"></i> <b>Salvar</b></button>
                    </div>
                    <div class="col-md-3">
                        {{--<button data-toggle="modal" data-target="#conta" class="btn btn-danger btn-block"><i class="fa fa-remove"></i> <b>Voltar</b></button>--}}
                        <a href="{{ route('documentos.associado.aguardando') }}" class="btn btn-danger btn-block"><i class="fa fa-mail-reply-all"></i><b> Voltar</b></a>
                    </div>
                </div>
            </div>
        </form>
    </section>
@endsection

@section('style')
    <link rel="stylesheet" href="/plugins/iCheck/square/red.css">
    <link rel="stylesheet" type="text/css" href="/plugins/fancybox/jquery.fancybox.min.css">
@endsection

@section('script')
    <script src="/plugins/iCheck/icheck.min.js"></script>
    {{--<script src="/plugins/fancybox/jquery-3.2.1.min.js"></script>--}}
    <script src="/plugins/fancybox/jquery.fancybox.min.js"></script>

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });

            $('input[type=radio]').on("ifChecked", function (event) {
                /*val = event.target.value;
                alert(val);*/
                var valor = $(this).prop('value');
                var name = 'motivo_' + $(this).prop('name');
                if(name.includes('motivo_banco_aprovacao')){
                    name = name.replace('[', '_').replace(']','');
                }
                if(valor == 0){
                    $('#' + name).removeClass('hidden');
                    $('input[name="' + name + '"]').attr('required','required');
                }else{
                    $('#' + name).addClass('hidden');
                    $('input[name="' + name + '"]').removeAttr('required');
                }
            });
        });
    </script>
@endsection