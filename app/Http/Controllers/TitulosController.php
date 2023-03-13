<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\ConfiguracaoBonus;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Log;
use App\Models\Titulos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\TitulosRequest;

class TitulosController extends Controller
{
    /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:master', ['only' => ['destroy']]);
        $this->middleware('permission:master|admin', ['except' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('default.titulos.index', [
            'title' => 'Lista de Titulos ',
            'data' => Titulos::all(),
            'data_desativados' => Titulos::withTrashed(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('default.titulos.create', [
            'title' => 'Cadastro de Titulos ',
            'titulos' => Titulos::all() ?? [],
            'configuracaoBonus' => ConfiguracaoBonus::where('status', 1)->get() ?? [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TitulosRequest $request
     * @return Response
     */
    public function store(TitulosRequest $request)
    {
        try {
            if ($request->get('titulo_inicial') == 1) {
                Titulos::where('titulo_inicial', 1)->update(['titulo_inicial' => 0]);
            }
            $arrayLimpo = array_filter($request->titulos_update);
            $request->merge(['titulos_update' => $arrayLimpo]);

            if($request->configuracao_bonus_adesao_id == 0)
                $request->merge(['configuracao_bonus_adesao_id' => null]);

            if($request->configuracao_bonus_rentabilidade_id == 0)
                $request->merge(['configuracao_bonus_rentabilidade_id' => null]);

            $body = $request->all();

            $body['cor'] = preg_replace('/\W/', '', $body['cor']);

            Titulos::create($body);

            flash()->success('Titulo <strong>'.$request->name.'</strong> adicionada com sucesso!');

            Log::info('Titulo Cadastrado', $request->except('_token'));

            return redirect()->route('titulo.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao salvar o Titulo');

            Log::info('Erro ao cadastrar ', ['user' => Auth::user()->id]);

            return redirect()->route('titulo.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        try {
            return view('default.titulos.edit', [
                'title' => 'Edição de Titulos ',
                'dados' => Titulos::findOrFail($id),
                'titulos' => Titulos::where('id', '<>', $id)->get(),
                'configuracaoBonus' => ConfiguracaoBonus::where('status',1)->get()
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao editar o Titulo');

            Log::info('Erro ao editar titulo :'.$id, ['user' => Auth::user()->id]);

            return redirect()->route('titulo.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TitulosRequest|Request $request
     * @param  int $id
     * @return Response
     */
    public function update(TitulosRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $titulo = Titulos::findOrFail($id);

            Log::info('Titulo', $titulo->toArray());

            if ($request->get('titulo_inicial') == 1) {
                Titulos::whereTituloInicial(1)->where('id', '<>', $id)->update(['titulo_inicial' => 0]);
                Log::info('Atualizado Titulo inicial para:'.$id, ['user' => Auth::user()->id]);
            }

            if ($request->has('titulos_update')) {
                $arrayLimpo = array_filter($request->titulos_update);
                $request->merge(['titulos_update' => $arrayLimpo]);
            }

            if($request->configuracao_bonus_adesao_id == 0)
                $request->merge(['configuracao_bonus_adesao_id' => null]);

            if($request->configuracao_bonus_rentabilidade_id == 0)
                $request->merge(['configuracao_bonus_rentabilidade_id' => null]);


            $body = $request->all();

            $body['cor'] = preg_replace('/\W/', '', $body['cor']);

            $titulo->update($body);

            DB::commit();

            flash()->success('Titulo <strong>'.$request->name.'</strong> adicionada com sucesso!');

            Log::info('Titulo Atualizado', $request->except('_token'));

            return redirect()->route('titulo.index');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, erro ao editar o Titulo');

            Log::info('Erro ao editar titulo: '.$id, ['user' => Auth::user()->id]);

            return redirect()->route('titulo.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id)
    {
        try {
            Titulos::withTrashed()->findOrFail($id)->delete();

            flash()->success('Titulo deletado da base de dados com sucesso!');

            return redirect()->route('titulo.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Erro ao deletar o Titulo da base de dados!');

            return redirect()->route('titulo.index');
        }
    }

    public function destroy($id)
    {
        try {
            Titulos::withTrashed()->findOrFail($id)->forceDelete();

            flash()->success('Titulo deletado da base de dados com sucesso!');

            return redirect()->route('titulo.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Erro ao deletar o Titulo da base de dados!');

            return redirect()->route('titulo.index');
        }
    }
}
