<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="@if(Auth::user()->image){{ route('imagecache', ['user', 'user/'.Auth::user()->image]) }}@else{{ route('imagecache', ['user', 'user-img.jpg']) }}@endif"
                     class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ strlen(Auth::user()->cpf) == 18 ? Auth::user()->empresa : Auth::user()->name }}</p>
                <p>Agência: 0001 / Conta: {{ Auth::user()->conta}}</p>
                {{--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>--}}
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">NAVEGAÇÃO</li>
            <li class="treeview">
                <a href="{{ route('home') }}">
                    <i class="fa fa-dashboard"></i>
                    <span>Início</span>
                </a>
            </li>
            <li class="treeview @if(strpos(Route::currentRouteName(), 'dados-usuario') !== false || strpos(Route::currentRouteName(), '2fa') !== false) active @endif">
                <a href="#">
                    <i class="fa fa-file-text"></i>
                    <span>Dados Cadastrais</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="@if(Route::currentRouteName() == 'dados-usuario.pessoais') active @endif"><a href="{{ route('dados-usuario.pessoais') }}"><i class="fa fa-file-text"></i> Dados pessoais</a></li>
                    <li class="@if(Route::currentRouteName() == 'dados-usuario.endereco') active @endif"><a href="{{ route('dados-usuario.endereco') }}"><i class="fa fa-street-view"></i> Endereço</a></li>
                    <li class="@if(Route::currentRouteName() == 'dados-usuario.identificacao') active @endif"><a href="{{ route('dados-usuario.identificacao') }}"><i class="fa fa-check-square-o"></i> Enviar Documentos</a></li>
                    <li class="@if(strpos(Route::currentRouteName(), 'dados-usuario.dados-bancarios') !== false) active @endif"><a href="{{ route('dados-usuario.dados-bancarios') }}"><i class="fa fa-bank"></i> Dados Bancários</a></li>
                    <li class="@if(Route::currentRouteName() == 'dados-usuario.seguranca' || strpos(Route::currentRouteName(), '2fa') !== false) active @endif"><a href="{{ route('dados-usuario.seguranca') }}"><i class="fa fa-lock"></i> Segurança</a></li>
                </ul>
            </li>
            @role('manipulador-documento')
            @include('default.layout.sidebar.documento')
            @endrole
            <li class="treeview">
                <a href="{{ route('indices.economicos') }}">
                    <i class="fa fa-line-chart"></i>
                    <span>Índices Econômicos</span>
                </a>
            </li>
            <li class="treeview @if(strpos(Route::currentRouteName(), 'emprestimos.') !== false) active @endif">
                <a href="javascript:">
                    <i class="fa fa-money"></i>
                    <span>Empréstimos</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ route('emprestimos.calculadora') }}"><i class="fa fa-calculator"></i> Fazer simulação</a></li>
                </ul>
            </li>
            @if(!Auth::user()->empresa_id)
                <li class="treeview @if(strpos(Route::currentRouteName(), 'depositos.') !== false || strpos(Route::currentRouteName(), 'deposito.depositar') !== false) active @endif">
                    <a href="#">
                        <i class="fa fa-th-large"></i> <span>Depósitos</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @if($sistema->deposito_is_active)
                            <li @if(Route::currentRouteName() == "deposito.depositar") class="active" @endif>
                                <a href="{{ route('deposito.depositar') }}">
                                    <i class="fa fa-circle-o" style="color: {{$dadosEmpresa->cor_botao_acao}}"></i>
                                    Realizar Novo Depósito
                                </a>
                            </li>
                        @endif
                        <li @if(Route::currentRouteName() == "depositos.aguardando.deposito") class="active" @endif>
                            <a href="{{ route('depositos.aguardando.deposito') }}">
                                <i class="fa fa-circle-o" style="color: red"></i>
                                Aguardando depósito
                                <small class="label pull-right bg-red">{{ Auth::user()->pedidos()->whereStatus(1)->whereTipoPedido(4)->count() }}</small>
                            </a>
                        </li>
                        <li @if(Route::currentRouteName() == "depositos.aguardando.conferencia") class="active" @endif>
                            <a href="{{ route('depositos.aguardando.conferencia') }}">
                                <i class="fa fa-circle-o" style="color: orange"></i>
                                Aguardando conferência
                                <small class="label pull-right bg-orange">{{ Auth::user()->pedidos()->whereStatus(4)->whereTipoPedido(4)->count() }}</small>
                            </a>
                        </li>
                        <li @if(Route::currentRouteName() == "depositos.confirmados") class="active" @endif>
                            <a href="{{ route('depositos.confirmados') }}">
                                <i class="fa fa-circle-o" style="color: green"></i>
                                Confirmados
                                <small class="label pull-right bg-green">{{ Auth::user()->pedidos()->whereStatus(2)->whereTipoPedido(4)->count() }}</small>
                            </a>
                        </li>
                        <li @if(Route::currentRouteName() == "depositos.cancelados") class="active" @endif>
                            <a href="{{ route('depositos.cancelados') }}">
                                <i class="fa fa-circle-o" style="color: gray"></i>
                                Cancelados
                                <small class="label pull-right bg-gray">{{ Auth::user()->pedidos()->whereStatus(3)->whereTipoPedido(4)->count() }}</small>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @if(!Auth::user()->empresa_id)
                <li class="treeview @if(strpos(Route::currentRouteName(), 'pedidos.') !== false || strpos(Route::currentRouteName(), 'portfolio.lista') !== false) active @endif">
                    <a href="#">
                        <i class="fa fa-file-text-o"></i>
                        <span>Credenciais</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li @if(Route::currentRouteName() == "portfolio.lista") class="active" @endif>
                            <a href="{{ route('portfolio.lista') }}">
                                <i class="fa fa-file-o"></i> Adquirir/Renovar Credencial
                            </a>
                        </li>
                        <li @if(Route::currentRouteName() == "pedidos.aguardando.pagamento") class="active" @endif>
                            <a href="{{ route('pedidos.aguardando.pagamento') }}">
                                <i class="fa fa-circle-o" style="color: red"></i> Aguardando Pagamento
                                <small class="label pull-right bg-red">{{ Auth::user()->pedidos()->whereStatus(1)->where('tipo_pedido', '<>', 4)->count() }}</small>
                            </a>
                        </li>
                        <li @if(Route::currentRouteName() == "pedidos.aguardando.conferencia") class="active" @endif>
                            <a href="{{ route('pedidos.aguardando.conferencia') }}">
                                <i class="fa fa-circle-o" style="color: orange"></i>
                                Aguardando conferência
                                <small class="label pull-right bg-orange">{{ Auth::user()->pedidos()->whereStatus(4)->where('tipo_pedido', '<>', 4)->count() }}</small>
                            </a>
                        </li>
                        <li @if(Route::currentRouteName() == "pedidos.confirmados") class="active" @endif>
                            <a href="{{ route('pedidos.confirmados') }}">
                                <i class="fa fa-circle-o" style="color: green"></i> Confirmados
                                <small class="label pull-right bg-green">{{ Auth::user()->pedidos()->whereStatus(2)->where('tipo_pedido', '<>', 4)->count() }}</small>
                            </a>
                        </li>
                        <li @if(Route::currentRouteName() == "pedidos.cancelados") class="active" @endif>
                            <a href="{{ route('pedidos.cancelados') }}">
                                <i class="fa fa-circle-o" style="color: gray"></i> Cancelados
                                <small class="label pull-right bg-gray">{{ Auth::user()->pedidos()->whereStatus(3)->where('tipo_pedido', '<>', 4)->count() }}</small>
                            </a>
                        </li>
                        <li @if(Route::currentRouteName() == "pedido.usuario.pedidos") class="active" @endif>
                            <a href="{{ route('pedido.usuario.pedidos', Auth::user()->id) }}">
                                <i class="fa fa-files-o"></i> Ver Aquisições Anteriores
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @include('default.layout.sidebar.extratos')
            <li class="treeview @if(strpos(Route::currentRouteName(), 'transferencia') !== false) active @endif">
                <a href="#">
                    <i class="fa fa-exchange"></i> <span>Transferências</span> <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li @if(Route::currentRouteName() == "transferencia.liberty") class="active" @endif>
                        <a href="{{ route('transferencia.liberty') }}">
                            <i class="fa fa-circle-o"></i> Entre contas {{ ucfirst(env('COMPANY_NAME_SHORT', 'empresa')) }}
                        </a>
                    </li>
                    <li @if(Route::currentRouteName() == "transferencia.create") class="active" @endif>
                        <a
                            @if($sistema->restringir_dias_para_saques && \Carbon\Carbon::now()->day !== $sistema->dia_permitido_para_saques)
                                style="cursor: not-allowed; color: rgba(138,164,175,0.3);"
                                href="#"
                                class="tooltip2"
                                title="Transferências permitidas apenas dia {{$sistema->dia_permitido_para_saques}}."
                            @else
                                href="{{ route('transferencia.create') }}"
                            @endif
                        >
                            <i class="fa fa-circle-o"></i> Para outros bancos
                            @if($sistema->restringir_dias_para_saques && \Carbon\Carbon::now()->day !== $sistema->dia_permitido_para_saques)
                                <span class="tooltiptext">Transferências permitidas apenas dia {{$sistema->dia_permitido_para_saques}}.</span>
                            @endif
                        </a>
                    </li>
                    <li @if(Route::currentRouteName() == "transferencia.index") class="active" @endif>
                        <a href="{{ route('transferencia.index') }}">
                            <i class="fa fa-circle-o"></i>Histórico
                        </a>
                    </li>
                </ul>
            </li>
            @if(Auth::user()->titulo->habilita_rede)
                <li class="treeview @if(Route::currentRouteName() == 'rede') active @endif">
                    <a href="{{ route('rede') }}">
                        <i class="fa fa-slideshare"></i>
                        <span>Clientes {{ env('COMPANY_NAME', 'Nome empresa') }} indicados</span>
                        <small class="label pull-right bg-red">{{ Auth::user()->diretos->count() }}</small>
                    </a>
                </li>
                <li class="treeview @if(strpos(Route::currentRouteName(), 'rede.organograma') !== false || strpos(Route::currentRouteName(), 'rede.agentes') !== false) active @endif">
                    <a href="#">
                        <i class="fa fa-users"></i>
                        <span>Rede hierárquica {{ env('COMPANY_NAME', 'Nome empresa') }}</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li @if(Route::currentRouteName() == "rede.agentes") class="active" @endif>
                            <a href="{{ route('rede.agentes') }}"><i class="fa fa-building"></i> Agentes
                                <span class="pull-right-container">
                                  <small class="label pull-right bg-red">{{ Auth::user()->consultores->count() }}</small>
                                </span>
                            </a>
                        </li>
                        <li @if(Route::currentRouteName() == "rede.organograma") class="active" @endif>
                            <a href="{{ route('rede.organograma') }}"><i class="fa fa-sitemap"></i> Organograma
                                <span class="pull-right-container">
                                </span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
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
            @if(Auth::user()->nascimento->diffInYears(\Carbon\Carbon::now()) >= 18)
                {{--@if(!Auth::user()->titulo->habilita_rede && Auth::user()->pedidos()->contratos()->where('status', 2)->count() > 0)--}}
                @if(!Auth::user()->titulo->habilita_rede)
                    <li class="treeview">
                        <a href="{{ route('pedido.consultor') }}">
                            <i class="fa fa-black-tie"></i>
                            <span>Seja Agente Credenciado</span>
                        </a>
                    </li>
                @endif
            @endif
            @include('default.layout.sidebar.download')
            <li class="treeview">
                <a href="{{ route('auth.logout') }}">
                    <i class="fa fa-sign-out"></i>
                    <span>Sair</span>
                </a>
            </li>
        </ul>
    </section>
</aside>
