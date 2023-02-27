@extends('default.layout.main')

@section('title')
    <title>Pagar</title>
@endsection

@section('content')
    <section class="content-header">
        <h1>Pagar</h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Pagar</li>
        </ol>
    </section>
    <section class="content">
        @include('default.errors.errors')
        <div class="col-md-12 center-block" style="float: none;">
            <form method="post" action="{{ route('emprestimos.pagar') }}" class="box box-solid">
                {!! csrf_field() !!}
                <div class="box-body">
                    <div class="form-group col-xs-12">
                        <label for="user_id">Usuário</label>
                        <select name="user_id" id="user_id" class="form-control select2">
                            <option value="">Escolha um usuario</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->id }}">
                                    #{{ $usuario->id }} - {{ $usuario->name }} @if($usuario->empresa) - {{ $usuario->empresa }} @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="valor">Valor</label>
                        <input
                            type="text"
                            name="valor"
                            id="valor"
                            value="{{ old('valor') }}"
                            class="form-control"
                            placeholder="0,00"
                            data-prefix="{{ $sistema->moeda }} "
                            data-thousands="."
                            data-decimal=","
                        />
                    </div>
                    <div class="form-group col-xs-12">
                        <label for="chave_pix">Chave Pix</label>
                        <input type="text" name="chave_pix" id="chave_pix" value="{{ old('chave_pix') }}" placeholder="Chave Pix" class="form-control">
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Pagar</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@section('script')
    <script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
    <script>
        $(function () {
            $("input[name='valor']").maskMoney().maskMoney('mask')
        })
    </script>
@endsection
