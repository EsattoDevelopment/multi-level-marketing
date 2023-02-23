@extends('layout.main')

@section('content')
    <section class="content">

        @include('errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" @submit.prevent="submit" id="formulario" action="{{ route('saude.guias.store') }}" method="post">
                    {!! csrf_field() !!}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cadastro de Guias</h3>
                        </div><!-- /.box-header -->

                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="tipo">Guia para Titular ou dependente</label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <select required v-model="tipo" class="form-control" id="tipo"
                                            name="tipo">
                                        <option value="">Selecione quem será atendido</option>
                                        <option {{ old('tipo') === 1 ? 'selected' : ''  }} value="1">Titular</option>
                                        <option {{ old('tipo') === 2 ? 'selected' : ''  }} value="2">Dependente</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="tipo_atendimento">Tipo de atendimento</label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <select required v-model="tipo_atendimento"
                                            class="form-control" id="tipo_atendimento" name="tipo_atendimento">
                                        <option value="">Selecione um tipo de exame</option>
                                        <option {{ old('tipo_atendimento') === 1 ? 'selected' : ''  }} value="1">
                                            Exames
                                        </option>
                                        <option {{ old('tipo_atendimento') === 2 ? 'selected' : ''  }} value="2">
                                            Consulta
                                        </option>
                                        <option {{ old('tipo_atendimento') === 4 ? 'selected' : ''  }} value="4">
                                            Sessão
                                        </option>
                                        <option {{ old('tipo_atendimento') === 5 ? 'selected' : ''  }} value="5">
                                            Fisioterapia
                                        </option>
                                        <option {{ old('tipo_atendimento') === 6 ? 'selected' : ''  }} value="6">
                                            Procedimento
                                        </option>
                                    </select>
                                </div>
                            </div>

                            @permission(['master', 'admin', 'gerar-guia-consulta'])
                            <div class="form-group col-xs-12">
                                <label for="clinicas">Clinicas</label>
                                <select required class="form-control" v-model="clinica" id="clinicas" name="clinica_id">
                                    <option value="">Escolha uma clinica</option>
                                    @foreach($clinicas as $clinica)
                                        <option value="{{ $clinica->id }}">{{ $clinica->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endpermission

                            <div class="form-group col-xs-12">
                                <label for="dt_atendimento">Data atendimento</label>
                                <input type="text" v-model="dt_atendimento" required class="form-control"
                                       value="{{ old('dt_atendimento', date('d/m/Y')) }}"
                                       id="dt_atendimento" name="dt_atendimento">
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="titular">N.Cartão/Paciente/Titular</label> <br>
                                <small>Titular do cartão <i class="text-danger">(Filtro: nome, numero do cartão,
                                        CPF)</i></small>
                                <select class="form-control" id="titular" name="user_id">
                                </select>
                            </div>

                            <div id="campo-dependente" class="form-group col-xs-12">
                                <label for="dependente">Dependente</label> <br>
                                <small>Se o paciente for o dependente</small>
                                <select v-bind:disabled="!campos.dependente" required v-model="dependente" class="form-control js-example-placeholder-single js-states" id="dependente"
                                        name="dependente_id">
                                    <option value="">Escolha um dependente</option>
                                </select>
                            </div>

                            <div id="campo-medicos" class="form-group col-xs-12">
                                <label for="medicos">Medicos</label>
                                <select v-model="medico" required v-bind:disabled="!campos.medicos" class="form-control js-example-placeholder-single js-states" id="medicos" name="medico_id">
                                    <option value="">Escolha um medico</option>
                                    @if($medicos->count() > 0)
                                        @foreach($medicos as $medico)
                                            <option value="{{ $medico->id }}">{{ $medico->name }}</option>
                                        @endforeach
                                    @else
                                        @foreach($clinicas->first()->medicos as $medico)
                                            <option {{ $medico->id == old('medico_id') ? 'selected' : '' }} value="{{ $medico->id }}">{{ $medico->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div id="campo-exames" class="form-group col-xs-12">
                                <label for="exames">Exames</label> <br>
                                <small>Exames com cobertura total do plano</small>
                                <select class="form-control" v-bind:disabled="!(campos.exames && !campos.dependente)" multiple id="exames" name="exames[]">
                                </select>
                            </div>

                            <div id="campo-procedimentos" class="form-group col-xs-12">
                                <label for="procedimentos">Procedimentos</label> <br>
                                <small>Selecione os procedimentos a serem realizados</small>
                                <select class="form-control" v-bind:disabled="!campos.procedimentos" multiple id="procedimentos" name="procedimentos[]">
                                    @if($procedimentos->count() > 0)
                                        @foreach($procedimentos as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="observacao">Observação</label>
                                <textarea class="form-control" v-model="observacao" id="observacao"
                                          name="observacao">{{ old('observacao') }}</textarea>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            @if(!Auth::user()->can(['master', 'admin', 'gerar-guia-consulta']))
                                <input type="hidden" name="clinica_id" value="{{ Auth::user()->id }}">
                            @endif
                            <input type="hidden" name="confirmado_por" value="{{ Auth::user()->id }}">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ URL::previous() }}" class="btn btn-default pull-right">Voltar</a>
                        </div>
                    </div><!-- /.box -->
                </form>
            </div><!--/.col (left) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->

@endsection

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css')}}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/i18n/pt-BR.js') }}"></script>

    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{ asset('plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js')}}"></script>

    <script src="{{ asset('plugins/input-mask/jquery.inputmask.js')}}"></script>
    <script src="{{ asset('plugins/input-mask/jquery.inputmask.date.extensions.js')}}"></script>

    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>

    <script>

      $(function(){
          @if($procedimentos->count() > 0)
          $("#procedimentos").select2();
          @endif
        //Busca
        $("#titular").select2({
          placeholder: 'Buscar paciente...',
          language: "pt-BR",
          minimumInputLength: 2,
          tags: false,
          ajax: {
            delay: 250,
            url: "{{ route('api.paciente.busca') }}",
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            data: function (params) {
              var queryParameters = {
                search: params.term
              }
              return queryParameters;
            },
            processResults: function (data) {
              return {
                results: $.map(data, function (item) {
                  return {
                    text: item.name + ' - Contrato: ' + item.codigo,
                    id: item.id
                  }
                })
              };
            },
            cache: true
          }
        });

        //Metodo ao selecionar
        $('#titular').on('select2:select', function (e) {
          var data = e.params.data;

          $.get('{{ route('saude.dependentes.busca') }}', {search: data.id}, function (data) {

            var dados = $.map(data, function (obj) {
              obj.text = obj.name + ' - ' + obj.parentesco + ' - RG: ' + obj.rg;

              return obj;
            });


            $('#dependente').html('');
            $('#dependente').append("<option value=''>Escolha um dependente</option>");

            $.each(dados, function (key, value) {
              $('#dependente').append("<option value='" + value.id + "'>" + value.text + "</option>");
            });
          });

          $.get('{{ route('saude.exames.from.user') }}', {user: data.id}, function (data) {

            //se já há dados no select destroy o select2 e options
            if ($('#exames').hasClass('select2-hidden-accessible')) {
              $('#exames').select2('destroy');
              $('#exames').html('');
            }

            $.each(data, function (key, value) {
              $('#exames').append("<option value='" + value.id + "'>" + value.text + "</option>");
            });

            $('#exames').select2();
          });

        });

        $("#dt_atendimento").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
      })

        var app = new Vue({
            el: "#formulario",

            data: {
                campos: {
                    dependente: false,
                    medicos: false,
                    exames: false,
                    procedimentos: false
                },
                tipo: "",
                tipo_atendimento: "",
                clinica: "",
                dt_atendimento: "{{ old('dt_atendimento', date('d/m/Y')) }}",
                titular: "",
                dependente: "",
                medico: "",
                exames: [],
                procedimentos: [],
                observacao: "",
                dados: []
            },
            watch: {
                tipo_atendimento:  function(val){
                    $('#medicos').val(null).trigger('change');
                    $('#dependente').val(null).trigger('change');
                    //se vazio esconde todos os campos
                    if(val != '') {

                        if (['2', '3', '4', '5'].indexOf(val) > -1) {
                            this.campos.medicos = true
                        }else{
                            this.campos.medicos = false;
                        }

                        //se medicos for verdadeiro, exames é falso
                        this.campos.exames = this.campos.medicos ? false : true
                        this.campos.procedimentos = false;

                        if(val == '6'){
                            this.campos.procedimentos = true;
                            this.campos.exames = false;
                        }
                    }else{
                        this.campos.medicos = this.campos.exames = false
                    }
                },
                tipo: function(val) {
                    $('#dependente').val(null).trigger('change');
                    $('#medicos').val(null).trigger('change');

                    if (val == 1) {
                        this.campos.dependente = false
                    } else if (val == 2) {
                        this.campos.dependente = true
                    }
                },
                clinica: function(val){
                    $.get('{{ route('clinica.medicos') }}', {clinica: val}, function (data) {

                        //se já há dados no select destroy o select2 e options
                       /* if ($('#medicos').hasClass('select2-hidden-accessible')) {
                            $('#medicos').select2('destroy');
                            $('#medicos').html('');
                        }*/
                        $('#medicos').html('');

                        $('#medicos').append("<option value=''>Escolha um medico</option>");

                        $.each(data, function (key, value) {
                            $('#medicos').append("<option value='" + value.id + "'>" + value.name + "</option>");
                        });

                        /*$("#medicos").select2({
                            placeholder: 'Escolha um medico...',
                        });*/
                    });

                    $.get('{{ route('saude.procedimentos_clinica.from.clinica') }}', {clinica: val}, function (data) {

                        //se já há dados no select destroy o select2 e options
                        if ($('#procedimentos').hasClass('select2-hidden-accessible')) {
                            $('#procedimentos').select2('destroy');
                            $('#procedimentos').html('');
                        }

                        $('#procedimentos').append("<option value=''></option>");

                        $.each(data, function (key, value) {
                            $('#procedimentos').append("<option value='" + value.procedimento_id + "'>" + value.procedimento.name + "</option>");
                        });

                        $('#procedimentos').select2({
                            placeholder: 'Escolha os procedimentos...',
                        });
                    });
                },
                titular: function(val){

                    $.get('{{ route('saude.dependentes.busca') }}', {search: val}, function (data) {

                        var dados = $.map(data, function (obj) {
                            obj.text = obj.name + ' - ' + obj.parentesco + ' - RG: ' + obj.rg;

                            return obj;
                        });

                        //se já há dados no select destroy o select2 e options
                        if ($('#dependente').hasClass('select2-hidden-accessible')) {
                            $('#dependente').select2('destroy');
                            $('#dependente').html('');
                        }

                        $('#dependente').append("<option value=''></option>");

                        $.each(dados, function (key, value) {
                            $('#dependente').append("<option value='" + value.id + "'>" + value.text + "</option>");
                        });

                        $('#dependente').select2({
                            placeholder: 'Escolha um dependente...',
                        });
                    });

                    $.get('{{ route('saude.exames.from.user') }}', {user: val}, function (data) {

                        //se já há dados no select destroy o select2 e options
                        if ($('#exames').hasClass('select2-hidden-accessible')) {
                            $('#exames').select2('destroy');
                            $('#exames').html('');
                        }

                        $('#exames').append("<option value=''></option>");

                        $.each(data, function (key, value) {
                            $('#exames').append("<option value='" + value.id + "'>" + value.text + "</option>");
                        });

                        $('#exames').select2({
                            placeholder: 'Escolha os exames...',
                        });
                    });
                }
            },
            methods: {
                submit: function(){

                    var constants = {!! json_encode(config('constants.tipo_atendimento')) !!}

                    var exames2 = '';

                    $('#exames option:selected').each(function () {
                        exames2 += $(this).text() + ', <br>';
                    });

                    var procedimentos2 = '';

                    $('#procedimentos option:selected').each(function () {
                        procedimentos2 += $(this).text() + ', ';
                    });

                    var dados = {
                        tipo: this.tipo == '1' ? 'Titular' : 'Dependente',
                        atendimento: constants[this.tipo_atendimento],
                        paciente: this.tipo == '1' ? $('#titular').text() : $('select[name="dependente_id"] option:selected').text(),
                        medico: this.tipo_atendimento != '1' ? "<br><b>Medico</b>: " + $('select[name="medico_id"] option:selected').text() : '',
                        obs: this.observacao,
                        exames: this.tipo_atendimento == '1' ? "<br> <b>Exames cobridos pelo plano</b>: " + exames2 : '',
                        procedimentos: this.tipo_atendimento == '6' ? "<br> <b>Procedimentos</b>: " + procedimentos2 : ''
                    };

                    swal({
                            title: "Verifique as informações?",
                            html: true,
                            text: "<b>Quem sera atendido</b>: " + dados.tipo + " <br><b>Qual o tipo da guia</b>: " + dados.atendimento + "<br><b>Paciente</b>: " + dados.paciente + dados.medico + dados.exames + dados.procedimentos + "<br><b>Observação</b>: " + dados.obs,
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