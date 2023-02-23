<div class="col-xs-6 col-sm-3 box-img col-md-4 col-lg-2" id="image-{{ $imagem->id }}">
    <a href="#" class="thumbnail @if($imagem->principal == 1) thumbnail-principal @endif">
        <img src="{{ route('imagecache', ['thumb', $imagem->caminho . $imagem->name]) }}" >
    </a>


    <nav class="navbar navbar-imagens navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <input type="hidden" name="url-delete" value="{{ route('galeria.deleteImg', $imagem->id) }}">
                <input type="hidden" name="url-legenda" value="{{ route('galeria.legenda', $imagem->id) }}">
                <input type="hidden" name="url-principal" value="{{ route('galeria.imagem.principal', $imagem->id) }}">
                <a href="#" data-id="{{ $imagem->id }}" title="Principal" class="btn btn-default navbar-btn"><span class="glyphicon glyphicon-home" aria-hidden="true"></span></a>
                <a href="#" data-id="{{ $imagem->id }}" data-legenda="{{ empty($imagem->legenda) ? 'Não há legenda!' : $imagem->legenda }}" title="Legenda" class="btn btn-default navbar-btn"><span class="glyphicon glyphicon-text-color text-blue" aria-hidden="true"></span></a>
                <a href="#" data-id="{{ $imagem->id }}" title="Apagar" class="btn btn-danger navbar-btn"><span class="glyphicon glyphicon-trash text-default" aria-hidden="true"></span></a>
            </div>
        </div>
    </nav>
</div>