<li class="treeview @if(strpos(Route::currentRouteName(), 'extrato') !== false || strpos(Route::currentRouteName(), 'capitalizacao') !== false) active @endif">
    <a href="#">
        <i class="fa fa-print"></i> <span>Extratos</span> <i class="fa fa-angle-left pull-right"></i>
    </a>
    <ul class="treeview-menu">
        <li @if(Route::currentRouteName() == "extrato.financeiro") class="active" @endif><a href="{{ route('extrato.financeiro') }}"><i class="text-green fa fa-money"></i> Conta digital</a>
        </li>
        @if($sistema->extrato_capitalizacao_exibicao == 1)
            <li @if(Route::currentRouteName() == 'capitalizacao.index') class="active" @endif><a href="{{ route('capitalizacao.index') }}"><i class="fa fa-files-o"></i> Credenciamentos</a></li>
        @endif
            @if(Auth::user()->titulo->habilita_rede)

                {{--<li><a href="{{ route('extrato.milhas') }}"><i class="text-yellow fa fa-plane"></i> GMilhas</a>
                </li>--}}
                {{--<li><a href="{{ route('extrato.pv') }}"><i class="text-purple fa fa-plus"></i> PV</a></li>--}}
                <li class="treeview @if(strpos(Route::currentRouteName(), 'extrato.bonus') !== false) active @endif">
                    <a href="#"><i class="text-green glyphicon glyphicon-piggy-bank"></i> <span>Bônus e taxas</span> <i class="fa fa-angle-left pull-right"></i></a>
                    <ul class="treeview-menu">
                        @if($sistema->extrato_bonus_equipe_exibicao == 1)
                            <li class="@if(strpos(Route::currentRouteName(), 'extrato.bonus.equiparacao') !== false) active @endif"><a href="{{ route('extrato.bonus.equiparacao') }}"><i class="fa fa-users"></i> Bônus de equipe</a></li>
                        @endif
                        <li class="@if(strpos(Route::currentRouteName(), 'extrato.bonus.direto') !== false) active @endif"><a href="{{ route('extrato.bonus.direto') }}"><i class="fa fa-file-text"></i> Licenças e Credenciamentos</a></li>
                            {{--<li class="@if(strpos(Route::currentRouteName(), 'extrato.bonus.direto') !== false) active @endif"><a href="{{ route('extrato.bonus.direto') }}"><i class="fa fa-file-text"></i> Operações</a></li>--}}
                        {{--<li class="@if(strpos(Route::currentRouteName(), 'extrato.bonus.royalties') !== false && strpos(Route::currentRouteName(), 'extrato.bonus.royalties.pagos') === false) active @endif"><a href="{{ route('extrato.bonus.royalties') }}"><i class="text-green fa fa-undo"></i> Royalties ganhos</a></li>--}}
                        <li class="@if(strpos(Route::currentRouteName(), 'extrato.bonus.royalties.pagos') !== false) active @endif"><a href="{{ route('extrato.bonus.royalties.pagos') }}"><i class="text-red fa fa-undo"></i> Royalties Pagos</a></li>
                    </ul>
                </li>
                @if($sistema->pontos_pessoais_calculo_exibicao == 1)
                    <li @if(Route::currentRouteName() == 'extrato.pessoais') class="active" @endif><a href="{{ route('extrato.pessoais') }}"><i class="fa fa-line-chart"></i> Pontos Pessoais</a></li>
                @endif
                @if($sistema->pontos_equipe_calculo_exibicao == 1)
                    <li @if(Route::currentRouteName() == 'extrato.equipe') class="active" @endif><a href="{{ route('extrato.equipe') }}"><i class="fa fa-line-chart"></i> Pontos de Equipe</a></li>
                @endif
            @endif
    </ul>
</li>
