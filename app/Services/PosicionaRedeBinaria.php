<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\User;
use App\Models\RedeBinaria;
use Illuminate\Support\Facades\Log;

class PosicionaRedeBinaria
{
    private $rede;
    private $lado;
    private $user;

    public function __construct(RedeBinaria $rede, $lado, User $user)
    {
        Log::info('Instanciado posicionamento de rede binária');
        $this->rede = $rede;
        $this->lado = $lado;
        $this->user = $user;
    }

    /**
     * Posiciona usuário na rede.
     * @return bool
     */
    public function posicionar()
    {
        $lado = $this->lado;

        Log::info("Verificando se tem posição vaga do lado {$this->lado} da rede do #ID {$this->rede->user_id}");
        if (! $this->rede->$lado) {
            Log::info('Esta vago!');
            $this->rede->$lado = $this->user->id;
            $this->rede->save();

            $this->user->lado = $lado == 'esquerda' ? 1 : 2; //seta no cadastro do usuario em que lado ele esta posicionado
            $this->user->save();

            Log::info('Posicionamento finalizado!');

            return true;
        }

        Log::info('Não há, descendo um nivél.');
        $usuarioAbaixo = $this->rede->usuarioAbaixo($lado);

        $this->rede = $usuarioAbaixo->redeBinario;
        $this->posicionar();
    }
}
