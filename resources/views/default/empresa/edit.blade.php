@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cadastro Empresa</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{ route('empresa.update', 1) }}" method="post" enctype="multipart/form-data">
                        {!! csrf_field() !!}
                        <div class="box-body">
                            {{--Dados da empresa--}}
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title col-md-12">
                                        <i class="fa fa-industry text-primary"></i> Dados Empresa
                                    </h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button type="button" class="btn btn-primary btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                            <i class="fa fa-minus"></i></button>
                                    </div>
                                    <!-- /. tools -->
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <div class="box-body">
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Razão Social</label>
                                        <input type="text" name="razao_social" value="{{ old('razao_social', $dados->razao_social) }}" class="form-control" placeholder="Razão Social">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Nome fantasia</label>
                                        <input type="text" name="nome_fantasia" value="{{ old('nome_fantasia', $dados->nome_fantasia) }}" class="form-control"  placeholder="Nome fantasia">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Site</label>
                                        <input type="text" name="site" value="{{ old('site', $dados->site) }}" class="form-control"  placeholder="Site">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">CNPJ</label>
                                        <input type="text" name="cnpj" value="{{ old('cnpj', $dados->cnpj) }}" class="form-control"  placeholder="CNPJ">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Inscrição estadual</label>
                                        <input type="text" name="inscricao_estadual" value="{{ old('inscricao_estadual', $dados->inscricao_estadual) }}" class="form-control"  placeholder="Inscrição estadual">
                                    </div>
                                </div>
                            </div>

                            {{--Contatos da empresa--}}
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title col-md-12">
                                        <i class="fa fa-industry text-primary"></i> Contatos empresa
                                    </h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button type="button" class="btn btn-primary btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                            <i class="fa fa-minus"></i></button>
                                    </div>
                                    <!-- /. tools -->
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <div class="box-body">
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Nome contato</label>
                                        <input type="text" name="nome_contato" value="{{ old('nome_contato', $dados->nome_contato) }}" class="form-control" placeholder="Nome contato">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">CPF</label>
                                        <input type="text" name="cpf_contato" value="{{ old('cpf_contato', $dados->cpf_contato) }}" class="form-control"  placeholder="CPF">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">RG</label>
                                        <input type="text" name="rg_contato" value="{{ old('rg_contato', $dados->rg_contato) }}" class="form-control"  placeholder="RG">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Telefone</label>
                                        <input type="text" name="telefone_contato" value="{{ old('telefone_contato', $dados->telefone_contato) }}" class="form-control"  placeholder="Telefone">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">E-mail contato</label>
                                        <input type="text" name="email_contato" value="{{ old('email_contato', $dados->email_contato) }}" class="form-control"  placeholder="E-mail contato">
                                    </div>
                                </div>
                            </div>

                            {{--Endereço empresa--}}
                            <div class="box box-primary">
                                <div class="box-header">
                                    <h3 class="box-title col-md-12">
                                        <i class="fa fa-industry text-primary"></i> Endreços empresa
                                    </h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button type="button" class="btn btn-primary btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                            <i class="fa fa-minus"></i></button>
                                    </div>
                                    <!-- /. tools -->
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <div class="box-body">
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Logradouro</label>
                                        <input type="text" name="logradouro" value="{{ old('logradouro', $dados->logradouro) }}" class="form-control" placeholder="Logradouro">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Numero</label>
                                        <input type="text" name="numero" value="{{ old('numero', $dados->numero) }}" class="form-control"  placeholder="Numero">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Complemento</label>
                                        <input type="text" name="complemento" value="{{ old('complemento', $dados->complemento) }}" class="form-control"  placeholder="Complemento">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Bairro</label>
                                        <input type="text" name="bairro" value="{{ old('bairro', $dados->bairro) }}" class="form-control"  placeholder="Bairro">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Cidade</label>
                                        <input type="text" name="cidade" value="{{ old('cidade', $dados->cidade) }}" class="form-control"  placeholder="Cidade">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">CEP</label>
                                        <input type="text" name="cep" value="{{ old('cep', $dados->cep) }}" class="form-control"  placeholder="CEP">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Estado</label>
                                        <input type="text" name="uf" value="{{ old('uf', $dados->uf) }}" class="form-control"  placeholder="Estado">
                                    </div>
                                </div>
                            </div>

                            {{--Rede social--}}
                            <div class="box box-success">
                                <div class="box-header">
                                    <h3 class="box-title col-md-12">
                                        <i class="fa fa-file text-primary"></i> Termo de aceite
                                    </h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button type="button" class="btn btn-primary btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                            <i class="fa fa-minus"></i></button>
                                    </div>
                                    <!-- /. tools -->
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <div class="box-body">
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Nome termo</label>
                                        <input type="text" name="nome_termo_inicial" value="{{ old('nome_termo_inicial', $dados->nome_termo_inicial) }}" class="form-control" placeholder="Nome termo">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="image">Arquivo</label>
                                        <input type="file" id="termo_inicial" name="termo_inicial">
                                    </div>
                                    @if($dados->termo_inicial)
                                        <div class="form-group col-xs-12 col-md-8">
                                            <a class="btn btn-primary btn-block" target="_blank" href="{{ route('download.download', $dados->termo_inicial) }}"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Ver arquivo</a>
                                        </div>

                                        <div class="form-group col-xs-12 col-md-4">
                                            <input type="checkbox" name="apagar_termo_inicial" value="true">
                                            <label for="">Apagar arquivo</label>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{--Arquivo de adesão--}}
                            <div class="box box-warning">
                                <div class="box-header">
                                    <h3 class="box-title col-md-12">
                                        <i class="fa fa-link text-primary"></i> Links de rede social
                                    </h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button type="button" class="btn btn-primary btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                            <i class="fa fa-minus"></i></button>
                                    </div>
                                    <!-- /. tools -->
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <div class="box-body">
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Link Facebook</label>
                                        <input type="text" name="link_facebook" value="{{ old('link_facebook', $dados->link_facebook) }}" class="form-control" placeholder="Link Facebook">
                                    </div>
                                    <div class="form-group col-xs-12">
                                        <label for="exampleInputEmail1">Link Instagram</label>
                                        <input type="text" name="link_instagram" value="{{ old('link_instagram', $dados->link_instagram) }}" class="form-control" placeholder="Link Instagram">
                                    </div>
                                </div>
                            </div>

                            {{--Imagens--}}
                            <div class="box box-danger">
                                <div class="box-header">
                                    <h3 class="box-title col-md-12">
                                        <i class="fa fa-file-image-o text-primary"></i> Imagens/Cor
                                    </h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button type="button" class="btn btn-primary btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                            <i class="fa fa-minus"></i></button>
                                    </div>
                                    <!-- /. tools -->
                                </div>
                                <!-- /.box-header -->
                                <!-- form start -->
                                <div class="box-body">

                                    <div class="form-group col-md-12">
                                        <label>Cores</label>
                                        <select class="form-control select2" name="cor" data-placeholder="Selecione uma cor" style="width: 100%;">
                                            @foreach($cores as $key => $cor)
                                                <option @if(old('cor', $dados->cor) == $key) selected @endif value="{{ $key }}">{{ $cor }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="image">Plano de fundo</label>
                                        <input type="file" id="background" name="background">
                                    </div>
                                    @if($dados->background)
                                        <div class="form-group col-xs-12 col-md-8">
                                            <a class="btn btn-primary btn-block" target="_blank" href="{{ route('imagecache', ['visualizar', 'empresa/'.$dados->background]) }}"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Plano de fundo</a>
                                        </div>

                                        <div class="form-group col-xs-12 col-md-4">
                                            <input type="checkbox" name="apagar_background" value="true">
                                            <label for="">Apagar Plano de fundo</label>
                                        </div>
                                    @endif

                                    <div class="form-group col-md-6">
                                        <label for="background_manutencao">Plano de fundo página de manutenção</label>
                                        <input type="file" id="background_manutencao" name="background_manutencao">
                                    </div>
                                    @if($dados->background_manutencao)
                                        <div class="form-group col-xs-12 col-md-8">
                                            <a class="btn btn-primary btn-block" target="_blank" href="{{ route('imagecache', ['visualizar', 'empresa/'.$dados->background_manutencao]) }}"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Plano de fundo</a>
                                        </div>

                                        <div class="form-group col-xs-12 col-md-4">
                                            <input type="checkbox" name="apagar_background_manutencao" value="true">
                                            <label for="">Apagar Plano de fundo manutenção</label>
                                        </div>
                                    @endif

                                    <div class="form-group col-md-6">
                                        <label for="image">Logo</label>
                                        <input type="file" id="logo" name="logo">
                                    </div>
                                    @if($dados->logo)
                                        <div class="form-group col-xs-12 col-md-8">
                                            <a class="btn btn-primary btn-block" target="_blank" href="{{ route('imagecache', ['visualizar', 'empresa/'.$dados->logo]) }}"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Logo</a>
                                        </div>

                                        <div class="form-group col-xs-12 col-md-4">
                                            <input type="checkbox" name="apagar_logo" value="true">
                                            <label for="">Apagar logo</label>
                                        </div>
                                    @endif

                                    <div class="form-group col-md-6">
                                        <label for="image">Favicon</label>
                                        <input type="file" id="favicon" name="favicon">
                                    </div>
                                    @if($dados->favicon)
                                        <div class="form-group col-xs-12 col-md-4">
                                            <a class="btn btn-primary btn-block" target="_blank" href="{{ route('imagecache', ['visualizar', 'empresa/'.$dados->favicon]) }}"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Favicon</a>
                                        </div>

                                        <div class="form-group col-xs-12 col-md-4">
                                            <input type="checkbox" name="apagar_favicon" value="true">
                                            <label for="">Apagar favicon</label>
                                        </div>
                                    @endif

                                    <div class="form-group col-md-6">
                                        <label for="image">Logo Flutuante</label>
                                        <input type="file" id="logo_flutuante" name="logo_flutuante">
                                    </div>
                                    @if($dados->logo_flutuante)
                                        <div class="form-group col-xs-12 col-md-8">
                                            <a class="btn btn-primary btn-block" target="_blank" href="{{ route('imagecache', ['visualizar', 'empresa/'.$dados->logo_flutuante]) }}"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Logo Flutuante</a>
                                        </div>

                                        <div class="form-group col-xs-12 col-md-4">
                                            <input type="checkbox" name="apagar_logo_flutuante" value="true">
                                            <label for="">Apagar logo flutuante</label>
                                        </div>
                                    @endif

                                    <div class="form-group col-md-6">
                                        <label for="image">Logo e-mail</label>
                                        <input type="file" id="logo_email" name="logo_email">
                                    </div>
                                    @if($dados->logo_email)
                                        <div class="form-group col-xs-12 col-md-8">
                                            <a class="btn btn-primary btn-block" target="_blank" href="{{ route('imagecache', ['visualizar', 'empresa/'.$dados->logo_email]) }}"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Logo e-mail</a>
                                        </div>

                                        <div class="form-group col-xs-12 col-md-4">
                                            <input type="checkbox" name="apagar_logo_email" value="true">
                                            <label for="">Apagar Logo E-mail</label>
                                        </div>
                                    @endif
                                </div>
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