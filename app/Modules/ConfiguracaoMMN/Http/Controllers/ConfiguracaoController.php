<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Modules\ConfiguracaoMMN\Http\Controllers;

use Illuminate\Http\Request;
use App\Domains\Configuracao\ConfiguracaoRepository;

class ConfiguracaoController extends BaseController
{
    /**
     * @param ConfiguracaoRepository $configuracao
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home(ConfiguracaoRepository $configuracao)
    {
        $configuracoes = $configuracao->getAll();

        return $this->view('home', [
                'title' => 'Configuração sistema',
                'dados' => $configuracoes->first(),
            ]);
    }

    /**
     * @param                             $id
     * @param Request $request
     * @param ConfiguracaoRepository      $configuracao
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request, ConfiguracaoRepository $configuracao)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                    $request, $validator
                );
        }

        $configuracao->save($request->all(), $id);

        flash()->success('Salvo com sucesso!');

        return redirect()->back();
    }

    protected function validator(array $data)
    {
        return \Validator::make($data, [
                'profundidade_unilevel' => 'required',
                'bonus_milha_cadastro' => 'required',
                'bonus_ciclo_hotel' => 'required',
                'custo_hotel' => 'required',
                'milhas_ciclo_hotel' => 'required',
                'validade_milhas_ciclo_hotel' => 'required',
                'diretos_qualificacao' => 'required',
            ]);
    }
}
