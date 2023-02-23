<li class="treeview @if((strpos(Route::currentRouteName(), 'documentos') !== false)) active @endif">
    <a href="#">
        <i class="glyphicon glyphicon-file"></i>
        <span>Documentos</span>
        <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li @if(Route::currentRouteName() == "documentos.associado.nao-enviados") class="active" @endif><a href="{{ route('documentos.associado.nao-enviados') }}"><i class="glyphicon glyphicon-hand-left text-light-blue"></i>Não Enviados</a></li>
        <li @if(Route::currentRouteName() == "documentos.associado.aguardando" || Route::currentRouteName() == "documentos.associado.aguardando.visualizacao" || Route::currentRouteName() == "documentos.associado.aguardando.confirmacao") class="active" @endif><a href="{{ route('documentos.associado.aguardando') }}"><i class="glyphicon glyphicon-hand-right text-orange"></i>Aguardando Aprovação</a></li>
        <li @if(Route::currentRouteName() == "documentos.associado.aprovados" || Route::currentRouteName() == "documentos.associado.aprovados.visualizacao" || Route::currentRouteName() == "documentos.associado.aprovados.confirmacao") class="active" @endif><a href="{{ route('documentos.associado.aprovados') }}"><i class="glyphicon glyphicon-thumbs-up text-green"></i>Aprovados</a></li>
        <li @if(Route::currentRouteName() == "documentos.associado.reprovados" || Route::currentRouteName() == "documentos.associado.reprovados.visualizacao" || Route::currentRouteName() == "documentos.associado.reprovados.confirmacao") class="active" @endif><a href="{{ route('documentos.associado.reprovados') }}"><i class="glyphicon glyphicon-thumbs-down text-red"></i>Reprovados</a></li>
    </ul>
</li>