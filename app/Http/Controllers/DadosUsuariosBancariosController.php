<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use App\Models\User;
use App\Models\Bancos;
use Illuminate\Http\Request;
use App\Models\DadosBancarios;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendDadosBancariosEmail;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\DadosUsuarioDadosBancariosRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class DadosUsuariosBancariosController.
 */
class DadosUsuariosBancariosController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $id = Auth::user()->id;

        return view('default.dados-usuario.dados-bancarios.index', [
            'usuario'        => User::with('titulo')->findOrFail($id),
            'title'          => 'Dados Bancários - Dados do usuário',
            'contas'         => User::with('dadosBancarios')->findOrFail($id)->getRelation('dadosBancarios'),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $id = Auth::user()->id;

        return view('default.dados-usuario.dados-bancarios.create', [
            'usuario'        => User::with('titulo')->findOrFail($id),
            'title'          => 'Cadastrar Conta - Dados do usuário',
            'bancos'         => Bancos::all(),
        ]);
    }

    /**
     * @param DadosUsuarioDadosBancariosRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DadosUsuarioDadosBancariosRequest $request)
    {
        try {
            $dBancarios = $request->get('d_bancarios');
            $dBancarios['user_id_editor'] = Auth::user()->id;
            $dBancarios['user_id'] = Auth::user()->id;

            DadosBancarios::create($dBancarios);
            Log::info('Dados bancarios Cadastrado', $dBancarios);

            flash()->success('Nova conta cadastrada com sucesso!');

            return redirect()->route('dados-usuario.dados-bancarios');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao cadastrar sua conta');

            Log::info('Erro ao cadastrar conta do usuario: '.$request->get('user_id'), ['user' => Auth::user()->id, 'd_bancarios' => $dBancarios]);

            return redirect()->route('dados-usuario.dados-bancarios');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $conta = DadosBancarios::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
            $conta->delete();

            flash()->success('Conta excluída com sucesso.');

            return redirect()->route('dados-usuario.dados-bancarios');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao excluir conta.');

            return redirect()->route('dados-usuario.dados-bancarios');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function comprovante(Request $request)
    {
        if ($request->has('bank')) {
            try {
                $conta = DadosBancarios::where('id', $request->get('bank'))->where('user_id', Auth::user()->id)->firstOrFail();

                if ($request->hasFile('comprovante') && $conta->status_comprovante !== 'validado') {
                    if ($conta->imagem_comprovante) {
                        if (Storage::disk('interno')->exists('documentos/'.$conta->imagem_comprovante)) {
                            Storage::disk('interno')->delete('documentos/'.$conta->imagem_comprovante);
                        }
                    }

                    $comprovanteImage = str_slug(uniqid(), '_').'.'.strtolower($request->file('comprovante')->getClientOriginalExtension());
                    $request->file('comprovante')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/documentos', $comprovanteImage);

                    $conta->imagem_comprovante = $comprovanteImage;
                    $conta->status_comprovante = 'em_analise';
                    $conta->save();

                    // avisar que tem um novo comprovante.
                    $this->dispatch(new SendDadosBancariosEmail($conta));

                    return response()->json([
                        'success' => true,
                        'msg' => 'Seu comprovante foi enviado para análise.',
                        'flash' => 'success',
                    ], 200);
                }
            } catch (\Exception $e) {
            }
        }

        return response()->json([], 500);
    }
}
