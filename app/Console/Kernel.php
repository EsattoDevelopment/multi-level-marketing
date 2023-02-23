<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Console;

use Log;
use Carbon\Carbon;
use App\Services\PagseguroService;
use Illuminate\Support\Facades\Artisan;
use App\Services\BoletoGerencianetService;
use Illuminate\Console\Scheduling\Schedule;
use App\Services\ResgateMinimoContratoService;
use App\Services\FinalizaContratoAutomaticoService;
use App\Services\AlertaRecontratacaoAutomaticoService;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Services\FinalizaContratoQuitarComBonusAutomaticoService;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
            Commands\Inspire::class,
        ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
            ->hourly();

        $schedule->call(function () {
            Log::info('Começou a verificar os pagamento por Boleto!');
            $boleto = new BoletoGerencianetService(0, false, true);
            $boleto->verificaBoletos();
            Log::info('Terminou a verificação dos pagamentos por Boleto!');
        })->twiceDaily(11, 23);
        //->everyMinute();
        //->dailyAt('06:00')

//        $schedule->call(function () {
//            Log::info('Serviço ResgateMinimoContratoService - Início');
//            $resgateMinimoContratoService = new ResgateMinimoContratoService();
//            $resgateMinimoContratoService->processar();
//            Log::info('Serviço ResgateMinimoContratoService - Fim');
//        })->dailyAt('00:00');

//        $schedule->call(function () {
//            Log::info('Serviço FinalizaContratoAutomaticoService - Início');
//            $dataAtual = Carbon::now();
//            $finalizaContratoService = new FinalizaContratoAutomaticoService($dataAtual);
//            $finalizaContratoService->processar();
//            Log::info('Serviço FinalizaContratoAutomaticoService - Fim');
//        })->dailyAt('02:00');

//        $schedule->call(function () {
//            Log::info('Serviço FinalizaContratoQuitarComBonusAutomaticoService - Início');
//            $dataAtual = Carbon::now();
//            $finalizaContratoService = new FinalizaContratoQuitarComBonusAutomaticoService($dataAtual);
//            $finalizaContratoService->processar();
//            Log::info('Serviço FinalizaContratoQuitarComBonusAutomaticoService - Fim');
//        })->dailyAt('03:00');

//        $schedule->call(function () {
//            Log::info('Serviço AlertaRecontratacaoAutomaticoService - Início');
//            $dataAtual = Carbon::now();
//            $alertaRecontratacaoAutomaticoService = new AlertaRecontratacaoAutomaticoService($dataAtual);
//            $alertaRecontratacaoAutomaticoService->processar();
//            Log::info('Serviço AlertaRecontratacaoAutomaticoService - Fim');
//        })->dailyAt('04:00');

        $schedule->call(function () {
            $pagseguro = new PagseguroService();
            $pagseguro->atualizarPagamentos();
        })->everyThirtyMinutes();
    }
}
