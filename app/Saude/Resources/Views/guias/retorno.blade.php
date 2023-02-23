@extends('layout.main')

@section('content')
    <section class="content">

        @include('errors.errors')

        @if($guia->dt_autorizado->diffInDays(\Carbon\Carbon::now()) > 21)
            <div class="alert alert-danger">
                Esta guia foi atendida a mais de 21 dias
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <form role="form" @submit.prevent="submit" id="formulario" action="{{ route('saude.guias.store') }}" method="post">
                    {!! csrf_field() !!}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Retorno de consulta - Confira os dados antes de confirmar</h3>
                        </div><!-- /.box-header -->

                        <div class="box-body">
                            <dl class="dl-horizontal">
                                <dt>Paciente</dt>
                                @if($guia->tipo == 1)
                                    <dd>{{ $guia->usuario->name }}</dd>
                                @else
                                    <dd>{{ $guia->dependente->name }}</dd>
                                @endif

                                <dt>Tipo paciente</dt>
                                <dd>{{ $guia->tipo == 1 ? 'Titular' : 'Dependente' }}</dd>

                                <dt>Tipo de atendimento</dt>
                                <dd>{{ config('constants.tipo_atendimento')[$guia->tipo_atendimento] }}</dd>

                                <dt>Médico</dt>
                                <dd>{{ $guia->medico->name }}</dd>

                                <dt>Clinica</dt>
                                <dd>{{ $guia->clinica->name }}</dd>

                                <dt>Data do atendimento</dt>
                                <dd>{{ $guia->dt_atendimento }}</dd>

                                <dt>Titular do contrato</dt>
                                <dd>{{ $guia->usuario->name }}</dd>

                                <dt>Contrato</dt>
                                <dd>{{ $guia->usuario->codigo }}</dd>

                            </dl>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                    <div class="box box-warning">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="dt_atendimento">Data do retorno</label> <br>
                                <input class="form-control" required v-model="dt_atendimento" id="dt_atendimento" type="text" name="dt_atendimento">
                            </div>
                            <div class="form-group">
                                <label for="observacao">Observação</label> <br>
                                <textarea class="form-control" v-model="observacao" name="observacao" id="">{{ old('observacao') }}</textarea>

                            </div>
                        </div>
                        <div class="box-footer">
                            <input type="hidden" v-model="clinica" name="clinica_id" value="{{ $guia->clinica_id }}">
                            <input type="hidden" name="user_id" value="{{ $guia->user_id }}">
                            <input type="hidden" name="tipo" value="{{ $guia->tipo }}">
                            <input type="hidden" name="tipo_atendimento" value="3">
                            <input type="hidden" name="medico_id" value="{{ $guia->medico_id }}">

                            @if($guia->tipo == 2)
                                <input type="hidden" name="dependente_id" value="{{ $guia->dependente_id }}">
                            @endif

                            <input type="hidden" name="clinica_id" value="{{ $guia->clinica_id }}">

                            <input type="hidden" name="confirmado_por" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="guia_referencia" value="{{ $guia->id }}">
                            <button type="submit" class="btn btn-primary">Gerar guia de retorno</button>
                            <a href="{{ route('saude.guias.autorizadas') }}" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div>
                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
@endsection

@section('script')
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>

    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <script>

      $(function(){
        $("#dt_atendimento").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
      })

        var app = new Vue({
            el: "#formulario",

            data: {
                tipo: "{{ $guia->tipo == 1 ? 'Titular' : 'Dependente' }}",
                tipo_atendimento: "{{ config('constants.tipo_atendimento')[3] }}",
                clinica: '{{ $guia->clinica->name }}',
                dt_atendimento: "{{ old('dt_atendimento', date('d/m/Y')) }}",
                paciente: "{{ $guia->tipo == 1 ? $guia->usuario->name : $guia->dependente->name }}",
                titular: "{{ $guia->usuario->name }}",
                dependente: "{{ $guia->tipo == 2 ? $guia->dependente->name : '' }}",
                medico: "{{ $guia->medico->name }}",
                observacao: ""
            },
            methods: {
                submit: function(){

                    swal({
                            title: "Verifique as informações?",
                            html: true,
                            text: "<b>Paciente</b>: " + this.paciente +
                                "<br><b>Medico</b>: " + this.medico +
                                "<br><b>Qual o tipo da guia</b>: " + this.tipo_atendimento +
                                "<br><b>Data</b>: " + document.getElementById("dt_atendimento").value +
                                "<br><b>Local</b>: " + this.clinica +
                                "<br><b>Quem sera atendido</b>: " + this.tipo +
                                "<br><b>Observação</b>: " + this.observacao,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Solicitar",
                            cancelButtonText: "Cancelar",
                            closeOnConfirm: false,
                            closeOnCancel: false
                        },
                        function (isConfirm) {
                            if (isConfirm) {
                                $('#formulario').submit();
                                swal("Enviando...", "Guia sendo enviada!", "success");
                            } else {
                                swal("Cancelado", "Cancelado com sucesso!", "error");
                            }
                        });
                }
            }
        });

    </script>
@endsection