<li>
    <i class="fa fa-money bg-green"></i>
    <div class="timeline-item">
        <span class="time"><i class="fa fa-clock-o"></i> {{ $line->created_at->diffForHumans() }}</span>


        <h3 class="timeline-header no-border"><a href="{{ route('capitalizacao.index') }}">Correção</a> de Capital de {{ $line->rentabilidade ? $line->rentabilidade->percentual * 100 . '% ou ' : '' }}{{ mascaraMoeda($sistema->moeda, $line->valor_manipulado, 2, true) }}
            <a href="javascript:;">sobre</a> o depósito N°{{ $line->pedido->id }} de {{ mascaraMoeda($sistema->moeda, $line->pedido->valor_total, 2, true) }}</h3>
    </div>
</li>
