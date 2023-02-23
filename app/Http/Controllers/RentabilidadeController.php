<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use App\Models\Itens;
use App\Models\Sistema;
use App\Models\Titulos;
use Illuminate\Http\Request;
use App\Models\Rentabilidade;
use App\Models\PedidosMovimentos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\RentabilidadeServices;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\RentabilidadeRequest;
use App\Services\RentabilidadeContaServices;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RentabilidadeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master', ['only' => ['destroy']]);
        $this->middleware('permission:master|admin', ['except' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.rentabilidade.index', [
            'title' => 'Rentabilidade',
            'dados' => Rentabilidade::groupBy('data')->orderby('data', 'DESC')->get(),
            'permitirCadastro' => (Rentabilidade::where('data', '=', date('Y-m-d'))->count() == 0),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.rentabilidade.create', [
            'title' => 'Cadastro de rentabilidade',
            'titulos' => Titulos::all(),
            'itens' => Itens::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RentabilidadeRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $sistema = $sistema = Sistema::findOrFail(1);

            if ($sistema['rendimento_titulo'] == 1) {
                foreach ($request->titulos as $titulo_id => $titulo) {
                    Rentabilidade::create([
                        'item_id' => null,
                        'titulo_id' => $titulo_id,
                        'valor_fixo' => $titulo['valor_fixo'],
                        'percentual' => $titulo['percentual'],
                        'pago' => 0,
                        'data' => date('Y-m-d'),
                    ]);
                }
            }

            if ($sistema['rendimento_item'] == 1) {
                foreach ($request->itens as $item_id => $item) {
                    Rentabilidade::create([
                        'item_id' => $item_id,
                        'titulo_id' => null,
                        'valor_fixo' => $item['valor_fixo'],
                        'percentual' => $item['percentual'],
                        'pago' => 0,
                        'data' => date('Y-m-d'),
                    ]);
                }
            }

            DB::commit();

            flash()->success('Rentabilidade adicionada com sucesso!');

            Log::info('Rentabilidade Cadastrada', $request->except('_token'));

            return redirect()->route('rentabilidade.index');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, erro ao salvar a Rentabilidade');

            return redirect()->route('rentabilidade.index');
        }
    }

    /**
     * Show the form for viewer the specified resource.
     *
     * @param  date  $data
     * @return \Illuminate\Http\Response
     */
    public function viewer($data)
    {
        try {
            Log::info('Abriu visualização rentabilidade!', ['data' => $data, 'user' => Auth::user()->id]);

            $dados = DB::table('rentabilidades as r')
                ->leftJoin('titulos as t', 't.id', '=', 'r.titulo_id')
                ->leftJoin('itens as i', 'i.id', '=', 'r.item_id')
                ->select(['r.*', 't.name as titulo', 'i.name as item'])
                ->whereNull('r.deleted_at')
                ->where('r.data', '=', $data)
                ->get();

            $keys = [];
            foreach ($dados as $dado) {
                $keys[] = $dado->id;
            }
            $totalPago = PedidosMovimentos::where('operacao_id', 7)->whereIn('rentabilidade_id', $keys)->sum('valor_manipulado');

            return view('default.rentabilidade.viewer', [
                'data' => $data,
                'dados' => $dados,
                'totalPago' => $totalPago,
                'title' => 'Visualização de Rentabilidade',
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Erro ao abrir pagina edição Rentabilidade!', ['data' => $data, 'message' => $e->getMessage(), 'user' => Auth::user()->id]);

            flash()->error('Desculpe, ocorreu um erro ao buscar a Rentabilidade!');

            return redirect()->route('rentabilidade.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  date  $data
     * @return \Illuminate\Http\Response
     */
    public function edit($data)
    {
        //dd();
        try {
            Log::info('Abriu edição rentabilidade!', ['data' => $data, 'user' => Auth::user()->id]);

            return view('default.rentabilidade.edit', [
                'data' => $data,
                'dados' => DB::table('rentabilidades as r')
                ->leftJoin('titulos as t', 't.id', '=', 'r.titulo_id')
                ->leftJoin('itens as i', 'i.id', '=', 'r.item_id')
                ->select(['r.*', 't.name as titulo', 'i.name as item'])
                ->whereNull('r.deleted_at')
                ->where('r.data', '=', $data)
                ->get(),
                'title' => 'Edição de Rentabilidade',
            ]);
        } catch (ModelNotFoundException $e) {
            Log::error('Erro ao abrir pagina edição Rentabilidade!', ['data' => $data, 'message' => $e->getMessage(), 'user' => Auth::user()->id]);

            flash()->error('Desculpe, ocorreu um erro ao buscar a Rentabilidade!');

            return redirect()->route('rentabilidade.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RentabilidadeRequest|Request $request
     * @param  Request $request, date $data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $data)
    {
        DB::beginTransaction();
        try {
            $sistema = $sistema = Sistema::findOrFail(1);

            if ($sistema['rendimento_titulo'] == 1) {
                foreach ($request->titulos as $titulo_id => $titulo) {
                    $rentabilidade = Rentabilidade::where(['data' => $data, 'titulo_id' => $titulo_id, 'pago' => 0])->firstOrFail();

                    $rentabilidade->valor_fixo = $titulo['valor_fixo'];
                    $rentabilidade->percentual = $titulo['percentual'];
                    $rentabilidade->update();
                }
            }

            if ($sistema['rendimento_item'] == 1) {
                foreach ($request->itens as $item_id => $item) {
                    $rentabilidade = Rentabilidade::where(['data' => $data, 'item_id' => $item_id, 'pago' => 0])->firstOrFail();

                    $rentabilidade->valor_fixo = $item['valor_fixo'];
                    $rentabilidade->percentual = $item['percentual'];
                    $rentabilidade->update();
                }
            }

            DB::commit();

            flash()->success('Rentabilidade atualizada com sucesso!');

            Log::info('Rentabilidade Atualizada', $request->except('_token'));

            return redirect()->route('rentabilidade.index');
        } catch (ModelNotFoundException $e) {
            DB::rollback();

            flash()->error('Desculpe, erro ao atualizar a Rentabilidade');

            return redirect()->route('rentabilidade.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  date $data
     * @return \Illuminate\Http\Response
     */
    public function destroy($data)
    {
        if (Auth::user()->can('master')) {
            try {
                Rentabilidade::where(['data'=> $data, 'pago' => 0])->forceDelete();

                Log::error('Rentabilidade apagada!', ['data' => $data, 'user' => Auth::user()->id]);

                flash()->success('Registro deletado da base de dados com sucesso!');

                return redirect()->route('rentabilidade.index');
            } catch (ModelNotFoundException $e) {
                Log::error('Erro ao apagar o registro!', ['data' => $data, 'message' => $e->getMessage()]);

                flash()->error('Erro ao deletar o registro da base de dados!');

                return redirect()->route('rentabilidade.index');
            }
        }

        Log::alert('Sem permissão!', ['data' => $data, 'user' => Auth::user()->id, 'Controller' => 'User', 'method' => 'destroy']);

        flash()->error('Você não tem privilégios suficientes para esta operação!');

        return redirect()->route('rentabilidade.index');
    }

    /**
     * Remove the specified resource from storage.(with soft deletes).
     *
     * @param  date $data
     * @return \Illuminate\Http\Response
     */
    public function delete($data)
    {
        try {
            Rentabilidade::where('data', '=', $data)->Delete();
            Log::error('Registro desativado!', ['data' => $data, 'user' => Auth::user()->id]);

            flash()->warning(sprintf('Rentabilidade desativada com sucesso. Caso queira reativar o Item <a href="%s">clique aqui</a>.', route('rentabilidade.recovery', $data)));

            return redirect()->route('rentabilidade.index');
        } catch (ModelNotFoundException $e) {
            Log::error('Erro ao desativar o registro!', ['data' => $data, 'message' => $e->getMessage(), 'user' => Auth::user()->id]);

            flash()->error('Desculpe, erro ao desativar o registro!');

            return redirect()->route('rentabilidade.index');
        }
    }

    /**
     * @param date $data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recovery($data)
    {
        try {
            Rentabilidade::where('data', '=', $data)->restore();

            Log::error('Registro ativado!', ['data' => $data, 'user' => Auth::user()->id]);

            flash()->success('Registro ativado com sucesso!');

            return redirect()->route('rentabilidade.index');
        } catch (ModelNotFoundException $e) {
            Log::error('Erro ao ativar o registro!', ['data' => $data, 'message' => $e->getMessage(), 'user' => Auth::user()->id]);

            flash()->error('Desculpe, ocorreu um erro ao ativar o registro.');

            return redirect()->route('rentabilidade.index');
        }
    }

    public function pagar($data)
    {
        DB::beginTransaction();

        try {
            $rentabilidades = Rentabilidade::with('item', 'titulo')->where('data', $data)->get();

            \Log::info("\n************************************************************ Rentabilidade ************************************************************");
            (new RentabilidadeServices())
                ->rentabilidade($rentabilidades)
                ->pagar();

            //Pega rentabilidade da conta
            $rentabilidadeConta = Rentabilidade::with('item', 'titulo')
                ->whereHas('item', function ($query) {
                    $query->where('tipo_pedido_id', 4)
                        ->where('ativo', 1);
                })
                ->where('data', $data)->first();

            //paga rentabilidade da conta digital
            if ($rentabilidadeConta) {
                (new RentabilidadeContaServices())
                    ->rentabilidade($rentabilidadeConta)
                    ->pagar();
            }

            foreach ($rentabilidades as $rentabilidade) {
                $rentabilidade->update(['pago' => 1]);
            }

            DB::commit();

            flash()->success('Rendimento salvo com sucesso!');
            \Log::info("\n************************************************************ Rentabilidade - Fim ************************************************************");
        } catch (\Exception $e) {
            DB::rollback();

            flash()->error('Erro ao pagar rentabilidade');
            \Log::info("\n************************************************************ Rentabilidade - Erro ************************************************************");
        }

        return redirect()->back();
    }
}
