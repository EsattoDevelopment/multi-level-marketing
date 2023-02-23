@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            <a href="{{ route('user.rede-binaria') }}">Voltar para inicio da rede</a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li> >
            <li>Minha rede</li> >
            <li class="active">Rede Binária</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div @if(Auth::user()->id != $usuario->id) id="voltar-clube" @endif class="">
                @if(Auth::user()->id != $usuario->id)
                    <form id="form-voltar-clube" action="{{ route('user.rede-binaria') }}" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="{{ $rede->user_id }}">
                    </form>
            @endif
            <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-yellow">
                        <h3 class="widget-user-username">{{ $usuario->username }} - {{ $usuario->name }}</h3>
                        <h5 class="widget-user-desc">{{ $usuario->titulo->name }}</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="@if($usuario->image){{ route('imagecache', ['fotoclube', 'user/'.$usuario->image]) }}@else{{ route('imagecache', ['fotoclube', 'user-img.jpg']) }}@endif" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">R. Esquerda</h5>
                                    <span class="description-text">@if($usuario->extratoBinarioSaldo()){{ $usuario->extratoBinarioSaldo()->saldo_esquerda }}@else 0 @endif</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">Qualificado</h5>
                                    <span class="description-text">{{ $usuario->qualificado_string }}</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">R. Direita</h5>
                                    <span class="description-text">@if($usuario->extratoBinarioSaldo()){{ $usuario->extratoBinarioSaldo()->saldo_direita }}@else 0 @endif</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
        </div>


        <div class="row">
            {{--Equipe esquerda--}}
            @if($usuario->redeBinarioSelect)
                @if($usuario->redeBinarioSelect->userEsquerda)
                    <div id="hemisferio-sul" class="col-sm-6 col-md-6 col-lg-4 pull-left">
                    <form id="form-hemisferio-sul" action="{{ route('user.rede-binaria') }}" method="post">
                        {!! csrf_field() !!}
                        <input type="hidden" name="id" value="{{ $usuario->redeBinarioSelect->userEsquerda->id }}">
                    </form>
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user">
                        <!-- Add the bg color to the header using any of the bg-* classes -->

                        <div class="widget-user-header bg-orange" >
                            <h3 class="widget-user-username">{{ $usuario->redeBinarioSelect->userEsquerda->username }} - {{ $usuario->redeBinarioSelect->userEsquerda->name }}</h3>
                            <h5 class="widget-user-desc">{{ $usuario->redeBinarioSelect->userEsquerda->titulo->name }}</h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle" src="@if($usuario->redeBinarioSelect->userEsquerda->image){{ route('imagecache', ['fotoclube', 'user/'.$usuario->redeBinarioSelect->userEsquerda->image]) }}@else{{ route('imagecache', ['fotoclube', 'user-img.jpg']) }}@endif" alt="User Avatar">
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">R. Esquerda</h5>
                                        <span class="description-text">@if($usuario->redeBinarioSelect->userEsquerda->extratoBinarioSaldo()){{ $usuario->redeBinarioSelect->userEsquerda->extratoBinarioSaldo()->saldo_esquerda }}@else {{ '0' }} @endif</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">Qualificado</h5>
                                        <span class="description-text">{{ $usuario->redeBinarioSelect->userEsquerda->qualificado_string }}</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">R. Direita</h5>
                                        <span class="description-text">@if($usuario->redeBinarioSelect->userEsquerda->extratoBinarioSaldo()){{ $usuario->redeBinarioSelect->userEsquerda->extratoBinarioSaldo()->saldo_direita }}@else {{ '0' }} @endif</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.widget-user -->
                </div>
                @else
                    <div class="col-md-4 pull-left">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-user">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-black" >
                            <h3 class="widget-user-username">Posição vazia</h3>
                            <h5 class="widget-user-desc">Convide uma amigo</h5>
                        </div>
                        <div class="widget-user-image">
                            <img class="img-circle" src="{{ route('imagecache', ['fotoclube', 'user-img.jpg']) }}" alt="User Avatar">
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">R. Esquerda</h5>
                                        <span class="description-text">0</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 border-right">
                                    <div class="description-block">
                                        <h5 class="description-header">Qualificado</h5>
                                        <span class="description-text">Não</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4">
                                    <div class="description-block">
                                        <h5 class="description-header">R. Direita</h5>
                                        <span class="description-text">0</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.widget-user -->
                </div>
                @endif
            @endif

            {{--Equipe direita--}}
            @if($usuario->redeBinarioSelect)
                @if($usuario->redeBinarioSelect->userDireita)
                    <div id="hemisferio-norte" class="col-sm-6 col-md-6 col-lg-4 pull-right">
                        <form id="form-hemisferio-norte" action="{{ route('user.rede-binaria') }}" method="post">
                            {!! csrf_field() !!}
                            <input type="hidden" name="id" value="{{ $usuario->redeBinarioSelect->userDireita->id }}">
                        </form>
                        <!-- Widget: user widget style 1 -->
                        <div class="box box-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-primary">
                                <h3 class="widget-user-username">{{ $usuario->redeBinarioSelect->userDireita->username }} - {{ $usuario->redeBinarioSelect->userDireita->name }}</h3>
                                <h5 class="widget-user-desc">{{ $usuario->redeBinarioSelect->userDireita->titulo->name }}</h5>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle" src="@if($usuario->redeBinarioSelect->userDireita->image){{ route('imagecache', ['fotoclube', 'user/'.$usuario->redeBinarioSelect->userDireita->image]) }}@else{{ route('imagecache', ['fotoclube', 'user-img.jpg']) }}@endif" alt="User Avatar">
                            </div>
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header">R. Esquerda</h5>
                                            <span class="description-text">@if($usuario->redeBinarioSelect->userDireita->extratoBinarioSaldo()){{ $usuario->redeBinarioSelect->userDireita->extratoBinarioSaldo()->saldo_esquerda }} @else {{ '0' }} @endif</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header">Qualificado</h5>
                                            <span class="description-text">{{ $usuario->redeBinarioSelect->userDireita->qualificado_string }}</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4">
                                        <div class="description-block">
                                            <h5 class="description-header">R. Direita</h5>
                                            <span class="description-text">@if($usuario->redeBinarioSelect->userDireita->extratoBinarioSaldo()){{ $usuario->redeBinarioSelect->userDireita->extratoBinarioSaldo()->saldo_direita }} @else {{ '0' }} @endif</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>
                        <!-- /.widget-user -->
                    </div>
                @else
                    <div class="col-md-4 pull-right">
                        <!-- Widget: user widget style 1 -->
                        <div class="box box-widget widget-user">
                            <!-- Add the bg color to the header using any of the bg-* classes -->
                            <div class="widget-user-header bg-black">
                                <h3 class="widget-user-username">Posição vazia</h3>
                                <h5 class="widget-user-desc">Convide uma amigo</h5>
                            </div>
                            <div class="widget-user-image">
                                <img class="img-circle" src="{{ route('imagecache', ['fotoclube', 'user-img.jpg']) }}" alt="User Avatar">
                            </div>
                            <div class="box-footer">
                                <div class="row">
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header">Pontos sul</h5>
                                            <span class="description-text">0</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4 border-right">
                                        <div class="description-block">
                                            <h5 class="description-header">Qualificado</h5>
                                            <span class="description-text">Não</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-4">
                                        <div class="description-block">
                                            <h5 class="description-header">Pontos norte</h5>
                                            <span class="description-text">0</span>
                                        </div>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </div>
                        </div>
                        <!-- /.widget-user -->
                    </div>
                @endif
            @endif
        </div>

        <!-- /.row -->
    </section>
@endsection

@section('style')
    <style>
        section.content{
            background: url('{{ asset('images/fundo-clube4.jpg') }}');
            background-repeat: no-repeat;
            max-width: 1580px;
            background-size: cover;
        }

        .widget-user .widget-user-username{
            font-weight: 600 !important;
            text-shadow: #000 2px 1px 2px !important;
        }

        .widget-user .widget-user-desc{
            font-weight: 600 !important;
            text-shadow: #000 2px 1px 2px !important;
        }

        #hemisferio-norte, #hemisferio-sul, #voltar-clube{
            cursor: pointer;
        }
    </style>
@endsection

@section('script')
    <script>
        $(function(){
            $('#hemisferio-norte').click(function(){
                $('#form-hemisferio-norte').submit();
            });
            @if(Auth::user()->id != $usuario->id)
           $('#voltar-clube').click(function(){
                $('#form-voltar-clube').submit();
            });
            @endif
            $('#hemisferio-sul').click(function(){
                $('#form-hemisferio-sul').submit();
            });
        });
    </script>
@endsection