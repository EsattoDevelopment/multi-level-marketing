<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="@if(Auth::user()->image){{ route('imagecache', ['user', 'user/'.Auth::user()->image]) }}@else{{ route('imagecache', ['user', 'user-img.jpg']) }}@endif" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user()->name }}</p>
                {{--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>--}}
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
            @if($usuarioComum)
                <li class="treeview @if(strpos(Route::currentRouteName(), 'dados-usuario') !== false) active @endif">
                    <a href="{{ route('dados-usuario.show') }}">
                        <i class="fa fa-file-text"></i>
                        <span>Dados Cadastrais</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
            @endif

            <li class="treeview">
                <a href="{{ route('saude.dependentes.index') }}">
                    <i class="fa fa-user-plus"></i>
                    <span>Dependentes</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pedido') !== false)) active @endif">
                <a href="#">
                    <i class="fa fa-medkit"></i> <span>Pacotes</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('pedido.create') }}"><i class="fa fa-plane"></i> Novo pacote</a></li>
                    {{--       <li><a href="{{ route('pedido.usuario.pedidos', Auth::user()->id) }}"><i class="fa fa-ship"></i> Meus pedidos</a></li>
                           <li><a href="{{ route('pedido.bonus') }}"><i class="text-yellow fa fa-truck"></i> Pagar pedido com bônus</a></li>--}}
                    @if($master || $admin)
                        <li class="header"></li>
                        <li><a href="{{ route('pedido.aguardando-pagamento') }}"><i class="fa fa-truck"></i> Aguardando Pagamento</a></li>
                        <li><a href="{{ route('pedido.aguardando-confirmacao') }}"><i class="text-yellow fa fa-truck"></i> Aguardando Confirmação</a></li>
                        <li><a href="{{ route('pedido.pagos') }}"><i class="text-green fa fa-truck"></i> Pagos</a></li>
                        <li><a href="{{ route('pedido.cancelados') }}"><i class="text-red fa fa-truck"></i> Cancelados</a></li>
                        <li><a href="{{ route('pedido.index') }}"><i class="fa fa-truck"></i> Todos pedidos</a></li>
                    @endif
                </ul>
            </li>
            {{-- <li class="treeview @if(strpos(Route::currentRouteName(), 'usar-gmilhas') !== false)) active @endif">

                 <a href="#">
                     <i class="fa fa-plane"></i> <span>Usar GMilhas<i class="fa fa-registered"></i></span> <i class="fa fa-angle-left pull-right"></i>
                 </a>
                 <ul class="treeview-menu">
                     <li><a href="{{ route('usar-gmilhas.hospedagem') }}"><i class="fa fa-building"></i> Hospedagem</a></li>
                     <li><a href="{{ route('usar-gmilhas.pacote') }}"><i class="fa fa-plane"></i> Pacotes viagens</a></li>
                     <li><a href="{{ route('usar-gmilhas.cruzeiro') }}"><i class="fa fa-ship"></i> Cruzeiros</a></li>
                 </ul>
             </li>--}}
            {{--<li class="treeview @if(strpos(Route::currentRouteName(), 'reservas') !== false)) active @endif">
                <a href="#">
                    <i class="fa fa-plane"></i> <span>Reservas</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('reservas.hospedagem') }}"><i class="fa fa-building"></i> Hospedagem</a></li>
                    <li><a href="{{ route('reservas.pacotes') }}"><i class="fa fa-plane"></i> Pacotes viagens</a></li>
                    <li><a href="{{ route('reservas.cruzeiros') }}"><i class="fa fa-ship"></i> Cruzeiros</a></li>
                </ul>
            </li>--}}
            @if($master || $usuarioComum)
                <li class="treeview @if(strpos(Route::currentRouteName(), 'extrato') !== false)) active @endif">
                    <a href="#">
                        <i class="fa fa-file-text-o"></i> <span>Extratos</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('extrato.saldo') }}"><i class="text-green fa fa-money"></i> Financeiro</a></li>
                        <li><a href="{{ route('extrato.milhas') }}"><i class="text-yellow fa fa-plane"></i> GMilhas</a></li>
                        <li><a href="{{ route('extrato.pv') }}"><i class="text-purple fa fa-plus"></i> PV</a></li>
                    </ul>
                </li>
                {{--<li class="treeview @if(strpos(Route::currentRouteName(), 'user') !== false) active @endif">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span>Meus clubes</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('user.pendentes') }}"><i class="fa fa-building"></i> Indicados pendentes
                                <span class="pull-right-container">
                              <small class="label pull-right bg-red">{{ Auth::user()->pendentes()->count() }}</small>
                            </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.diretos') }}"><i class="fa fa-sort-amount-asc"></i> Assoc. indicados diretos</a>
                        </li>
                        @if(Auth::user()->status)
                            <li>
                                <a href="{{ route('user.rede-binaria') }}"><i class="glyphicon glyphicon-tower"></i> Clube norte e sul</a>
                            </li>
                        @endif
                        @if(Auth::user()->titulo_id < 6)
                            <li>
                                <a href="{{ route('hotel.index') }}"><i class="glyphicon glyphicon-tower"></i>Hotéis H.VIP</a>
                            </li>
                        @endif
                    </ul>
                </li>--}}
                <li class="treeview @if(strpos(Route::currentRouteName(), 'download') !== false) active @endif">
                    <a href="{{ route('download.show',1) }}">
                        <i class="glyphicon glyphicon-download"></i>
                        <span>Downloads</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
            @endif
            @if($master || $admin)
                <li class="header">ADMINISTRAÇÃO</li>
                <li class="treeview @if(strpos(Route::currentRouteName(), 'galeria') !== false)) active @endif" >
                    <a href="javascript:;">
                        <i class="glyphicon glyphicon-picture"></i>
                        <span>Galerias</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('galeria.index') }}"><i class="fa fa-list"></i> Lista</a></li>
                    </ul>
                </li>

                <li class="treeview @if((strpos(Route::currentRouteName(), 'user') !== false) || (strpos(Route::currentRouteName(), 'permission') !== false) || (strpos(Route::currentRouteName(), 'role') !== false)) active @endif">
                    <a href="#">
                        <i class="fa fa-user"></i> <span>Usuários</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('user.index') }}"><i class="fa fa-users"></i> Lista Usuários</a></li>
                        <li><a href="{{ route('permission.index') }}"><i class="fa fa-circle-o"></i> Permissões</a></li>
                        <li><a href="{{ route('role.index') }}"><i class="fa fa-circle-o"></i> Regras</a></li>
                    </ul>
                </li>

                <li class="treeview @if(strpos(Route::currentRouteName(), 'titulo') !== false) active @endif">
                    <a href="{{ route('titulo.index') }}">
                        <i class="fa fa-certificate"></i>
                        <span>Titulos</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
                {{--<li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                    <a href="javascript:;">
                        <i class="fa fa-plane"></i>
                        <span>Cadastro uso GMilhas<i class="fa fa-registered"></i></span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ route('pacotes.hospedagem.index') }}"><i class="fa fa-building"></i> Hospedagens</a></li>
                        <li><a href="{{ route('pacotes.pacote.index') }}"><i class="fa fa-plane"></i> Pacotes</a></li>
                        <li><a href="{{ route('pacotes.cruzeiro.index') }}"><i class="fa fa-ship"></i> Cruzeiros</a></li>
                    </ul>
                </li>--}}
                <li class="treeview @if(strpos(Route::currentRouteName(), 'item') !== false) active @endif">
                    <a href="{{ route('item.index') }}">
                        <i class="fa fa-cubes"></i>
                        <span>Itens</span>
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
                        <li><a href="{{ route('empresa.edit', 1) }}"><i class="fa fa-building"></i> Empresa</a></li>
                        <li><a href="{{ route('contas_empresa.index') }}"><i class="fa fa-bank"></i> Contas</a></li>
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
                        <li><a href="{{ route('boleto.retorno') }}"><i class="fa fa-building"></i> Receber boletos</a></li>
                        <li><a href="{{ route('movimento.create') }}"><i class="fa fa-building"></i> Inserir movimento</a></li>
                        <li><a target="_blank" href="{{ route('relatorio.pagamento') }}"><i class="fa fa-money"></i>Relatório para pagamento</a></li>
                    </ul>
                </li>
            @endif

            {{--Cadastro Novo--}}

            <li class="header">ADMINISTRAÇÃO</li>
            @permission('usuario-comum')
            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Dados Cadastrais</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Dados</a></li>
                    <li><a href="#"><i class="fa fa-plane"></i> Dependentes</a></li>
                    <li><a href="#"><i class="fa fa-plane"></i> Funcionários</a></li>
                </ul>
            </li>
            @endpermission

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Cadastros</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Usuários</a></li>
                    <li><a href="#"><i class="fa fa-plane"></i> Especialidades</a></li>
                    <li><a href="#"><i class="fa fa-ship"></i> Procedimentos</a></li>
                    <li><a href="#"><i class="fa fa-ship"></i> Médicos</a></li>
                    <li><a href="#"><i class="fa fa-ship"></i> Seguradora</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Produtos</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Novo</a></li>
                    <li><a href="#"><i class="fa fa-plane"></i> Consultar</a></li>
                    <li><a href="#"><i class="fa fa-ship"></i> Categoria</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Pedido</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Novo</a></li>
                    <li><a href="#"><i class="fa fa-plane"></i> Consultar</a></li>
                    <li><a href="#"><i class="fa fa-plane"></i> Novo Pedido </a></li>
                    <li><a href="#"><i class="fa fa-plane"></i> Up Grade </a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Agendamento</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Novo</a></li>
                    <li><a href="#"><i class="fa fa-plane"></i> Consultar</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Rede hierárquica</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Diretos Ativos</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Diretos Inativos</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Rede</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Extrato</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Financeiro</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Pontos Volume</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Ponto Pessoal</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Relatórios</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Usuários</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Faturamento</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Inadimplentes</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Bonificações Pagas</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Consultas Atendidas</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Exames Atendidos</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Configurações</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Empresa</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Status Pessoal</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Procedimentos</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Seguro de Vida</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Bônus Unilevel Primeiro Pedido</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Bônus Unilevel Mensal</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Bônus Binário</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Plano de Carreira</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Logística</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Pedidos</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Consultas</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Exames</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Atendimento</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Arquivo da Seguradora</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Lançamentos </span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Código de Rastreamento </a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Financeiro</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> GMilhas Pessoais </a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Pontos Volume </a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Contrato </span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Novo</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Lista</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Biblioteca </span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Novo</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Lista</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Mensagem </span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Novo</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Lista</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Video </span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Novo</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Lista</a></li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pacotes') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-plane"></i>
                    <span>Atendimento </span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-building"></i> Novo</a></li>
                    <li><a href="#"><i class="fa fa-building"></i> Consultar</a></li>
                </ul>
            </li>

            {{--<li class="header">OUTROS</li>
            <li><a href="{{ route('home') }}"><i class="fa fa-mail-reply"></i> <span>Voltar p/ Site</span></a></li>--}}
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
