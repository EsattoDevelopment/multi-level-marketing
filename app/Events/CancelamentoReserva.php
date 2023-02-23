<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Events;

use App\Models\PedidoPacote;
use Illuminate\Queue\SerializesModels;

class CancelamentoReserva extends Event
{
    use SerializesModels;

    private $reserva;

    /**
     * @return mixed
     */
    public function getReserva()
    {
        return $this->reserva;
    }

    /**
     * @param mixed $reserva
     */
    public function setReserva($reserva)
    {
        $this->reserva = $reserva;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PedidoPacote $reserva)
    {
        $this->setReserva($reserva);
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
