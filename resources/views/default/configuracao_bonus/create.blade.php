@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cadastro de Configuração de Bônus</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" action="{{ route('configuracao-bonus.store') }}" method="post">
                        {!! csrf_field() !!}
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="">Status</label>
                                <br>
                                <label style="padding-right: 25px">
                                    <input type="radio" value="1" name="status" class="flat-red" {{ old('status', 1)  == 1 ? 'checked' : '' }}>
                                    Ativo
                                </label>
                                <label>
                                    <input type="radio" value="0" name="status" class="flat-red" {{ old('status', 1)  == 0 ? 'checked' : '' }}>
                                    Inativo
                                </label>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="exampleInputEmail1">Nome <strong class="text-red">*</strong></label><br>
                                <input type="text" name="nome" value="{{ old('nome') }}" class="form-control" placeholder="Nome">
                            </div>
                            <div class="form-group col-xs-12 col-sm-12">
                                <label for="">Valores de pagamento por níveis</label><br>
                                <table id="tabela_index" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Nível</th>
                                        <th>Valor Fixo</th>
                                        <th>Percentual %</th>
                                        <th>Ações</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @for($i=1; $i<=$sistema->profundidade_pagamento_matriz; $i++)
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>
                                                    <input style="text-align: center;" type="text" id="valor_fixo_{{$i}}" name="itens[{{$i}}][valor_fixo]" value="{{ old('itens.'.$i.'.valor_fixo', '0.00') }}" class="form-control somar_fixo" placeholder="Valor Fixo">
                                                </td>
                                                <td>
                                                    <input style="text-align: center;" type="text" id="percentual_{{$i}}" name="itens[{{$i}}][percentual]" value="{{ old('itens.'.$i.'.percentual', '0') }}" class="form-control somar_percentual" placeholder="Percentual %">
                                                </td>
                                                <td>
                                                    @if($sistema->profundidade_pagamento_matriz != $i)
                                                        <div class="btn-group" role="group" aria-label="Botões de Ação">
                                                            <button type="button" class="btn btn-success btn-sm replicar" nivel="{{$i}}">
                                                                <span class="fa fa-copy text-black"></span>
                                                                Copiar para os níveis abaixo
                                                            </button>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endfor
                                        <tr>
                                            <td>Total</td>
                                            <td><input style="text-align: center;" type="text" readonly id="total_fixo" name="total_fixo" class="form-control" value="{{ old('total_fixo', '0') }}"></td>
                                            <td><input style="text-align: center;" type="text" readonly id="total_percentual" name="total_percentual" class="form-control" value="{{ old('total_percentual', '0') }}"></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('configuracao-bonus.index') }}" class="btn btn-warning pull-right">Voltar</a>
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
    <link rel="stylesheet" href="/plugins/iCheck/square/red.css">
@endsection

@section('script')
    <script src="/plugins/iCheck/icheck.min.js"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>

    <script>
        $(function () {
            //Initialize Select2 Elements
            $(".select2").select2();

            $('input').iCheck({
                checkboxClass: 'icheckbox_square-red',
                radioClass: 'iradio_square-red',
                increaseArea: '20%' // optional
            });

            $('.replicar').on('click',function(){
                var nivel_atual = $(this).attr('nivel');
                var valor_fixo = $("#valor_fixo_" + nivel_atual).val();
                var percentual = $("#percentual_" + nivel_atual).val();
                for(var i = nivel_atual++; i<={{$sistema->profundidade_pagamento_matriz}}; i++){
                    $("#valor_fixo_" + i).val(valor_fixo);
                    $("#percentual_" + i).val(percentual);
                }

                somarFixo();
                somarPercentual();
            });
        });

        $(".somar_fixo").blur(function(){
            somarFixo();
        });

        $(".somar_percentual").blur(function(){
            somarPercentual();
        });

        function somarFixo() {
            //declaro uma var para somar o total
            var total = 0;
            //faço um foreach percorrendo todos os inputs com a class soma e faço a soma na var criada acima
            $(".somar_fixo").each(function(){
                total = total + Number($(this).val());
            });

            //mostro o total no input Sub Total
            $("#total_fixo").val(total);
        }

        function somarPercentual() {
            //declaro uma var para somar o total
            var total = 0;
            //faço um foreach percorrendo todos os inputs com a class soma e faço a soma na var criada acima
            $(".somar_percentual").each(function(){
                total = total + Number($(this).val());
            });

            //mostro o total no input Sub Total
            $("#total_percentual").val(total + "%");
        }
    </script>
@endsection