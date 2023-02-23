<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Saude\Repositories\UserRepository;
use App\Saude\Repositories\ContratoRepository;

class ContratoImpressaoController extends Controller
{
    private $userRepository;
    private $contratoRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->contratoRepository = new ContratoRepository();

        $this->middleware('manipularOutro', ['only' => ['impressao', 'impressaoConsultor']]);
    }

    public function impressao($contrato)
    {
        $contrato = $this->contratoRepository->getContrato($contrato, ['item', 'usuario']);

        $user = $contrato->getRelation('usuario');
        $endereco = $this->userRepository->getEndereco($user);
        $item = $contrato->getRelation('item');

        return view('saude::impressao_contratos.contrato', compact('contrato', 'user', 'item', 'endereco'));
    }

    public function impressaoConsultor($id)
    {
        $user = User::with('endereco', 'dependentes', 'indicador')->findOrFail($id);
        $endereco = $user->getRelation('endereco');
        $filhos = $user->getRelation('dependentes');
        $indicador = $user->getRelation('indicador');

        return view('saude::impressao_contratos.consultor', compact('user', 'filhos', 'indicador', 'endereco'));
    }
}
