<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\PedidoFoiPago;
use App\Listeners\AtivaUsuario;
use App\Listeners\CriaContrato;
use App\Listeners\PosicionaRede;
use App\Listeners\QualificaUsuario;
use App\Listeners\QualificaUsuarioPosicionaHotel;
use App\Listeners\PedidoGeraHotel;
use App\Listeners\SobeTituloItem;
use App\Listeners\PagaPontosPessoais;
use App\Listeners\PagaPontosEquiparacao;
use App\Listeners\PagaBonusEvent;
use App\Listeners\PagaBonusIndicador;
use App\Listeners\Equiparacao;
use App\Listeners\PagaMilhas;
use App\Listeners\PagaBinarios;
use App\Listeners\PagaDeposito;
use App\Listeners\GerarContrato;
use App\Events\RodarSistema;
use App\Listeners\RodarSistema\SubirTitulo;
use App\Listeners\RodarSistema\RodaBinario;
use App\Listeners\RodarSistema\VerificarContratosFinalizado;
use App\Listeners\RodarSistema\VerificarMensalidades;
use App\Events\AcoesSistema;
use App\Listeners\AcoesSistema\BonusMilhasCadastro;
use App\Events\ReservaRealizada;
use App\Listeners\ReservaRealizada\EmailColaborador;
use App\Listeners\ReservaRealizada\EmailEmpresa as ReservaRealizadaEmailEmpresa;
use App\Events\CancelamentoReserva;
use App\Listeners\CancelamentoReserva\EmailEmpresa as CancelamentoReservaEmailEmpresa;
use App\Events\GerarMensalidadeEmpresa;
use App\Listeners\MensalidadeEmpresa;
use App\Events\BonusMensalidade;
use App\Listeners\PagaPontosUnilevel;
use App\Listeners\verificacaoContrato;
use App\Listeners\MensalidadePaga;
use App\Listeners\Pontuacao;
use App\Events\MensalidadeSemBonus;
use App\Listeners\verificacaoContrato2;
use App\Saude\Events\CancelamentoContrato;
use App\Saude\Listeners\Contrato\CancelarMensalidade;
use App\Saude\Listeners\Contrato\EstornoBonus;
use App\Saude\Listeners\Contrato\EstornoValorPedido;
use App\Saude\Listeners\Contrato\EstornoPontos;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PedidoFoiPago::class => [
            // ativa usuario caso esteja desativado
            AtivaUsuario::class,
            // cria o contrato do associado
            CriaContrato::class,
            // adiciona apenas na rede binaria
            PosicionaRede::class,
            QualificaUsuario::class,
            // verifica se o indicador agora tem diretos necessarios para hotel
            QualificaUsuarioPosicionaHotel::class,
            // libera hotel se o item comprado dar direito
            PedidoGeraHotel::class,
            // sobe de nivel caso o item comprado de direito
            SobeTituloItem::class,
            PagaPontosPessoais::class,
            PagaPontosEquiparacao::class,
            // paga bonus de indicação direta
            PagaBonusEvent::class,
//            PagaBonusIndicador::class,
            // paga bonus de equiparação
//            Equiparacao::class,
            // paga milhas
            PagaMilhas::class,
            // paga os pontos
            PagaBinarios::class,
            // paga deposito
            PagaDeposito::class,
            // gerar contrato
//            GerarContrato::class,
        ],
        RodarSistema::class => [
            SubirTitulo::class,
            RodaBinario::class,
            VerificarContratosFinalizado::class,
            VerificarMensalidades::class,
        ],
        AcoesSistema::class => [
//            BonusMilhasCadastro::class,
        ],
        ReservaRealizada::class => [
//            EmailColaborador::class,
//            ReservaRealizadaEmailEmpresa::class,
        ],
        CancelamentoReserva::class => [
//            CancelamentoReservaEmailEmpresa::class
        ],
        GerarMensalidadeEmpresa::class => [
            MensalidadeEmpresa::class,
        ],
        BonusMensalidade::class => [
//            MensalidadePaga::class,
//            Pontuacao::class,
//            Equiparacao::class,
            PagaPontosUnilevel::class,
            verificacaoContrato::class,
        ],
        MensalidadeSemBonus::class => [
            verificacaoContrato2::class,
        ],
        CancelamentoContrato::class => [
//            CancelarMensalidade::class,
//            EstornoBonus::class,
//            EstornoValorPedido::class,
//            EstornoPontos::class
        ],

    ];

    /**
     * Register any other events for your application.
     */
    public function boot(DispatcherContract $events): void
    {
        parent::boot($events);
    }
}
