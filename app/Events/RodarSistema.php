<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Events;

use App\Models\User;
use App\Models\Sistema;
use Illuminate\Queue\SerializesModels;

class RodarSistema extends Event
{
    use SerializesModels;

    private $objUsuario;
    private $usuarioQualificados;
    public $sistema;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setObjUsuario(new User());
        $this->sistema = Sistema::findOrFail(1);
        Log::info('Rodando Sistema');
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

    /**
     * @return mixed
     */
    public function getObjUsuario()
    {
        return $this->objUsuario;
    }

    /**
     * @param mixed $objUsuario
     */
    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
    }

    /**
     * @return mixed
     */
    public function getUsuarioQualificados()
    {
        return $this->usuarioQualificados;
    }

    /**
     * @param mixed $usuarioQualificados
     */
    public function setUsuarioQualificados($usuarioQualificados)
    {
        $this->usuarioQualificados = $usuarioQualificados;
    }
}
