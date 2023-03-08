<?php

namespace App\Services;

use App\Models\User;
use App\Models\RedeBinaria;
use Illuminate\Support\Facades\Log;

class PosicionaRedeBinaria
{
    private $rede;
    private $lado;
    private $user;

    public function __construct(RedeBinaria $rede, string $lado, User $user)
    {
        Log::info('Instanciado posicionamento de rede binária');
        $this->rede = $rede;
        $this->lado = $lado;
        $this->user = $user;
    }

    /**
     * Posiciona usuário na rede.
     */
    public function posicionar(): void
    {
        $lado = $this->lado;
        Log::info("Verificando se há posição na vaga do lado $this->lado da rede do usuário # {$this->rede->user_id}");
        if (!$this->rede->$lado) {
            Log::info("Lado {$this->rede->$lado} está vago");
            $this->rede->$lado = $this->user->id;
            $this->rede->save();
            // seta no cadastro do usuario em que lado ele está posicionado
            $this->user->lado = $lado === 'esquerda' ? 1 : 2;
            $this->user->save();
            Log::info('Posicionamento finalizado');
            return;
        }
        Log::info("Lado {$this->rede->$lado} ocupado, descendo um nivél");
        $usuarioAbaixo = $this->rede->usuarioAbaixo($lado);
        $this->rede = $usuarioAbaixo->redeBinario;
        $this->posicionar();
    }
}
