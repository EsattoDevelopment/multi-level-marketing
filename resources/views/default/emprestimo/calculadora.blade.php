@extends('default.layout.main')

@section('title')
    <title>Simular empréstimo</title>
@endsection

@section('content')
    <section class="content-header">
        <h1>Simular empréstimo</h1>
        <ol class="breadcrumb hidden-xs">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Simular empréstimo</li>
        </ol>
    </section>
    <section class="content">
        @include('default.errors.errors')
        <div class="col-md-6 center-block" style="float: none;">
            <div class="box box-solid">
                <div class="box-body text-center">
                    <h2 class="form-title"><b>Quanto</b> você quer <b>emprestar</b>?</h2>
                    <form action="{{ route('emprestimos.simular') }}" method="post" class="form">
                        {{ csrf_field() }}
                        <input
                            type="text"
                            name="valor"
                            placeholder="0,00"
                            value="{{ old('valor') }}"
                            data-affixes-stay="true"
                            data-prefix="{{ $sistema->moeda }} "
                            data-thousands="."
                            data-decimal=","
                        />
                        <p>Valor mínimo de {{ mascaraMoeda($sistema->moeda, $sistema->min_emprestimo, 2, true) }}.</p>
                        <button type="submit" class="btn btn-primary">Simular</button>
                    </form>
                </div>
            </div>
        </div>
        @if(isset($parcelas, $valor))
            <div class="center-block text-center">
                <h2>Simulação para um empréstimo de <b>{{ mascaraMoeda($sistema->moeda, $valor, 2, true) }}</b></h2>
            </div>
            <ul class="grupo">
                @foreach($parcelas as $grupo => $lista_parcelas)
                    <li class="box box-solid">
                        <div>
                            <h3>{{ $grupo }}</h3>
                        </div>
                        <ul class="parcelas">
                            @foreach(array_reverse($lista_parcelas) as $key => $parcela)
                                <li>
                                    <span>{{ str_pad($parcela['nome'], 3, '0', STR_PAD_LEFT) }} de </span>
                                    @if($key === 0)
                                        <span>
                                            <strong>
                                                <sup>{{ $sistema->moeda }}</sup>
                                                <span style="font-size: 32px">{{ mascaraMoeda($sistema->moeda, $parcela['valor_total'], 0) }}</span>
                                                <sub>{{substr(explode('.', (string) $parcela['valor_total'])[1], 0, 2)}}</sub>
                                            </strong>
                                        </span>
                                        <div class="mais-popular">
                                            <span>Mais Popular</span>
                                        </div>
                                    @else
                                        <span>{{ mascaraMoeda($sistema->moeda, $parcela['valor_total'], 2, true) }}</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection

@section('style')
    <style>
        .form-title {
            margin-top: 5px;
        }
        .form input {
            text-align: center;
            background: none;
            border: none;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
            font-size: 30px;
            font-family: 'Source Sans Pro',sans-serif;
            margin: 10px 0 20px 0;
            max-width: 230px;
            outline: none;
        }
        .grupo {
            display: flex;
            justify-content: center;
            flex-direction: row;
            list-style: none;
            line-height: 2;
        }
        .grupo h3 {
            margin-top: 0;
        }
        .grupo > li {
            margin: 0 12px;
            padding: 24px;
            width: auto;
            min-width: 300px;
        }
        @media screen and (max-width: 700px) {
            .grupo {
                flex-direction: column;
                margin: 0;
                padding: 0;
            }
            .grupo > li {
                margin: 16px 0;
            }
        }
        .parcelas {
            list-style: none;
            padding-left: 0;
            overflow: hidden;
        }
        .parcelas > li {
            padding: 0 16px;
            position: relative;
        }
        .parcelas > li:nth-child(odd) {
            background-color: #efefef;
        }
        .parcelas > li:nth-child(1) {
            background-color: #3c8dbc;
            color: #fff;
            font-size: 18px;
        }
        .mais-popular {
            position: absolute;
            top: 0;
            right: 0;
            width: 64px;
            height: 64px;
        }
        .mais-popular > span {
            width: 120px;
            height: 16px;
            font-size: 12px;
            font-weight: bold;
            top: 18px;
            left: -32px;
            text-transform: uppercase;
            position: absolute;
            background-color: #fff;
            color: #333333;
            transform: rotate(35deg);
            display: flex;
            justify-content: center;
            align-items: center;
            pointer-events: none;
        }
    </style>
@endsection

@section('script')
    <script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
    <script>
        $(() => {
            $('input[name="valor"]').maskMoney()
        })
    </script>
@endsection
