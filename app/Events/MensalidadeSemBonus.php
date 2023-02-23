<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Events;

use App\Models\User;
use App\Saude\Domains\Mensalidade;
use Illuminate\Queue\SerializesModels;

class MensalidadeSemBonus extends Event
{
    use SerializesModels;

    private $mensalidade;
    private $user;

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
        $this->setUser(User::select(['id', 'indicador_id', 'name', 'tipo'])->find($mensalidade->user_id));
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
