<div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user"></i> Responsavel</h3> <br>
            <small class="text-red">O usuário é menor de idade, deste modo deve colocar os dados do Responsável judicialmente.</small>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
            <div class="form-group col-sm-6 col-md-6">
                <label>Nome</label>
                <input type="text" {{ $usuario->validado ? 'disabled="disabled"' : 'required' }} name="responsavel[nome]" class="form-control" value="{{ old('responsavel.nome', $responsavel ? $responsavel->nome : '') }}">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                <label>E-mail</label>
                <input type="text" {{ $usuario->validado ? 'disabled="disabled"' : 'required' }} name="responsavel[email]" class="form-control" value="{{ old('responsavel.email', $responsavel ? $responsavel->email : '') }}">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                <label>CPF</label>
                <input type="text" {{ $usuario->validado ? 'disabled="disabled"' : 'required' }} name="responsavel[cpf]" class="form-control" value="{{ old('responsavel.cpf', $responsavel ? $responsavel->cpf : '') }}">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                <label>RG</label>
                <input type="text" {{ $usuario->validado ? 'disabled="disabled"' : 'required' }} name="responsavel[rg]" class="form-control" value="{{ old('responsavel.rg', $responsavel ? $responsavel->rg : '') }}">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                <label>Data Nascimento</label>
                <input type="date" {{ $usuario->validado ? 'disabled="disabled"' : 'required' }} name="responsavel[data_nasc]" class="form-control" value="{{ old('responsavel.data_nasc', $responsavel ? $responsavel->data_nasc->format('Y-m-d') : '') }}">
            </div>
            <div class="form-group col-sm-6 col-md-6">
                <label>Estado Civil</label>
                <select class="form-control select2" {{ $usuario->validado ? 'disabled="disabled"' : 'required' }} name="responsavel[estado_civil]"
                        data-placeholder="Selecione um estado civil" style="width: 100%;">
                    @foreach(config('constants.estado_civil') as $key => $value)
                        <option @if(old('responsavel.estado_civil', $responsavel ? $responsavel->estado_civil : 0) == $key) selected
                                @endif value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-sm-6 col-md-6">
                <label>Telefone</label>
                <input type="text" required name="responsavel[telefone]" class="form-control" value="{{ old('responsavel.telefone', $responsavel ? $responsavel->telefone : '') }}">
            </div>
            <input type="hidden" name="responsavel[user_id]" value="{{ Auth::user()->id }}">
{{--            <div class="form-group col-sm-6 col-md-3">
                <label for="image">Foto</label><br>
                <button id="imagem_responsavel" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar foto e enviar</button>
                <div id="progress_imagem" class="progress active" style="display: none;">
                    <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                </div>
            </div>
            <div class="form-group col-sm-6 col-md-3">
                <label for="image">Documento com Foto</label><br>
                <button id="documento_responsavel" type="button" class="btn btn btn-primary"><i class="fa fa-upload"></i> Selecionar foto e enviar</button>
                <div id="progress_imagem" class="progress active" style="display: none;">
                    <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                </div>
            </div>--}}
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>