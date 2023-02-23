<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

class CancelarMensalidadesService
{
    public $mensalidades;

    public function __construct($mensalidades)
    {
        $this->mensalidades = $mensalidades;
    }

    public function cancelar()
    {
        if ($this->mensalidades->count() > 0) {
            foreach ($this->mensalidades as $mensalidade) {
                $mensalidade->update(['status' => 5]);
            }

            \Log::info('Mensalidades canceladas com sucesso');
        } else {
            \Log::info('Sem mensalidade');
        }
    }
}
