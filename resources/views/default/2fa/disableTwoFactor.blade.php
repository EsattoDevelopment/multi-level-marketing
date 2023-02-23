@extends('default.layout.main')

@section('content')
    <section class="content-header">
        <h1>
            Autenticação de 2 fatores
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Autenticação de 2 fatores</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                @if (Session::has('flash_notification.message'))
                    @if (Session::has('flash_notification.overlay'))
                        @include('flash::modal', ['modalClass' => 'flash-modal', 'title' => Session::get('flash_notification.title'), 'body' => Session::get('flash_notification.message')])
                    @else
                        <div class="alert alert-{{ Session::get('flash_notification.level') }}">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>

                            {!! Session::get('flash_notification.message') !!}
                        </div>
                    @endif
                @endif

                @if($errors->has())
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{!!$error!!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="nav-tabs-custom">
                    <div class="tab-content">
                        <div class="panel-body" style="text-align: center;">
                            <p><strong>1.</strong> Para desativar a autentiação de 2 fatores, abra o aplicativo <strong>Google Authenticator</strong> e informe abaixo o código gerado:
                            <form name="form" method="POST" action="{{ route('2fa.disable') }}" class="col-xs-12 col-md-6" style="float: none; margin: 0 auto;">
                                {!! csrf_field() !!}
                                <div class="input-group{{ $errors->has('code') ? ' has-error' : '' }}">
                                    <input type="number" class="form-control" name="code">
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-sign-in"></i> Enviar
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <br/>
                            <a href="{{ route('dados-usuario.seguranca') }}" class="btn btn-warning btn-sm">Não quero desativar agora, voltar.</a>
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
    </section>
@endsection
