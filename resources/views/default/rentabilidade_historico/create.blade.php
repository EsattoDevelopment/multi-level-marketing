@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <form role="form" action="{{ route('operacao-historico.store') }}" method="post" enctype="multipart/form-data">
            {!! csrf_field() !!}
                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Cadastro de Histórico de Operação</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12">
                                <label for="status">Status</label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ old('status') == 1 ? 'active' : ''}}">
                                        <input type="radio" value="1" {{ old('status') == 1 ? 'checked' : ''}} name="status"
                                               autocomplete="off">Ativo
                                    </label>
                                    <label class="btn btn-primary {{ old('status') == 0 ? 'active' : ''}}">
                                        <input type="radio" value="0" {{ old('status') == 0 ? 'checked' : ''}} name="status"
                                               autocomplete="off">Inativo
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-2">
                                <label for="data">Data</label>
                                <input type="text" name="data"
                                       value="{{ old('data') }}"
                                       class="form-control datepicker" id="data"
                                       placeholder="Data">
                            </div>
                            <div class="form-group col-xs-12 col-lg-4">
                                <label for="exampleInputEmail1">Valor</label>
                                <div class="input-group">
                                    <span class="input-group-addon">{{$sistema->moeda}}</span>
                                    <input type="text" class="form-control" name="valor"
                                           value="{{ old('valor') }}" placeholder="Informe o valor"
                                           data-affixes-stay="true">
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-4">
                                <label for="exampleInputEmail1">Percentual</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="percentual"
                                           value="{{ old('percentual') }}" placeholder="Informe o percentual"
                                           data-affixes-stay="true" data-prefix="" data-thousands=","
                                           data-decimal=".">
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label>Plataforma</label>
                                @if(isset($plataforma->id))
                                    <input type="text" name="plataforma_nome" readonly class="form-control" value="{{ $plataforma->nome }}">
                                    <input type="hidden" name="plataforma_id" value="{{ $plataforma->id }}">
                                @else
                                    <select id="plataforma" class="form-control select2" required name="plataforma_id" data-placeholder="Selecione a plataforma" style="width: 100%;">
                                            <option value="">Selecione a plataforma</option>
                                            @foreach($plataforma as $item)
                                                <option @if(old('plataforma_id') == $item->id) selected @endif value="{{ $item->id }}">{{ $item->nome }}</option>
                                            @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="form-group col-xs-12 col-lg-12">
                                <label>Conta</label>
                                @if($conta != null)
                                    <input type="text" name="conta_nome" readonly class="form-control" value="{{ $conta->nome }}">
                                    <input type="hidden" name="plataforma_conta_id" value="{{ $conta->id }}">
                                @else
                                    <select id="conta" class="form-control select2" required name="plataforma_conta_id" data-placeholder="Selecione a conta" style="width: 100%;">
                                    </select>
                                @endif
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="titulo">Titulo</label>
                                <input type="text" name="titulo" value="{{ old('titulo') }}" class="form-control" placeholder="Titulo">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="descricao">Descrição</label>
                                <textarea name="descricao" class="form-control" rows="5" placeholder="Descrição">{{ old('descricao') }}</textarea>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="arquivo">Imagem (png, jpg, jpeg)</label><br>
                                <input type="file" id="arquivo" name="arquivo" accept="image/png, image/jpg, image/jpeg">
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="arquivo">Documento (pdf)</label><br>
                                <input type="file" id="documento" name="documento" accept="application/pdf">
                            </div>
                            <div class="form-group col-xs-12 col-lg-8">
                                <label for="preview">Preview</label><br>
                                <img name="preview" id="preview" src="{{route('imagecache',['geral','no-image.jpeg'])}}" style="width: 200px; height: 180px;">
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box box-primary">
                            <div class="box-footer">
                                <button type="submit" name="botao" value="news" class="btn btn-primary">Salvar</button>
                                @if($conta != null)
                                    <a href="JavaScript: window.history.back();" class="btn btn-default pull-right">Voltar</a>
                                @else
                                    <a href="{{ route('operacao-historico.index') }}" class="btn btn-default pull-right">Voltar</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div><!-- /.box -->
            </form>
        </div>   <!-- /.row -->
    </section><!-- /.content -->
@endsection

@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css')}}">
    <style>
        input[type=file] {
            float: left;
            display: block;
        }
        .hide {
            display: none;
            float: left;
        }
    </style>
@endsection

@section('script')
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/i18n/pt-BR.js') }}"></script>

    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
    <script src="{{ asset('plugins/datepicker/locales/bootstrap-datepicker.pt-BR.js')}}"></script>

    <!-- InputMask -->
    <script src="../../plugins/input-mask/jquery.inputmask.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

    <script>
        $("input[name='valor']").maskMoney({
            allowNegative:true,
            allowZero:true
        });

        $("input[name='percentual']").maskMoney({
            decimal:".",
            thousands:false,
            precision:4,
            allowNegative:true,
            allowZero:true
        });

        $(function () {
            //Initialize Select2 Elements
            //$(".select2").select2();

            preencherContas();
        });

        $(function () {
            $("input[name='data']").inputmask({
                mask: '99/99/9999',
                showTooltip: true,
                showMaskOnHover: true
            });

            $.fn.datepicker.defaults.language = 'pt-BR';

            $('.datepicker').datepicker({
                format: 'dd/mm/yyyy'
            });
        });

        function readURL() {
            $('input[type=file]').each(function(index){
                if ($('input[type=file]').eq(index).val() != ""){
                    if (this.files && this.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#preview')
                                .attr('src', e.target.result)
                        };
                        reader.readAsDataURL(this.files[0]);
                    }
                    else {
                        var img = this.value;
                        $('#preview').attr('src',img);
                    }
                }else{
                    $('#preview').attr('src', '{!! $noimage !!}');
                }
            });
        }

        $('input[type=file][name=arquivo]').on("change", function(){
            readURL()
        });

        $('#plataforma').change(function(e){
            preencherContas();
        });

        function preencherContas() {
            var plataformaId = $('#plataforma').val();
            $.getJSON('/api/plataforma/contas?plataforma_id=' + plataformaId,
            function (dados){
                if (dados.length > 0){
                    var option = '<option value="">Selecione a conta </option>';
                    var selecionado = '{!! old('plataforma_conta_id') !!}'
                    $.each(dados, function (index, value) {
                        var selected = '';
                        if (value.id == selecionado)
                            selected = 'selected';
                        option += '<option value="' + value.id + '" ' + selected + '>' + value.nome + '</option>';
                    });
                }else{
                    Reset();
                }
                $('#conta').html(option).show();
            });
        }

        function Reset(){
            $('#conta').empty().append('<option value="">Selecione a conta</option>');
        }
    </script>
@endsection

