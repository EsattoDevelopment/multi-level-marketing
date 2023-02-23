@extends('default.layout.main')

@section('content')
    <section class="content">

        @include('default.errors.errors')

        <div class="row">
            <form role="form" action="{{ route('operacao-historico.update', $dados->id) }}" method="post" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edição do Histórico de Operação</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-xs-12 col-lg-2">
                                <label for="status">Status</label> <br>
                                <div class="btn-group" data-toggle="buttons">
                                    <label class="btn btn-primary {{ old('status', $dados->status) == 1 ? 'active' : $dados->status == 1 ? 'active' : '' }}">
                                        <input type="radio" value="1" {{ old('status', $dados->status) == 1 ? 'checked' : $dados->status == 1 ? 'checked' : ''  }} name="status"
                                               autocomplete="off">Ativo
                                    </label>
                                    <label class="btn btn-primary {{ old('status', $dados->status) == 0 ? 'active' : $dados->status == 0 ? 'active' : '' }}">
                                        <input type="radio" value="0" {{ old('status', $dados->status) == 0 ? 'checked' : $dados->status == 0 ? 'checked' : ''  }} name="status" autocomplete="off">Inativo
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-2">
                                <label for="data">Data</label>
                                <input type="text" name="data"
                                       value="{{ old('data', $dados->data) }}"
                                       class="form-control datepicker" id="data"
                                       placeholder="Data">
                            </div>
                            <div class="form-group col-xs-12 col-lg-4">
                                <label for="exampleInputEmail1">Valor</label>
                                <div class="input-group">
                                    <span class="input-group-addon">{{$sistema->moeda}}</span>
                                    <input type="text" class="form-control" name="valor"
                                           value="{{ old('valor', mascaraMoeda($sistema->moeda, $dados->valor, 2)) }}" placeholder="Informe o valor"
                                           data-affixes-stay="true">
                                </div>
                            </div>
                            <div class="form-group col-xs-12 col-lg-4">
                                <label for="exampleInputEmail1">Percentual</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="percentual"
                                           value="{{ old('percentual', $dados->percentual) }}" placeholder="Informe o percentual"
                                           data-affixes-stay="true" data-prefix="" data-thousands=","
                                           data-decimal=".">
                                </div>
                            </div>
                            <div class="form-group col-xs-12">
                                <label>Plataforma</label>
                                <select id="plataforma" class="form-control select2"  name="plataforma_id" data-placeholder="Selecione a plataforma" style="width: 100%;">
                                    <option value="">Selecione a plataforma</option>
                                    @foreach($plataforma as $item)
                                        <option @if(old('plataforma_id', $item->id) == $conta->plataforma_id) selected @endif value="{{ $item->id }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12 col-lg-12">
                                <label>Conta</label>
                                <select id="conta" class="form-control select2"  name="plataforma_conta_id" data-placeholder="Selecione a conta" style="width: 100%;">
                                    <option value="">Selecione a conta</option>
                                    @foreach($contaPlataforma as $item)
                                        <option @if(old('plataforma_conta_id', $item->id) == $conta->id) selected @endif value="{{ $item->id }}">{{ $item->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="titulo">Titulo</label>
                                <input type="text" name="titulo" value="{{ old('titulo', $dados->titulo) }}" class="form-control" placeholder="Titulo">
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="descricao">Descrição</label>
                                <textarea name="descricao" class="form-control" rows="5" placeholder="Descrição">{{ old('descricao', $dados->descricao) }}</textarea>
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="arquivo">Imagem (png, jpg, jpeg)</label><br>
                                <input type="file" id="arquivo" name="arquivo" accept="image/png, image/jpg, image/jpeg">
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                <label for="arquivo">Documento (pdf)</label><br>
                                <input type="file" id="documento" name="documento" accept="application/pdf">
                            </div>
                            <div class="form-group col-xs-12 col-lg-3">
                                @if($dados->path_imagem != null && $dados->path_imagem != '')
                                    <label style="padding-right: 25px">
                                        <input type="checkbox" value="1" name="excluir_arquivo" class="flat-red" {{ old('excluir_arquivo')  == 1 ? 'checked' : '' }}>
                                        <small>Excluir imagem atual</small>
                                    </label>
                                    <br>
                                @endif
                                <label for="preview1">Preview imagem atual</label><br>
                                <img name="preview1" id="preview1" src="@if($dados->path_imagem){{ route('imagecache',['geral',$dados->path_imagem])}}@else {{route('imagecache',['geral','no-image.jpeg'])}} @endif" style="max-height: 180px;">
                            </div>
                            <div class="form-group col-xs-12 col-lg-3">
                                @if($dados->path_imagem != null && $dados->path_imagem != '')
                                    <label style="padding-right: 25px">
                                        <small>Imagem nova</small>
                                    </label>
                                    <br>
                                @endif
                                <label for="preview1">Preview imagem nova</label><br>
                                <img name="preview" id="preview" src="{{ route('imagecache',['geral','no-image.jpeg']) }}" style="max-height: 180px;">
                            </div>
                            <div class="form-group col-xs-12 col-lg-6">
                                @if($dados->path_documento != null && $dados->path_documento != '')
                                    <label style="padding-right: 25px">
                                        <input type="checkbox" value="1" name="excluir_documento" class="flat-red" {{ old('excluir_documento')  == 1 ? 'checked' : '' }}>
                                        <small>Excluir documento atual</small>
                                    </label><br>
                                    <label for="preview">Download de documento</label><br>
                                    <a target="_blank" href="{{route('operacao-historico-documento', $dados->path_documento)}}">Clique aqui para fazer download do documento</a>
                                @endif
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box box-primary">
                            <div class="box-footer">
                                <input type="hidden" name="_method" value="PUT">
                                <button type="submit" name="botao" value="news" class="btn btn-primary">Salvar</button>
                                <a href="{{ route('operacao-historico.index') }}" class="btn btn-default pull-right">Voltar</a>
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

