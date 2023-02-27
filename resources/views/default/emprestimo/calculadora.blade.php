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
            <ul class="parcelas">
                @foreach($parcelas as $grupo => $lista_parcelas)
                    <li class="box box-solid">
                        <h3>{{ $grupo }}</h3>
                        <ul>
                            @foreach($lista_parcelas as $parcela)
                                <li>{{ $parcela['nome'] }} - {{ mascaraMoeda($sistema->moeda, $parcela['valor_total'], 2, true) }}</li>
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
        .parcelas {
            display: flex;
            justify-content: center;
            flex-direction: row;
            list-style: none;
            line-height: 2;
        }
        .parcelas h3 {
            margin-top: 0;
        }
        .parcelas > li {
            margin: 0 12px;
            padding: 24px;
            width: auto;
            min-width: 300px;
        }
        @media screen and (max-width: 700px) {
            .parcelas {
                flex-direction: column;
                margin: 0;
                padding: 0;
            }
            .parcelas > li {
                margin: 16px 0;
            }
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
