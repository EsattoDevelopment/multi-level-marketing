<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class GerarMensalidadeEmpresa extends Event
{
    use SerializesModels;

    private $empresas;

    /**
     * @return mixed
     */
    public function getEmpresas()
    {
        return $this->empresas;
    }

    /**
     * @param mixed $empresas
     */
    public function setEmpresas($empresas)
    {
        $this->empresas = $empresas;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setEmpresas(User::whereTipo(2)
            ->whereStatus(1)
            ->select('id', 'tipo', 'status')
            ->get()
        );
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
