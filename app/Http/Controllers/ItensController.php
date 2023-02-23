<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\ConfiguracaoBonus;
use Log;
use App\Models\Itens;
use App\Models\Titulos;
use App\Models\TipoPedidos;
use Illuminate\Http\Request;
use App\Http\Requests\ItensRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Saude\Repositories\ExameRepository;

class ItensController extends Controller
{
    private $examesRepository;

    public function __construct()
    {
        $this->middleware('permission:master', ['only' => ['destroy']]);
        $this->middleware('permission:master|admin', ['except' => ['destroy']]);
        $this->examesRepository = new ExameRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.itens.index', [
            'title' => 'Planos',
            'dados' => Itens::with('tipoPedidos')->orderBy('ordem_exibicao')->get(),
            'dados_desativados' => Itens::onlyTrashed()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.itens.create', [
            'title' => 'Cadastro de Planos',
            'tipo_pedidos' => TipoPedidos::all(),
            'titulos' => Titulos::all(),
            'exames' => $this->examesRepository->getAll([], ['id', 'nome']),
            'configuracaoBonus' => ConfiguracaoBonus::where('status',1)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ItensRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItensRequest $request)
    {
        try {
            $request['faixa_deposito_min'] = str_replace('.', '', $request['faixa_deposito_min']);
            $request['faixa_deposito_min'] = str_replace(',', '.', $request['faixa_deposito_min']);
            $request['faixa_deposito_max'] = str_replace('.', '', $request['faixa_deposito_max']);
            $request['faixa_deposito_max'] = str_replace(',', '.', $request['faixa_deposito_max']);

            if($request->configuracao_bonus_adesao_id == 0)
                $request->merge(['configuracao_bonus_adesao_id' => null]);

            if($request->configuracao_bonus_rentabilidade_id == 0)
                $request->merge(['configuracao_bonus_rentabilidade_id' => null]);

            $item = Itens::create($request->all());

            if ($request->hasFile('imagem')) {
                $nameImage = str_slug($request->get('name'), '_').'.'.strtolower($request->file('imagem')->getClientOriginalExtension());

                Storage::put('images/itens/'.$item->id.'/'.$nameImage, File::get($request->file('imagem')->getRealPath()));

                $item->imagem = $nameImage;
                $item->save();
            }

            if ($request->has('exames')) {
                $item->exames()->attach($request->get('exames'));
            }

            flash()->success('Item <strong>'.$request->get('name').'</strong> adicionada com sucesso!');

            Log::info('Planos Cadastrado', $request->except('_token'));

            return redirect()->route('item.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao salvar o Item');

            return redirect()->route('item.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            Log::info('Abriu edição itens!', ['id' => $id, 'user' => Auth::user()->id]);

            return view('default.itens.edit', [
                'dados' => Itens::with('empresa', 'exames')->findOrFail($id),
                'title' => 'Edição de Planos',
                'tipo_pedidos' => TipoPedidos::all(),
                'titulos' => Titulos::all(),
                'exames' => $this->examesRepository->getAll([], ['id', 'nome']),
                'configuracaoBonus' => ConfiguracaoBonus::where('status',1)->get()
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Erro ao abrir pagina edição Item!', ['id' => $id, 'message' => $e->getMessage(), 'user' => Auth::user()->id]);

            flash()->error('Desculpe, ocorreu um erro ao buscar o Item!');

            return redirect()->route('item.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ItensRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItensRequest $request, $id)
    {
        try {
            $request['faixa_deposito_min'] = str_replace('.', '', $request['faixa_deposito_min']);
            $request['faixa_deposito_min'] = str_replace(',', '.', $request['faixa_deposito_min']);
            $request['faixa_deposito_max'] = str_replace('.', '', $request['faixa_deposito_max']);
            $request['faixa_deposito_max'] = str_replace(',', '.', $request['faixa_deposito_max']);

            if($request->configuracao_bonus_adesao_id == 0)
                $request->merge(['configuracao_bonus_adesao_id' => null]);

            if($request->configuracao_bonus_rentabilidade_id == 0)
                $request->merge(['configuracao_bonus_rentabilidade_id' => null]);

            $item = Itens::findOrFail($id);
            $dados = $request->except('imagem');

            Log::info('Planos antes da edição', $item->toArray());
            if ($request->hasFile('imagem')) {
                if (Storage::exists('/images/itens/'.$item->id)) {
                    Storage::deleteDirectory('/images/itens/'.$item->id);
                }

                $nameImage = str_slug($request->get('name'), '_'.substr(md5($request->get('name')), 0, 5)).'.'.strtolower($request->file('imagem')->getClientOriginalExtension());

                Storage::put('images/itens/'.$item->id.'/'.$nameImage, File::get($request->file('imagem')->getRealPath()));

                $dados['imagem'] = $nameImage;
            }

            $item->update($dados);

            if ($request->has('exames')) {
                $item->exames()->sync($request->get('exames'));
            } else {
                $item->exames()->detach();
            }

            flash()->success('Planos  '.$request->get('name').' editado com sucesso!');

            $request->merge(['user' => Auth::user()->id]);

            Log::info('Planos editado!', $request->except(['_token', 'imagem']));

            return redirect()->route('item.index');
        } catch (ModelNotFoundException $e) {
            Log::error('Erro ao editar Planos!', ['id' => $id, 'message' => $e->getMessage(), 'user' => Auth::user()->id]);

            flash()->error('Desculpe, erro ao editar o registro!');

            return redirect()->route('item.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('master')) {
            try {
                Storage::deleteDirectory('/images/itens/'.$id);

                Itens::withTrashed()->findOrFail($id)->forceDelete();

                Log::error('Item apagado!', ['id' => $id, 'user' => Auth::user()->id]);

                flash()->success('Registro deletado da base de dados com sucesso!');

                return redirect()->route('item.index');
            } catch (ModelNotFoundException $e) {
                Log::error('Erro ao apagar o registro!', ['id' => $id, 'message' => $e->getMessage()]);

                flash()->error('Erro ao deletar o registro da base de dados!');

                return redirect()->route('item.index');
            }
        } else {
            Log::alert('Sem permissão!', ['id' => $id, 'user' => Auth::user()->id, 'Controller' => 'User', 'method' => 'destroy']);

            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('item.index');
        }
    }

    /**
     * Remove the specified resource from storage.(with soft deletes).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            Itens::destroy($id);

            Log::error('Registro desativado!', ['id' => $id, 'user' => Auth::user()->id]);

            flash()->warning(sprintf('Item desativado com sucesso. Caso queira reativar o Item <a href="%s">clique aqui</a>.', route('item.recovery', $id)));

            return redirect()->route('item.index');
        } catch (ModelNotFoundException $e) {
            Log::error('Erro ao desativar o registro!', ['id' => $id, 'message' => $e->getMessage(), 'user' => Auth::user()->id]);

            flash()->error('Desculpe, erro ao desativar o registro!');

            return redirect()->route('item.index');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recovery($id)
    {
        try {
            Itens::onlyTrashed()->findOrFail($id)->restore();

            Log::error('Registro ativado!', ['id' => $id, 'user' => Auth::user()->id]);

            flash()->success('Registro ativado com sucesso!');

            return redirect()->route('item.index');
        } catch (ModelNotFoundException $e) {
            Log::error('Erro ao ativar o registro!', ['id' => $id, 'message' => $e->getMessage(), 'user' => Auth::user()->id]);

            flash()->error('Desculpe, ocorreu um erro ao ativar o registro.');

            return redirect()->route('item.index');
        }
    }
}
