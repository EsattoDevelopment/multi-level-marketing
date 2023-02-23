<li>
    <i class="fa fa-video-camera bg-maroon"></i>

    <div class="timeline-item">
        <span class="time"><i class="fa fa-clock-o"></i> {{ $line->created_at->diffForHumans() }}</span>

        <h3 class="timeline-header"><a href="#">{{ $line->nome }}</a> </h3>

        <div class="timeline-body">
            @if($line->tipo == 1)
                <a data-fancybox href="https://www.youtube.com/watch?v={{$line->codigo}}?autoplay=1">
                    <img id="{{$line->id}}" src="{{$line->capa}}" alt="" width="100%" height="100%" style="max-width: 300px;">
                </a>
            @elseif($line->tipo == 2)
                <a data-fancybox href="https://vimeo.com/{{$line->codigo}}?autoplay=1">
                    <img id="{{$line->id}}" src="{{$line->capa}}" alt="" width="100%" height="100%" style="max-width: 300px;">
                </a>
            @elseif($line->tipo == 0 && $line->codigo)
                <a data-fancybox href="{{$line->codigo}}">
                    <video id="{{$line->id}}" width="100%" height="auto">
                        <source src="{{$line->codigo}}" type="video/mp4">
                    </video>
                </a>
            @endif

            <p>
                <br>
                {!! $line->descricao !!}
            </p>
        </div>
    </div>
</li>