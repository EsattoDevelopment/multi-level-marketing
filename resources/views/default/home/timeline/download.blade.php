<li>
    <i class="fa {{ in_array($line->extensao, ['jpg', 'jpeg', 'png']) ? 'fa-image bg-yellow' : 'fa-download bg-blue' }}"></i>

    <div class="timeline-item">
        <span class="time"><i class="fa fa-clock-o"></i> {{ $line->created_at->diffForHumans() }} </span>

        <h3 class="timeline-header"><a href="javascript:;">{{ $line->title }}</a> <i class="label bg-primary pull-right">{{ $line->tipo->titulo }}</i> </h3>
        <div class="timeline-body">
            @if(in_array($line->extensao, ['jpg', 'jpeg', 'png']))
                <a data-fancybox href="{{ route('imagecache',['background', $line->nomeArquivo]) }}">
                    <img name="preview" id="preview" src="{{ route('imagecache',['img-download', $line->nomeArquivo]) }}">
                </a>
            @endif
            <br>
            {!! $line->descricao !!}
        </div>

        <div class="timeline-footer">
            <a href="{{ route('download.download', [$line->id, $line->nomeArquivo]) }}" class="btn btn-primary btn-xs">Download</a>
        </div>
    </div>
</li>