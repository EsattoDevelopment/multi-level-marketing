<li class="treeview {{ (strpos(Route::currentRouteName(), 'download') !== false) ? 'active' : '' }}">
    <a href="{{ route('download.show',1) }}">
        <i class="glyphicon glyphicon-download"></i>
        <span>Downloads</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        @foreach($downloads as $tipo)
            <li class="@if(Request::is("download/{$tipo['id']}")) active @endif"><a href="{{ route('download.show', ['tipo' => $tipo]) }}"> <i class="fa fa-arrow-circle-o-right"></i> {{ $tipo->titulo }}</a></li>
        @endforeach
    </ul>
</li>