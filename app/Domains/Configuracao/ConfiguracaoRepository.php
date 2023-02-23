<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Domains\Configuracao;

use App\Repositories\BaseRepository;

class ConfiguracaoRepository extends BaseRepository
{
    protected $modelClass = Configuracao::class;
}
