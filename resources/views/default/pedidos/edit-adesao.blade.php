@extends('default.layout.main')

@section('content')

    @include('default.errors.errors')

    <section class="content-header">
        <h1>
            Edição pedido
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Edição pedido</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-header with-border">
                        <i class="glyphicon glyphicon-user"></i>

                        <h3 class="box-title">Dados do pedido</h3>
                    </div>
                    <div class="box-body">
                        <dl class="dl-horizontal">
                            <dt>Nome usuário</dt>
                            <dd>{{ $dados->getRelation('user')->name }}</dd>
                            <dt>Item</dt>
                            <dd>
                                #{{ $dados->getRelation('itens')->first()->item_id }} {{ $dados->getRelation('itens')->first()->name_item }}</dd>
                            <dt>Valor</dt>
                            <dd>{{ $sistema->moeda }} {{ $dados->getRelation('dadosPagamento')->valor }}</dd>
                            <dt>Data compra</dt>
                            <dd>{{ $dados->data_compra->format('d/m/Y') }}</dd>
                            <dt>Pontos válidos</dt>
                            <dd>{{ $dados->getRelation('itens')->first()->getRelation('itens')->pontos_binarios }}</dd>
                            {{--<dt>Milhas</dt>
                            <dd>{{ $dados->getRelation('itens')->first()->getRelation('itens')->milhas }}</dd>--}}
                        </dl>
                    </div><!-- /.box-body -->

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="box box-warning">
                            <div class="box-header with-border">
                                <i class="glyphicon glyphicon-user"></i>

                                <h3 class="box-title">Dados de pagamento</h3>
                            </div>
                            <form method="post" action="{{ route('pedido.update', $dados) }}">
                                <div class="box-body">

                                    {!! csrf_field() !!}
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="pedido_id" value="{{ $dados->id }}">

                                    <div class="form-group col-md-12">
                                        <label class="text-red">Forma de pagamento</label>
                                        <select class="form-control" required id="metodo_pagamento_id"
                                                name="metodo_pagamento_id">
                                            @foreach($metodo_pagamento as $mp)
                                                <option value="{{ $mp->id }}" {{ old('metodo_pagamento_id', $dados->getRelation('dadosPagamento')->metodo_pagamento_id) == $mp->id ? 'selected="selected"' : '' }} >{{ $mp->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Data pagamento</label>
                                        <input type="text" required name="data_pagamento" value="{{ $dados->getRelation('dadosPagamento')->data_pagamento->format('d/m/Y') }}" class="form-control datepicker">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Descrição</label>
                                        <input type="text" required name="documento" class="form-control" value="{{ $dados->getRelation('dadosPagamento')->documento }}"
                                               placeholder="Descrição">
                                    </div>

                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-success" type="button">Salvar</button>
                                    <a href="{{ URL::previous() }}" class="btn btn-primary pull-right">Voltar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- /.box -->
            </div><!--/.col (left) -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="/plugins/datatables/dataTables.bootstrap.css">s
@endsection

@section('script')
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{ asset('plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js')}}"></script>

    <!-- InputMask -->
    <script src="../../plugins/input-mask/jquery.inputmask.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>

    <script>

        $(function () {
            $("input[name='dt_pagamento'], input[name='data_vencimento']").inputmask({
                mask: '99/99/9999',
                showTooltip: true,
                showMaskOnHover: true
            });

            $.fn.datepicker.defaults.language = 'pt-BR';

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });
        })

    </script>
@endsection