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
                <p>Agência: 0001 / Conta: {{ Auth::user()->conta}}</p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">NAVEGAÇÃO</li>
            <li class="treeview">
                <a href="{{ route('home') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Home</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>

            @if(!$master)
                <li class="treeview @if(strpos(Route::currentRouteName(), 'dados-usuario') !== false) active @endif">
                    <a href="{{ route('dados-usuario.show') }}">
                        <i class="fa fa-file-text"></i>
                        <span>Dados Cadastrais</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                </li>
            @endif

            {{-- empréstimos --}}
            <li
                class="treeview
                @if(strpos(Route::currentRouteName(), 'emprestimos.') !== false || Route::currentRouteName() === 'emprestimos') active @endif"
            >
                <a href="javascript:">
                    <i class="fa fa-money"></i>
                    <span>Empréstimos</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ route('emprestimos') }}">
                            <i class="fa fa-money"></i> Emprestimos
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('emprestimos.calculadora') }}">
                            <i class="fa fa-calculator"></i> Fazer simulação
                        </a>
                    </li>
                    @if(Auth::user()->can('emprestar') && !$master)
                        <li>
                            <a href="{{ route('emprestimos.pagar') }}">
                                <i class="fa fa-money"></i> Pagar
                            </a>
                        </li>
                    @endif
                    @if($master)
                        <li>
                            <a href="{{ route('emprestimos.pagar') }}">
                                <i class="fa fa-money"></i> Pagar
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('emprestimos.configuracoes') }}">
                                <i class="fa fa-cog"></i> Configuração
                            </a>
                        </li>
                    @endif
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'pedido') !== false && strpos(Route::currentRouteName(), 'pedidos.normal') === false)) active @endif">
                <a href="#">
                    <i class="fa fa-th-large"></i> <span>Depósitos</span> <i class="fa fa-angle-left pull-right"></i>
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

            <li class="treeview @if(strpos(Route::currentRouteName(), 'contratos.capital') !== false || strpos(Route::currentRouteName(), 'pedidos.normal') !== false) active @endif">
                <a href="#">
                    <i class="fa fa-th-large"></i> <span>Credenciamentos</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('pedidos.normal.aguardando.pagamento') }}"><i class="fa fa-truck"></i> Aguardando pagamento</a></li>
                    <li><a href="{{ route('pedidos.normal.aguardando.confirmacao') }}"><i class="fa fa-truck text-warning"></i> Aguardando confirmação</a></li>
                    <li><a href="{{ route('contratos.capital.ativos') }}"><i class="fa fa-truck text-green"></i> Pagos</a></li>
                    <li><a href="{{ route('contratos.capital.finalizados') }}"><i class="fa fa-truck text-orange"></i> Finalizados</a></li>
                    <li><a href="{{ route('pedidos.normal.cancelados') }}"><i class="fa fa-truck text-red"></i> Cancelados</a></li>
                </ul>
            </li>

            @include('default.layout.sidebar.documento')

            @if(!$master)
                <li class="treeview @if(strpos(Route::currentRouteName(), 'minha-rede') !== false) active @endif">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span>Minha rede</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">

                        <li>
                            <a href="{{ route('rede') }}"><i class="fa fa-sort-amount-asc"></i> Diretos
                                <span class="pull-right-container">
                              <small class="label pull-right bg-red">{{ Auth::user()->diretos->count() }}</small>
                            </span>
                            </a>
                        </li>
                        @if($sistema->rede_binaria)
                            <li>
                                <a href="{{ route('user.rede-binaria') }}"><i class="fa fa-sitemap"></i> Rede binária</a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('user.pendentes') }}"><i class="fa fa-arrow-circle-right"></i> Diretos pendentes
                                <span class="pull-right-container">
                              <small class="label pull-right bg-red">{{ Auth::user()->pendentes()->count() }}</small>
                            </span>
                            </a>
                        </li>
                    </ul>
                </li>

                @include('default.layout.sidebar.extratos')
            @endif

            @include('default.layout.sidebar.download')


            <li class="treeview @if(Route::currentRouteName() == 'videos.show') active @endif">
                <a href="#">
                    <i class="glyphicon glyphicon-play-circle"></i>
                    <span>Vídeos</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    @foreach(config('constants.videos_categorias') as $categoriaId => $categoria)
                        <li class="@if(Request::is("videos/show/{$categoriaId}")) active @endif"><a href="{{ route('videos.show',$categoriaId) }}"><i class="green fa fa-play-circle"></i> {{$categoria}}</a></li>
                    @endforeach
                </ul>
            </li>
            <li class="@if(Route::currentRouteName() == 'dados-usuario.seguranca' || strpos(Route::currentRouteName(), '2fa') !== false) active @endif"><a href="{{ route('dados-usuario.seguranca') }}"><i class="fa fa-lock"></i> Segurança</a></li>


            <li class="header">ADMINISTRAÇÃO</li>
            <li class="treeview @if((strpos(Route::currentRouteName(), 'user') !== false) || (strpos(Route::currentRouteName(), 'permission') !== false) || (strpos(Route::currentRouteName(), 'role') !== false)) active @endif">
                <a href="#">
                    <i class="fa fa-user"></i> <span>Usuários</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('user.index') }}"><i class="fa fa-users"></i> Todos</a></li>

                    <li><a href="{{ route('user.consultor') }}"><i class="fa fa-users"></i> Agentes</a></li>
                    <li><a href="{{ route('user.inativo') }}"><i class="fa fa-users"></i> Inativos</a></li>

                    @if($sistema->sistema_saude)
                        <li><a href="{{ route('user.clinica') }}"><i class="fa fa-users"></i> Lista de Clinicas</a></li>
                    @endif

                    <li><a href="{{ route('user.verificar.update.titulo') }}"><i class="fa fa-users"></i> Verificar update de títulos</a></li>
                    @if($master)
                        <li><a href="{{ route('permission.index') }}"><i class="fa fa-circle-o"></i> Permissões</a></li>
                        <li><a href="{{ route('role.index') }}"><i class="fa fa-circle-o"></i> Regras</a></li>
                    @endif
                </ul>
            </li>

            @if($master)
                <li class="treeview @if((strpos(Route::currentRouteName(), 'empresa') !== false) || (strpos(Route::currentRouteName(), 'contas_empresa') !== false) || (strpos(Route::currentRouteName(), 'sistema') !== false) || (strpos(Route::currentRouteName(), 'metodo_pagamento') !== false)) active @endif">
                    <a href="#">
                        <i class="fa fa-wrench"></i>
                        <span>Configurações</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="@if(Route::currentRouteName() == 'contas_empresa.index') active @endif"><a href="{{ route('contas_empresa.index') }}"><i class="fa fa-bank"></i> Contas</a></li>
                        <li class="@if(Route::currentRouteName() == 'empresa.edit') active @endif"><a href="{{ route('empresa.edit', 1) }}"><i class="fa fa-building"></i> Dados da empresa</a></li>
                        <li class="@if(Route::currentRouteName() == 'metodo_pagamento.index') active @endif"><a href="{{ route('metodo_pagamento.index') }}"><i class="fa fa-building"></i> Métodos de Pagamento</a></li>
                        <li class="@if(Route::currentRouteName() == 'sistema.edit') active @endif"><a href="{{ route('sistema.edit', 1) }}"><i class="fa fa-building"></i> Sistema</a></li>

                    </ul>
                </li>
            @endif

            <li class="treeview @if(strpos(Route::currentRouteName(), 'download.index') !== false) active @endif">
                <a href="{{ route('download.index') }}">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Uploads</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'download-tipo') !== false) active @endif">
                <a href="{{ route('download-tipo.index') }}">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Tipo Uploads</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>

            <li class="treeview @if(Route::currentRouteName() == 'videos.index') active @endif">
                <a href="{{ route('videos.index') }}">
                    <i class="glyphicon glyphicon-play-circle"></i>
                    <span>Vídeos</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>


            <li class="treeview @if((strpos(Route::currentRouteName(), 'boleto') !== false) || (strpos(Route::currentRouteName(), 'movimento') !== false) || (strpos(Route::currentRouteName(), 'remessa') !== false) || (strpos(Route::currentRouteName(), 'pagamentos') !== false || strpos(Route::currentRouteName(), 'rentabilidade') !== false)) active @endif">
                <a href="#">
                    <i class="fa fa-money"></i>
                    <span>Financeiro</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview @if(strpos(Route::currentRouteName(), 'boletos') !== false) active @endif">
                        <a href="#"><i class="glyphicon glyphicon-barcode"></i> Boletos</a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('boletos.adesoes') }}"><i class="fa fa-building"></i> Adesões</a></li>
                            <li><a href="{{ route('boletos.mensalidades') }}"><i class="fa fa-building"></i> Mensalidades</a></li>
                            <li><a href="{{ route('boleto.retorno') }}"><i class="fa fa-building"></i> Receber boletos</a></li>
                        </ul>
                    </li>
                    @if($sistema->rede_binaria)
                        <li @if(Route::currentRouteName() == 'movimento.binario.create') class="active" @endif><a href="{{ route('movimento.binario.create') }}"><i class="glyphicon glyphicon-open-file"></i> Inserir binário</a></li>
                    @endif
                    <li @if(Route::currentRouteName() == 'movimento.create') class="active" @endif><a href="{{ route('movimento.create') }}"><i class="glyphicon glyphicon-open-file"></i> Inserir movimento</a></li>
                    <li class="treeview @if(strpos(Route::currentRouteName(), 'pagamentos') !== false || strpos(Route::currentRouteName(), 'rentabilidade') !== false) active @endif @if($sistema['rendimento_titulo'] == 0 && $sistema['rendimento_item'] == 0) hide @endif">
                        <a href="#">
                            <i class="fa fa-balance-scale"></i>
                            <span>Pagamentos</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="@if(strpos(Route::currentRouteName(), 'rentabilidade') !== false) active @endif"><a href="{{ route('rentabilidade.index') }}"><i class="fa fa-line-chart"></i>Rentabilidade</a></li>
                        </ul>
                    </li>
                    <li><a href="{{ route('relatorio.pagamento') }}"><i class="glyphicon glyphicon-print"></i>Relatório para pagamento</a></li>

                    <li class="treeview @if((strpos(Route::currentRouteName(), 'remessa') !== false))) active @endif">
                        <a href="#">
                            <i class="fa fa-truck"></i>
                            <span>Remessas</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('remessa.index') }}"><i class="glyphicon glyphicon-list-alt"></i>Lista</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="treeview @if((strpos(Route::currentRouteName(), 'item') !== false) || (strpos(Route::currentRouteName(), 'titulo') !== false) || (strpos(Route::currentRouteName(), 'configuracao-bonus') !== false)) active @endif">
                <a href="#">
                    <i class="fa fa-balance-scale"></i> <span>Bonificação</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ route('item.index') }}">
                            <i class="fa fa-cubes"></i>
                            <span>Itens</span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                    </li>
                    @if($master || $admin)
                        <li>
                            <a href="{{ route('titulo.index') }}">
                                <i class="fa fa-certificate"></i>
                                <span>Titulos</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                        </li>
                    @endif
                    <li class="@if(strpos(Route::currentRouteName(), 'configuracao-bonus') !== false) active @endif"><a href="{{ route('configuracao-bonus.index', 1) }}"><i class="fa fa-building"></i> Configuração de bônus</a></li>
                </ul>
            </li>


            {{--operações--}}
            <li class="treeview @if((strpos(Route::currentRouteName(), 'plataforma') !== false) || (strpos(Route::currentRouteName(), 'plataforma-conta') !== false) || (strpos(Route::currentRouteName(), 'operacao-historico') !== false)) active @endif">
                <a href="#">
                    <i class="fa fa-check-square-o"></i>
                    <span>Operações</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == 'plataforma.index') class="active" @endif><a href="{{ route('plataforma.index') }}"><i class="fa fa-th"></i> Plataforma</a></li>
                    <li @if(Route::currentRouteName() == 'operacao-historico.index' || Route::currentRouteName() == 'operacao-historico.create' || Route::currentRouteName() == 'operacao-historico.edit' || (strpos(Route::currentRouteName(), 'operacao-historico') !== false)) class="active" @endif><a href="{{ route('operacao-historico.index') }}">
                            <i class="fa fa-line-chart"></i> Histórico de Operações</a>
                    </li>
                </ul>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'modal') !== false) active @endif">
                <a href="{{ route('modal.index') }}">
                    <i class="fa fa-bars"></i>
                    <span>Modal de aviso</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>

            <li class="treeview @if(strpos(Route::currentRouteName(), 'relatorio') !== false) active @endif">
                <a href="javascript:;">
                    <i class="fa fa-file-text-o"></i>
                    <span>Relatórios</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
            </li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
