<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="@if(Auth::user()->image){{ route('imagecache', ['user', 'user/'.Auth::user()->image]) }}@else{{ route('imagecache', ['user', 'user-img.jpg']) }}@endif"
                     class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">NAVEGAÇÃO</li>
            <li class="treeview">
                <a href="{{ route('home') }}">
                    <i class="fa fa-black-tie"></i>
                    <span>Home</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>

            <li class="treeview">
                <a href="{{ route('saude.dependentes.index') }}">
                    <i class="fa fa-user-plus"></i>
                    <span>Dependentes</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pedido') !== false)) active @endif">
                <a href="#">
                    <i class="fa fa-medkit"></i> <span>Adesão</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('pedido.aguardando-pagamento') }}"><i class="fa fa-truck"></i> Aguardando
                            Pagamento</a></li>
                    <li><a href="{{ route('pedido.aguardando-confirmacao') }}"><i class="text-yellow fa fa-truck"></i>
                            Aguardando Confirmação</a></li>
                    <li><a href="{{ route('pedido.pagos') }}"><i class="text-green fa fa-truck"></i> Pagos</a></li>
                    <li><a href="{{ route('pedido.cancelados') }}"><i class="text-red fa fa-truck"></i> Cancelados</a>
                    </li>
                </ul>
            </li>


            <li class="treeview @if(strpos(Route::currentRouteName(), 'contratos') !== false &&  strpos(Route::currentRouteName(), 'minha-rede') === false)) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-pencil"></i> <span>Contratos</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('contratos.index') }}"><i class="fa fa-circle-o"></i> Aguardando liberação</a></li>
                    <li><a href="{{ route('contratos.abertos') }}"><i class="fa fa-circle-o"></i> Liberados/em Aberto</a></li>
                    <li><a href="{{ route('contratos.atrasados') }}"><i class="text-red fa fa-circle-o"></i> Atrasados</a></li>
                    <li><a href="{{ route('contratos.finalizando') }}"><i class="fa fa-circle-o"></i> Em finalização</a></li>
                    <li><a href="{{ route('contratos.finalizado') }}"><i class="fa fa-circle-o"></i> Finalizados</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'download') !== false) active @endif">
                <a href="{{ route('download.show',1) }}">
                    <i class="glyphicon glyphicon-download"></i>
                    <span>Downloads</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'videos') !== false) active @endif">
                <a href="{{ route('download.show',1) }}">
                    <i class="glyphicon glyphicon-play-circle"></i>
                    <span>Vídeos</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>

            <li class="header">ADMINISTRAÇÃO</li>

            <li class="treeview @if((strpos(Route::currentRouteName(), 'user') !== false) || (strpos(Route::currentRouteName(), 'permission') !== false) || (strpos(Route::currentRouteName(), 'role') !== false)) active @endif">
                <a href="#">
                    <i class="fa fa-user"></i> <span>Usuários</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('user.index') }}"><i class="fa fa-users"></i> Lista Usuários</a></li>
                    <li><a href="{{ route('user.consultor') }}"><i class="fa fa-users"></i> Lista de agentes</a></li>
                    <li><a href="{{ route('user.disabled') }}"><i class="fa fa-users"></i> Lista Usuários desabilitados</a></li>
                    <li><a href="{{ route('user.inativo') }}"><i class="fa fa-users"></i> Lista Usuários inativos</a></li>
                    <li><a href="{{ route('user.inadimplente') }}"><i class="fa fa-users text-red"></i> Lista Usuários inadimplente</a></li>
                    <li><a href="{{ route('user.finalizado') }}"><i class="fa fa-users text-yellow"></i> Lista Usuários finalizados</a></li>
                </ul>
            </li>


            <li class="treeview @if(strpos(Route::currentRouteName(), 'item') !== false) active @endif">
                <a href="{{ route('item.index') }}">
                    <i class="fa fa-cubes"></i>
                    <span>Planos</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>
            <li class="treeview @if((strpos(Route::currentRouteName(), 'empresa') !== false) || (strpos(Route::currentRouteName(), 'contas_empresa') !== false)) active @endif">
                <a href="#">
                    <i class="fa fa-wrench"></i>
                    <span>Configurações</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    {{--<li><a href="{{ route('empresa.edit', 1) }}"><i class="fa fa-building"></i> Empresa</a></li>--}}
                    <li><a href="{{ route('contas_empresa.index') }}"><i class="fa fa-bank">--}}</i> Contas</a></li>
                    <li><a href="{{ route('download.index') }}"><i class="fa fa-bank"></i> Cadastro Downloads</a></li>
                </ul>
            </li>
            <li class="treeview @if((strpos(Route::currentRouteName(), 'boleto') !== false) || (strpos(Route::currentRouteName(), 'movimento') !== false))) active @endif">
                <a href="#">
                    <i class="fa fa-wrench"></i>
                    <span>Financeiro</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview @if((strpos(Route::currentRouteName(), 'boletos') !== false))) active @endif">
                        <a href="#"><i class="fa fa-building"></i> Boletos</a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('boleto.retorno') }}"><i class="fa fa-building"></i> Receber boletos</a></li>
                            <li><a href="{{ route('boletos.adesoes') }}"><i class="fa fa-building"></i> Adesões</a></li>
                            <li><a href="{{ route('boletos.mensalidades') }}"><i class="fa fa-building"></i> Mensalidades</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('movimento.create') }}"><i class="fa fa-building"></i> Inserir movimento</a>
                    </li>
                    <li><a target="_blank" href="{{ route('relatorio.pagamento') }}"><i class="fa fa-money"></i>Relatório
                            para pagamento</a></li>
                </ul>
            </li>

            <li class="treeview ">
                <a href="javascript:;">
                    <i class="fa fa-file-text-o"></i>
                    <span>Relatórios</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('relatorio.usuarios') }}"><i class="fa fa-building"></i> Usuários</a></li>
                    <li><a href="{{ route('relatorio.faturamento') }}"><i class="fa fa-building"></i> Faturamento</a></li>
                    <li><a href="{{ route('relatorio.pagamento-diarios') }}"><i class="fa fa-building"></i> Recebimentos</a></li>
                    <li><a href="{{ route('relatorio.consultor') }}"><i class="fa fa-building"></i> Bonificações pagas</a></li>
                    <li><a href="{{ route('relatorio.inadimplentes') }}"><i class="fa fa-users"></i> Usuários inadimplentes</a></li>
                </ul>
            </li>
        </ul>
    </section>
</aside>