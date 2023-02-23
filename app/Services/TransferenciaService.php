<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\Sistema;
use App\Models\Transferencias;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TransferenciaService
{
    private $sistema;

    public function __construct()
    {
        $this->sistema = Sistema::find(1);
    }

    public function transferenciaInternaGratuitaQtde($user_id)
    {
        $transferencia_gratuitas = $this->sistema->transferencia_interna_qtde_gratis;
        $transferencia_gratuitas_restante = 0;
        if($transferencia_gratuitas < 10000){
            //se for >= a 10000 todas as transferencias são gratuitas desde que estejam dentro do valor minimo
            $inicioMes = Carbon::now()->firstOfMonth();
            $fimMes = Carbon::now()->lastOfMonth();

            $totalTransferenciasGratuitas = Transferencias::where('user_id', $user_id)
                ->whereDate('created_at', '>=', $inicioMes)
                ->whereDate('created_at', '<=', $fimMes)
                ->whereNotNull('destinatario_user_id')
                ->where('valor', '>=', $this->sistema->transferencia_interna_valor_minimo_gratis)
                ->where('status', '<>', 3)
                ->get();

            $transferencia_gratuitas_restante = $transferencia_gratuitas - $totalTransferenciasGratuitas->count();
            $transferencia_gratuitas_restante = $transferencia_gratuitas_restante >= 0  ? $transferencia_gratuitas_restante : 0;
        }
        else{
            $transferencia_gratuitas_restante = 'Ilimitada';
        }

        return $transferencia_gratuitas_restante;
    }

    public function transferenciaInternaValorTaxa($user_id, $valor){
        $valor_taxa = 0;

        if($this->sistema->transferencia_interna_valor_taxa > 0){
            if($valor < $this->sistema->transferencia_interna_valor_minimo_gratis)
                $valor_taxa = $this->sistema->transferencia_interna_valor_taxa;
            else{
                $transferencia_gratuitas_restante = $this->transferenciaInternaGratuitaQtde($user_id);
                if($transferencia_gratuitas_restante !== 'Ilimitada'){
                    if($transferencia_gratuitas_restante == 0)
                        $valor_taxa = $this->sistema->transferencia_interna_valor_taxa;
                }
            }
        }

        return $valor_taxa;
    }

    public function transferenciaExternaGratuitaQtde($user_id)
    {
        $transferencia_gratuitas = $this->sistema->transferencia_externa_qtde_gratis;
        $transferencia_gratuitas_restante = 0;
        if($transferencia_gratuitas < 10000){
            //se for >= a 10000 todas as transferencias são gratuitas desde que estejam dentro do valor minimo
            $inicioMes = Carbon::now()->firstOfMonth();
            $fimMes = Carbon::now()->lastOfMonth();

            $totalTransferenciasGratuitas = Transferencias::where('user_id', $user_id)
                ->whereDate('created_at', '>=', $inicioMes)
                ->whereDate('created_at', '<=', $fimMes)
                ->whereNotNull('dado_bancario_id')
                ->where('valor', '>=', $this->sistema->transferencia_externa_valor_minimo_gratis)
                ->where('status', '<>', 3)
                ->get();

            $transferencia_gratuitas_restante = $transferencia_gratuitas - $totalTransferenciasGratuitas->count();
            $transferencia_gratuitas_restante = $transferencia_gratuitas_restante >= 0  ? $transferencia_gratuitas_restante : 0;
        }
        else{
            $transferencia_gratuitas_restante = 'Ilimitada';
        }

        return $transferencia_gratuitas_restante;
    }

    public function transferenciaExternaValorTaxa($user_id, $valor){
        $valor_taxa = 0;

        if($this->sistema->transferencia_externa_valor_taxa > 0){
            if($valor < $this->sistema->transferencia_externa_valor_minimo_gratis)
                $valor_taxa = $this->sistema->transferencia_externa_valor_taxa;
            else{
                $transferencia_gratuitas_restante = $this->transferenciaExternaGratuitaQtde($user_id);
                if($transferencia_gratuitas_restante !== 'Ilimitada'){
                    if($transferencia_gratuitas_restante == 0)
                        $valor_taxa = $this->sistema->transferencia_externa_valor_taxa;
                }
            }
        }

        return $valor_taxa;
    }
}
