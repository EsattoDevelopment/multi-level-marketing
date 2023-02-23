<div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Informe seu endereço de correspondência</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
            <div class="form-group col-md-3">
                <label for="exampleInputEmail1">CEP @if(!$hasPedido) <strong class="text-red">*</strong>@endif</label>
                <input type="text" {{ $sistema->endereco_obrigatoria ? 'required' : '' }} name="endereco[cep]"
                       value="{{ old('endereco.cep', isset($endereco->cep) ? $endereco->cep : '') }}"
                       class="form-control" placeholder="CEP" @if($hasPedido) readonly @endif>
            </div>
            <div class="form-group col-md-7">
                <label for="exampleInputEmail1">Endereço @if(!$hasPedido) <strong class="text-red">*</strong>@endif</label>
                <input type="text" name="endereco[logradouro]"
                       value="{{ old('endereco.logradouro', isset($endereco->logradouro) ? $endereco->logradouro : '') }}"
                       class="form-control" placeholder="Endereço" @if($hasPedido) readonly @endif>
            </div>
            <div class="form-group col-md-2">
                <label for="exampleInputPassword1">Numero @if(!$hasPedido) <strong class="text-red">*</strong>@endif</label>
                <input type="text" name="endereco[numero]"
                       value="{{ old('endereco.numero', isset($endereco->numero) ? $endereco->numero : '') }}"
                       class="form-control" placeholder="Numero" @if($hasPedido) readonly @endif>
            </div>
            <div class="form-group col-md-3">
                <label for="exampleInputPassword1">Bairro @if(!$hasPedido) <strong class="text-red">*</strong>@endif</label>
                <input type="text" name="endereco[bairro]"
                       value="{{ old('endereco.bairro', isset($endereco->bairro) ? $endereco->bairro : '') }}"
                       class="form-control" placeholder="Bairro" @if($hasPedido) readonly @endif>
            </div>
            <div class="form-group col-md-6">
                <label for="exampleInputPassword1">Cidade @if(!$hasPedido) <strong class="text-red">*</strong>@endif</label>
                <input type="text" name="endereco[cidade]"
                       value="{{ old('endereco.cidade', isset($endereco->cidade) ? $endereco->cidade : '') }}"
                       class="form-control" placeholder="Cidade" @if($hasPedido) readonly @endif>
            </div>
            <div class="form-group col-md-3">
                <label for="exampleInputPassword1">Estado @if(!$hasPedido) <strong class="text-red">*</strong>@endif</label>
                <input type="text" name="endereco[estado]"
                       value="{{ old('endereco.estado', isset($endereco->estado) ? $endereco->estado : '') }}"
                       class="form-control" placeholder="Estado" @if($hasPedido) readonly @endif>
            </div>
            <div class="form-group col-md-12">
                <label for="exampleInputPassword1">Complemento</label>
                <input type="text" name="endereco[complemento]"
                       value="{{ old('endereco.complemento', isset($endereco->complemento) ? $endereco->complemento : '') }}"
                       class="form-control" placeholder="Complemento" @if($hasPedido) readonly @endif>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>