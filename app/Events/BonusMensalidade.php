<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Events;

use App\Models\User;
use App\Models\Sistema;
use App\Saude\Domains\Mensalidade;
use Illuminate\Queue\SerializesModels;

class BonusMensalidade extends Event
{
    use SerializesModels;

    private $mensalidade;
    private $equiparacaoPago = 0;
    private $equiparacaoNivel = 4;

    /**
     * @return int
     */
    public function getEquiparacaoNivel()
    {
        return $this->equiparacaoNivel;
    }

    /**
     * @param int $equiparacaoNivel
     */
    public function setEquiparacaoNivel($equiparacaoNivel)
    {
        $this->equiparacaoNivel = $equiparacaoNivel;
    }

    /**
     * @return mixed
     */
    public function getEquiparacaoPago()
    {
        return $this->equiparacaoPago;
    }

    /**
     * @param mixed $equiparacaoPago
     */
    public function setEquiparacaoPago($equiparacaoPago)
    {
        $this->equiparacaoPago += $equiparacaoPago;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    private $user;

    /**
     * @return Mensalidade
     */
    public function getMensalidade()
    {
        return $this->mensalidade;
    }

    /**
     * @param Mensalidade $mensalidade
     */
    public function setMensalidade($mensalidade)
    {
        $this->mensalidade = $mensalidade;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Mensalidade $mensalidade)
    {
        $this->setMensalidade($mensalidade);
        $this->setUser(User::select(['id', 'indicador_id', 'name', 'tipo', 'titulo_id'])
                ->with(
                    [
                        'indicador' => function ($query) {
                            $query
                                ->select(['id', 'status', 'titulo_id', 'indicador_id', 'name']);
                        },
                    ]
                )->find($mensalidade->user_id));

        $this->sistema = Sistema::findOrFail(1);
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
