<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\PedidoFoiPago'           => [
            //ativa usuario caso esteja desativado
            'App\Listeners\AtivaUsuario',

            //Cria o contrato do associado
            'App\Listeners\CriaContrato',

            //adiciona apenas na rede binaria
            'App\Listeners\PosicionaRede',

            'App\Listeners\QualificaUsuario',

            //verifica se o indicador agora tem diretos necessarios para hotel
            'App\Listeners\QualificaUsuarioPosicionaHotel',

            //Libera hotel se o item comprado dar direito
            'App\Listeners\PedidoGeraHotel',

            //sobe de nivel caso o item comprado de direito
            'App\Listeners\SobeTituloItem',

            'App\Listeners\PagaPontosPessoais',

            'App\Listeners\PagaPontosEquiparacao',

            //paga bonus de indicação direta
            'App\Listeners\PagaBonusEvent',
#            'App\Listeners\PagaBonusIndicador',

            //paga bonus de equiparação
#            'App\Listeners\Equiparacao',

            //paga milhas
            'App\Listeners\PagaMilhas',

            //paga os pontos
            'App\Listeners\PagaBinarios',

            //paga deposito
            'App\Listeners\PagaDeposito',

            //gerar contrato
#            'App\Listeners\GerarContrato',
        ],
        'App\Events\RodarSistema'            => [
            'App\Listeners\RodarSistema\SubirTitulo',
            'App\Listeners\RodarSistema\RodaBinario',
            'App\Listeners\RodarSistema\VerificarContratosFinalizado',
            'App\Listeners\RodarSistema\VerificarMensalidades',
        ],
        'App\Events\AcoesSistema'            => [
            //'App\Listeners\AcoesSistema\BonusMilhasCadastro',
        ],
        'App\Events\ReservaRealizada'        => [
            //'App\Listeners\ReservaRealizada\EmailColaborador',
            //'App\Listeners\ReservaRealizada\EmailEmpresa',
        ],
        'App\Events\CancelamentoReserva'     => [
            //'App\Listeners\CancelamentoReserva\EmailEmpresa'
        ],
        'App\Events\GerarMensalidadeEmpresa' => [
            'App\Listeners\MensalidadeEmpresa',
        ],
        'App\Events\BonusMensalidade'        => [
            //'App\Listeners\MensalidadePaga',
            //'App\Listeners\Pontuacao',
            //'App\Listeners\Equiparacao',
            'App\Listeners\PagaPontosUnilevel',
            'App\Listeners\verificacaoContrato',
        ],
        'App\Events\MensalidadeSemBonus'  => [
            'App\Listeners\verificacaoContrato2',
        ],
        'App\Saude\Events\CancelamentoContrato'  => [
            //'App\Saude\Listeners\Contrato\CancelarMensalidade',
            //'App\Saude\Listeners\Contrato\EstornoBonus',
            //'App\Saude\Listeners\Contrato\EstornoValorPedido',
            //'App\Saude\Listeners\Contrato\EstornoPontos'
        ],

    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);
    }
}
