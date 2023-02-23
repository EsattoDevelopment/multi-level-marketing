@extends('layout.main')

@section('content')
    <section class="content">

        @include('errors.errors')

        <div class="row">
            <div class="col-md-12">
                <form role="form" action="{{ route('saude.guias.update', $dados) }}" method="post">
                    {!! csrf_field() !!}
                    <input type="hidden" name="_method" value="PUT">

                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edição de Guias</h3>
                        </div><!-- /.box-header -->

                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="tipo">Guia para Titular ou dependente</label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <select required class="form-control" id="tipo" name="tipo">
                                        <option value="">Selecione quem será atendido</option>
                                        <option {{ old('tipo', $dados->tipo) === 1 ? 'selected' : ''  }} value="1">Titular</option>
                                        <option {{ old('tipo', $dados->tipo) === 2 ? 'selected' : ''  }} value="2">Dependente</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="tipo_atendimento">Tipo de atendimento</label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <select required class="form-control" id="tipo_atendimento" name="tipo_atendimento">
                                        <option value="">Selecione um tipo de exame</option>
                                        <option {{ old('tipo_atendimento', $dados->tipo_atendimento) === 1 ? 'selected' : ''  }} value="1">Exames</option>
                                        <option {{ old('tipo_atendimento', $dados->tipo_atendimento) === 2 ? 'selected' : ''  }} value="2">Consulta</option>
                                        <option {{ old('tipo_atendimento', $dados->tipo_atendimento) === 3 ? 'selected' : ''  }} value="3">Retorno</option>
                                        <option {{ old('tipo_atendimento', $dados->tipo_atendimento) === 4 ? 'selected' : ''  }} value="4">Sessão</option>
                                        <option {{ old('tipo_atendimento', $dados->tipo_atendimento) === 5 ? 'selected' : ''  }} value="5">Fisioterapia</option>
                                        <option {{ old('tipo_atendimento', $dados->tipo_atendimento) === 6 ? 'selected' : ''  }} value="6">Procedimento</option>
                                    </select>
                                </div>
                            </div>

                            @permission(['master', 'admin', 'gerar-guia-consulta'])
                            <div class="form-group col-xs-12">
                                <label>Clinicas</label>
                                <select class="form-control" id="clinicas" name="clinica_id">
                                    @foreach($clinicas as $clinica)
                                        <option {{ $dados->clinica_id == $clinica->id ? 'selected' : '' }} value="{{ $clinica->id }}">{{ $clinica->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endpermission

                            <div class="form-group col-xs-12">
                                <label>Data atendimento</label>
                                <input type="text" required class="form-control"
                                       value="{{ old('dt_atendimento', $dados->dt_atendimento) }}"
                                       id="dt_atendimento" name="dt_atendimento">
                            </div>

                            <div class="form-group col-xs-12">
                                <label>N.Cartão/Paciente/Titular</label> <br>
                                <small>Titular do cartão <i class="text-danger">(Filtro: nome, numero do cartão,
                                        CPF)</i></small>
                                <select required class="form-control" id="titular" name="user_id">
                                    <option value="{{ $dados->user_id }}">{{ $dados->usuario->name }}</option>
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>Dependente</label> <br>
                                <small>Se o paciente for o dependente</small>
                                <select disabled class="form-control" id="dependente" name="dependente_id">
                                    @foreach($dados->usuario->dependentes as $dependente)
                                        <option {{ $dados->dependente_id == $dependente->id ? 'selected' : '' }} value="{{ $dependente->id }}">{{ $dependente->name . ' - ' . $dependente->parentesco . ' - RG: ' . $dependente->rg }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label for="name">Medicos</label>
                                <select disabled class="form-control" id="medicos" name="medico_id">
                                    @forelse($dados->clinica->medicos as $medico)
                                        <option {{ $medico->id == $dados->medico_id ? 'selected' : '' }} value="{{ $medico->id }}">{{ $medico->name }}</option>
                                    @empty
                                        @foreach($clinicas->first()->medicos as $medicos)
                                            <option {{ $medico->id == $dados->medico_id ? 'selected' : '' }} value="{{ $medico->id }}">{{ $medico->name }}</option>
                                        @endforeach
                                    @endforelse
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>Exames</label> <br>
                                <small>Exames com cobertura total do plano</small>
                                <select class="form-control" multiple id="exames" name="exames[]">
                                    @forelse($dados->usuario->contratoVigente()->item->exames as $exame)
                                        <option {{ $dados->exames->contains($exame) ? 'selected' : '' }} value="{{ $exame->id }}">{{ $exame->nome }}</option>
                                    @empty

                                    @endforelse
                                </select>
                            </div>

                            <div id="campo-procedimentos" class="form-group col-xs-12">
                                <label for="procedimentos">Procedimentos</label> <br>
                                <small>Selecione os procedimentos a serem realizados</small>
                                <select class="form-control" v-bind:disabled="!campos.procedimentos" multiple id="procedimentos" name="procedimentos[]">
                                    @forelse($procedimentos_clinicas as $pc)
                                        <option {{ $dados->procedimentos->contains($pc->procedimento_id) ? 'selected' : '' }} value="{{ $pc->procedimento->id }}">{{ $pc->procedimento->name }}</option>
                                    @empty

                                    @endforelse
                                </select>
                            </div>

                            <div class="form-group col-xs-12">
                                <label>Observação</label>
                                <textarea class="form-control" id="observacao"
                                          name="observacao">{{ old('observacao', $dados->observacao) }}</textarea>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            @if(!Auth::user()->can(['master', 'admin', 'gerar-guia-consulta']))
                                <input type="hidden" name="clinica_id" value="{{ Auth::user()->id }}">
                            @endif
                            <input type="hidden" name="confirmado_por" value="{{ Auth::user()->id }}">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <a href="{{ route('saude.guias.index') }}" class="btn btn-default pull-right">Voltar</a>
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

    <script>
        $(function () {
            //Initialize Select2 Elements

            var constants = {!! json_encode(config('constants.tipo_atendimento')) !!}

            @if($dados->tipo_atendimento == 1 || $dados->tipo_atendimento == 4)
                @if(in_array($dados->tipo_atendimento, [1,4]))
                    exameIn();
                @else
                    exameOut();
                @endif

            medicoOut();
            @else
            exameOut();
            medicoIn();
            @endif

            @if($dados->tipo == 1)
            $('#dependente').prop('required', false);
            $('#dependente').prop('disabled', true);
            @else
            $('#dependente').prop('required', true);
            $('#dependente').prop('disabled', false);
            @endif

            @if($dados->tipo_atendimento != 6)
            $('#procedimentos').prop('required', false);
            $('#procedimentos').prop('disabled', true);
            @endif

            $('#formulario').submit(function (event) {

                event.preventDefault();

                var exames2 = '';

                $('#exames option:selected').each(function () {
                    exames2 += $(this).text() + ', <br>';
                });

                var procedimentos2 = '';
                $('#procedimentos option:selected').each(function () {
                    procedimentos2 += $(this).text() + ', <br>';
                });

                var dados = {
                    tipo: $('select[name="tipo"] option:selected').val() == '1' ? 'Titular' : 'Dependente',
                    atendimento: constants[$('select[name="tipo_atendimento"] option:selected').val()],
                    paciente: $('select[name="tipo"] option:selected').val() == '1' ? $('select[name="user_id"] option:selected').text() : $('select[name="dependente_id"] option:selected').text(),
                    medico: $('select[name="tipo_atendimento"] option:selected').val() == '2' ? "<br><b>Medico</b>: " + $('select[name="medico_id"] option:selected').text() : '',
                    obs: $('textarea[name="observacao"]').val(),
                    exames: $('select[name="tipo_atendimento"] option:selected').val() == '1' ? "<br> <b>Exames cobridos pelo plano</b>: " + exames2 : '',
                    procedimentos: $('select[name="tipo_atendimento"] option:selected').val() == '6' ? "<br> <b>Procedimentos</b>: " + procedimentos2 : ''
                };

                //console.log(dados);

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
                            $('#formulario').unbind('submit').submit();
                            swal("Enviando...", "Guia sendo enviada!", "success");
                        } else {
                            swal("Cancelado", "Cancelado com sucesso!", "error");
                        }
                    });
            });

            $("#dt_atendimento").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});

            $("#medicos").select2({
                placeholder: 'Escolha um medico...',
            });

            $('#exames').select2();

            $("#procedimentos").select2();

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

                    //se já há dados no select destroy o select2 e options
                    if ($('#dependente').hasClass('select2-hidden-accessible')) {
                        $('#dependente').select2('destroy');
                        $('#dependente').html('');
                    }

                    $.each(dados, function (key, value) {
                        $('#dependente').append("<option value='" + value.id + "'>" + value.text + "</option>");
                    });

                    $('#dependente').select2();
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

            //Mudança no tido de paciente
            $('select[name="tipo"]').change(function () {
                if ($(this).val() == 2) {
                    $('#dependente').prop('required', true);
                    $('#dependente').prop('disabled', false);
                    exameOut();

                } else {
                    $('#dependente').prop('required', false);
                    $('#dependente').prop('disabled', true);

                    //verifica se é consulta ou exame
                    tipoAtendimento = $('select[name="tipo_atendimento"] option:selected').val();
                    if (tipoAtendimento == 1 || tipoAtendimento == 4) {
                        exameIn();
                        medicoOut();
                    } else {
                        exameOut();
                        medicoIn();
                    }
                }
            });

            $('select[name="tipo_atendimento"]').change(function () {
                if ($(this).val() == 2 || $(this).val() == 3 || $(this).val() == 5) {
                    exameOut();
                    medicoIn();
                } else {
                    medicoOut();

                    tipo = $('select[name="tipo"] option:selected').val();
                    if (tipo == 1 || tipo == 4) {
                        exameIn();
                    } else {
                        exameOut();
                    }

                    if ($(this).val() != 6) {
                        $('#procedimentos').prop('required', false);
                        $('#procedimentos').prop('disabled', true);
                        $("#procedimentos-multi").prop("disabled", true);
                    }

                    if($(this).val() == 6){
                        $('#procedimentos').prop('required', true);
                        $('#procedimentos').prop('disabled', false);
                        $("#procedimentos-multi").prop("disabled", false);
                    }
                }
            });

            function exameIn() {
                $('#exames').prop('disabled', false);
            }

            function exameOut() {
                $('#exames').prop('disabled', true);
            }

            function medicoIn() {
                $('#medicos').prop('required', true);
                $('#medicos').prop('disabled', false);
            }

            function medicoOut() {
                $('#medicos').prop('required', false);
                $('#medicos').prop('disabled', true);
            }
        });
    </script>
@endsection