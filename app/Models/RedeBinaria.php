<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Log;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RedeBinaria extends Model
{
    protected $table = 'rede_binaria';

    protected $fillable = ['user_id', 'esquerda', 'direita'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userEsquerda()
    {
        return $this->belongsTo(User::class, 'esquerda');
    }

    /*
     * Busca a rede a qual o usuario pertence
     */
    public function redeSuperior($userId)
    {
        return $this->where('esquerda', $userId)->orWhere('direita', $userId)->first();
    }

    public function usuarioAbaixo($lado)
    {
        return $this->belongsTo(User::class, $lado)->first();
    }

    public function userDireita()
    {
        return $this->belongsTo(User::class, 'direita');
    }

    public function qualificado()
    {
        if ($this->attributes['esquerda'] != 0 && $this->attributes['direita'] != 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Pontua a rede.
     *
     * @param Pedidos $pedido
     * @param $lado
     * @return bool
     */
    public function pontuaRede(ItensPedido $itemPedido, $lado, User $usuarioPedido)
    {
        Log::info('Entrou pontuação binaria');
        Log::info('Lado #'.$lado);
        try {
            $item = $itemPedido->itens()->first();

            //TODO flag para verificar primeiros diretos
            $pularUsuario = false;

            if ($this->user_id == $usuarioPedido->indicador_id) {
                $diretosIndicador = $usuarioPedido->indicador()->first()->diretos()->get()->count();

                if ($diretosIndicador <= 2) {
                    $pularUsuario = true;
                }
            }

            $usuarioRecebedor = User::find($this->user_id);

            if (! $pularUsuario && $usuarioRecebedor->qualificado) {
                Log::info('Binario referente ao item: '.$item->id);

                $extratoBinario = ExtratoBinario::whereUserId($this->user_id)->get()->last();

                if ($lado == 1) {
                    $AcumuladoE = ! $extratoBinario ? $item->pontos_binarios : $item->pontos_binarios + $extratoBinario->acumulado_esquerda;
                    $saldoE = ! $extratoBinario ? $item->pontos_binarios : $item->pontos_binarios + $extratoBinario->saldo_esquerda;

                    $AcumuladoD = ! $extratoBinario ? 0 : $extratoBinario->acumulado_direita;
                    $saldoD = ! $extratoBinario ? 0 : $extratoBinario->saldo_direita;
                } else {
                    $AcumuladoD = ! $extratoBinario ? $item->pontos_binarios : $item->pontos_binarios + $extratoBinario->acumulado_direita;
                    $saldoD = ! $extratoBinario ? $item->pontos_binarios : $item->pontos_binarios + $extratoBinario->saldo_direita;

                    $AcumuladoE = ! $extratoBinario ? 0 : $extratoBinario->acumulado_esquerda;
                    $saldoE = ! $extratoBinario ? 0 : $extratoBinario->saldo_esquerda;
                }

                $dadosExtratoBinario = [
                    'pontos' => $item->pontos_binarios,
                    'saldo_anterior' => ! $extratoBinario ? 0 : $extratoBinario->saldo,
                    'saldo' => ! $extratoBinario ? $item->pontos_binarios : $item->pontos_binarios + $extratoBinario->saldo,
                    'referencia' => $itemPedido->pedido_id,
                    'acumulado_direita' => $AcumuladoD,
                    'acumulado_esquerda' => $AcumuladoE,
                    'acumulado_total' => $AcumuladoE + $AcumuladoD,
                    'saldo_direita' => $saldoD,
                    'saldo_esquerda' => $saldoE,
                    'user_id' => $this->user_id,
                    'operacao_id' => 9,
                ];

                ExtratoBinario::create($dadosExtratoBinario);
                Log::info('Registrou binario para: '.$this->user_id, $dadosExtratoBinario);
            } else {
                Log::info('Pulou patrocinador, é desqualificado ou era binario direto numero ');
            }

            $rede = $this->where('esquerda', $this->user_id)->orWhere('direita', $this->user_id)->first();

            //TODO verifica se há rede
            if ($rede) {
                Log::info('Pagar binario para: '.$rede->user_id);
                if ($rede->esquerda == $this->user_id) {
                    $lado = 1;
                } else {
                    $lado = 2;
                }

                //$referencia = "Pedido #" . $this->pedido_id;

                return $rede->pontuaRede($itemPedido, $lado, $usuarioPedido);
            }

            return true;
        } catch (ModelNotFoundException $e) {
            Log::info('Erro no pagamento de binários');

            return false;
        }
    }

    public function pontuaRedeMilhas(Itens $item, $pedidoID, $nivelMaximo, $count = 1)
    {
        Log::info('entrou Pontua rede milhas');
        if (($nivelMaximo < 0) || ($nivelMaximo > 0 && $nivelMaximo >= $count)) {
            Log::info('Pontua rede milhas, nivel #'.$count);
            try {
                //TODO pagamento das milhas
                $dadosMilhas = [
                    'quantidade' => $item->milhas_binaria,
                    'descricao' => 'Milhas binarias',
                    'user_id' => $this->user_id,
                    'validade' => Carbon::now()->addDays($item->milhas_binaria_validade),
                    'pedido_id' => $pedidoID,
                ];

                Milhas::create($dadosMilhas);
                Log::info('Inserido milhas binarias: ', $dadosMilhas);

                $rede = $this->whereEsquerda($this->user_id)->orWhere('direita', $this->user_id)->first();

                //TODO verifica se há rede
                if ($rede) {
                    return $rede->pontuaRedeMilhas($item, $pedidoID, $nivelMaximo, $count++);
                }

                return true;
            } catch (ModelNotFoundException $e) {
                Log::info('Erro no pagamento de binários');

                return false;
            }
        } else {
            Log::info('Pagamento de milhas binarias chegou ao nivel maximo!');
        }
        Log::info('saiu Pontua rede milhas');

        return true;
    }
}
