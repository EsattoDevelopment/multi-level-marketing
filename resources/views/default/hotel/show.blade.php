@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Hotel # {{ $dados->id }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li> >
            <li>Minha rede</li> >
            <li class="active">Hotel</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="andares">
            <div id="1andar" class="linha" style="margin-top: 55px; margin-left: -26px">
                <img src="@if($dados->getRelation('usuario')->image){{ route('imagecache', ['fotohotel', 'user/'.$dados->getRelation('usuario')->image]) }}@else{{ route('imagecache', ['fotohotel', 'user-img.jpg']) }}@endif" alt="" data-toggle="tooltip" data-placement="bottom" title="{{ $dados->getRelation('usuario')->username }}">
            </div>
            @if(isset($quartos[1]))
                <div id="2-andar" class="linha" style="margin-top: 36px; margin-left: 46.3%; text-align: left;">
                    @if(isset($quartos[1][0]))
                        <img style="margin-right: 6px;"
                             src="@if($quartos[1][0]->image){{ route('imagecache', ['fotohotel', 'user/'.$quartos[1][0]->image]) }}@else{{ route('imagecache', ['fotohotel', 'user-img.jpg']) }}@endif"
                             alt=""
                             data-toggle="tooltip"
                             data-placement="bottom"
                             title="{{ $quartos[1][0]->username }}">
                    @endif

                    @if(isset($quartos[1][1]))
                        <img src="@if($quartos[1][1]->image){{ route('imagecache', ['fotohotel', 'user/'.$quartos[1][1]->image]) }}@else{{ route('imagecache', ['fotohotel', 'user-img.jpg']) }}@endif"
                             alt=""
                             data-toggle="tooltip"
                             data-placement="bottom"
                             title="{{ $quartos[1][1]->username }}">
                    @endif
                </div>
            @endif

            @if(isset($quartos[2]))
                <div id="3-andar" class="linha" style="margin-top: 35px; margin-left: 43.4%; text-align: left;">
                    @foreach($quartos[2] as $key => $usuario)
                        @if($key != 3)
                            <img style="margin-right: 6px;"
                                 src="@if($usuario->image){{ route('imagecache', ['fotohotel', 'user/'.$usuario->image]) }}@else{{ route('imagecache', ['fotohotel', 'user-img.jpg']) }}@endif"
                                 alt=""
                                 data-toggle="tooltip"
                                 data-placement="bottom"
                                 title="{{ $usuario->username }}">
                        @else
                            <img
                                    src="@if($usuario->image){{ route('imagecache', ['fotohotel', 'user/'.$usuario->image]) }}@else{{ route('imagecache', ['fotohotel', 'user-img.jpg']) }}@endif"
                                    alt=""
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="{{ $usuario->username }}">
                        @endif
                    @endforeach
                </div>
            @endif
            @if(isset($quartos[3]))
                <div id="4-andar" class="linha" style="margin-top: 35px; margin-left: 37.7%; text-align: left;">
                    @foreach($quartos[3] as $key => $usuario)
                        @if($key != 7)
                            <img style="margin-right: 6px;"
                                 src="@if($usuario->image){{ route('imagecache', ['fotohotel', 'user/'.$usuario->image]) }}@else{{ route('imagecache', ['fotohotel', 'user-img.jpg']) }}@endif"
                                 alt=""
                                 data-toggle="tooltip"
                                 data-placement="bottom"
                                 title="{{ $usuario->username }}">
                        @else
                            <img
                                    src="@if($usuario->image){{ route('imagecache', ['fotohotel', 'user/'.$usuario->image]) }}@else{{ route('imagecache', ['fotohotel', 'user-img.jpg']) }}@endif"
                                    alt=""
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="{{ $usuario->username }}">
                        @endif
                    @endforeach
                </div>
            @endif
            @if(isset($quartos[4]))
                <div id="5-andar" class="linha" style="margin-top: 40px; margin-left: 26.1%; text-align: left;">
                    @foreach($quartos[4] as $key => $usuario)
                        @if($key != 15)
                            <img style="margin-right: 6px;"
                                 src="@if($usuario->image){{ route('imagecache', ['fotohotel', 'user/'.$usuario->image]) }}@else{{ route('imagecache', ['fotohotel', 'user-img.jpg']) }}@endif"
                                 alt=""
                                 data-toggle="tooltip"
                                 data-placement="bottom"
                                 title="{{ $usuario->username }}">
                        @else
                            <img
                                    src="@if($usuario->image){{ route('imagecache', ['fotohotel', 'user/'.$usuario->image]) }}@else{{ route('imagecache', ['fotohotel', 'user-img.jpg']) }}@endif"
                                    alt=""
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="{{ $usuario->username }}">
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
        <div id="hotel">
            <img  src="{{ asset('images/hotel.png') }}" alt="">
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('style')
    <style>
        .content-wrapper {
            background: url('{{ asset('images/fundo-hotel.jpg') }}');
        }

        section.content{
            width: 1130px;
            height:529px;
            position: relative;
            text-align: center;
        }

        .andares{
            width: 100%;
            position: absolute;
            z-index: 10;
        }

        #hotel{
            left: 0;
            top: 0;
            position: absolute;
            background: url('{{ asset('images/hotel.png') }}');
            width: 1130px;
            height:529px;
        }

        .tooltip-arrow{border-color: #f00;}
        .tooltip > .tooltip-inner {background-color: #f00;}
    </style>
@endsection

@section('script')
    <script>
        $(function(){

        });
    </script>
@endsection