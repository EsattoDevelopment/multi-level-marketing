<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Events;

use App\Events\Event;
use App\Models\Contrato;
use Illuminate\Queue\SerializesModels;

class CancelamentoContrato extends Event
{
    use SerializesModels;

    protected $contato;

    /**
     * @return Contrato
     */
    public function getContato()
    {
        return $this->contato;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Contrato $contrato)
    {
        $this->contato = $contrato;
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
