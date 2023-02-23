<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Milhas extends Model
{
    protected $table = 'milhas';

    protected $fillable = [
        'quantidade',
        'referencia',
        'descricao',
        'user_id',
        'validade',
        'utilizada_onde',
        'pedido_id',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function pontuaRede(Pedidos $pedido, $nohSuperior)
    {
        Log::info('Entrou pagamento milhas binarias, pedido #'.$pedido->id);
        try {
            $itensPedido = $pedido->itens()->get();

            foreach ($itensPedido as $itemPedido) {
                $item = $itemPedido->itens()->first();

                if ($item->milhas_binaria > 0) {
                    Log::info('Binario referente ao item #'.$item->id);

                    //TODO pagamento das milhas
                    $dadosMilhas = [
                        'quantidade' => $item->milhas_binaria,
                        'descricao' => 'Milhas binarias',
                        'user_id' => $nohSuperior->id,
                        'validade' => Carbon::now()->addDays($item->milhas_binaria_validade),
                        'pedido_id' => $pedido->id,
                    ];

                    self::create($dadosMilhas);
                    Log::info('Inserido milhas binarias: ', $dadosMilhas);

                    $rede = $this->whereEsquerda($this->id)->orWhere('direita', $this->id)->first();

                    //TODO verifica se há rede
                    if ($rede) {
                        Log::info('Pagar binario para: '.$rede->user_id);

                        $lado = 2;

                        if ($rede->esquerda == $this->id) {
                            $lado = 1;
                        }

                        $referencia = 'Pedido #'.$this->pedido_id;

                        $rede->pontuaRede($item, $lado, $referencia);
                    }
                }
            }

            return true;
        } catch (ModelNotFoundException $e) {
            Log::info('Erro no pagamento de binários');

            return false;
        }
    }

    public function getValidadeAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }
}
