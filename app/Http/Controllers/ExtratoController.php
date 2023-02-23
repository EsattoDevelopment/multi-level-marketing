<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Milhas;
use App\Models\Movimentos;
use App\Models\ExtratoBinario;
use App\Models\PontosPessoais;
use App\Models\PontosEquipeUnilevel;
use Illuminate\Support\Facades\Auth;
use App\Models\PontosEquipeEquiparacao;
use Yajra\Datatables\Facades\Datatables;

/**
 * Class ExtratoController.
 */
class ExtratoController extends Controller
{
    /**
     * ExtratoController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:master|admin', ['only' => ['saldoUsers', 'milhasUsers', 'pvUsers']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function financeiro()
    {
        $objMovimento = new Movimentos();
        $movimentos = $objMovimento
            ->with('operacao')
            ->whereUserId(Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('default.extrato.financeiro', [
            'title'                 => 'Extrato financeiro',
            'todos'                 => $movimentos,
            'totalGanhos'           => $objMovimento->totalGanhos(Auth::user()->id),
        ]);
    }

    public function equiparacao()
    {
        $objMovimento = new Movimentos();
        $movimentos = $objMovimento
            ->with('operacao')
            ->where('operacao_id', 17)
            ->whereUserId(Auth::user()->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('default.extrato.equiparacao', [
            'title'                 => 'Equiparação - Extrato Bônus',
            'bonus_equiparacao'     => $movimentos,
        ]);
    }

    public function direto()
    {
        $objMovimento = new Movimentos();
        $movimentos = $objMovimento->with('operacao')
            ->whereIn('operacao_id', [1, 20])
            ->whereUserId(Auth::user()->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('default.extrato.direto', [
            'title'                 => 'Extrato Bônus de contratos',
            'bonus' => $movimentos,
        ]);
    }

    public function royalties()
    {
        $objMovimento = new Movimentos();
        $movimentos = $objMovimento->with('operacao')
            ->whereIn('operacao_id', [27])
            ->whereUserId(Auth::user()->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('default.extrato.royalties', [
            'title'                 => 'Extrato de royalties',
            'ganhos' => $movimentos,
        ]);
    }

    public function royaltiesPagos()
    {
        $objMovimento = new Movimentos();
        $movimentos = $objMovimento->with('operacao')
            ->whereIn('operacao_id', [31])
            ->whereUserId(Auth::user()->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('default.extrato.royalties-pagos', [
            'title'                 => 'Extrato de royalties pagos',
            'ganhos' => $movimentos,
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function saldoUsers($id)
    {
        $objMovimento = new Movimentos();
        $movimentos = $objMovimento
            ->with('operacao')
            ->whereUserId($id)
            ->orderBy('created_at', 'desc')->get();

        return view('default.extrato.saldo', [
            'title'             => 'Extrato financeiro',
            'todos'             => $movimentos,
            'totalGanhos'       => $objMovimento->totalGanhos($id),
            'bonus_diretos'     => $movimentos->where('operacao_id', 1),
            'bonus_equiparacao' => $movimentos->where('operacao_id', 17),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function milhas()
    {
        $objMilhas = new Milhas();
        $milhas = $objMilhas
            ->whereUserId(Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('default.extrato.milhas', [
            'title'      => 'Extrato milhas',
            'dados'      => $milhas,
            'disponivel' => $milhas->where('referencia', 0)->sum('quantidade'),
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function milhasUsers($id)
    {
        $objMilhas = new Milhas();
        $milhas = $objMilhas->whereUserId($id)->orderBy('created_at', 'desc')->get();

        return view('default.extrato.milhas', [
            'title'      => 'Extrato milhas',
            'dados'      => $milhas,
            'disponivel' => $milhas->where('referencia', 0)->sum('quantidade'),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pv()
    {
        $objExtratoBinario = new ExtratoBinario();
        $pv = $objExtratoBinario->with([
            'operacao' => function ($query) {
                $query->select('id', 'name', 'cor');
            },
        ])->whereUserId(Auth::user()->id)->orderBy('created_at', 'desc')->select('id', 'created_at', 'pontos', 'operacao_id')->get();

        return view('default.extrato.pv', [
            'title' => 'Extrato pontos',
            'dados' => $pv,
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pvUsers($id)
    {
        $objExtratoBinario = new ExtratoBinario();
        $pv = $objExtratoBinario->with('operacao')->whereUserId($id)->orderBy('created_at', 'desc')->get();

        return view('default.extrato.pv', [
            'title' => 'Extrato pontos',
            'dados' => $pv,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function equipe()
    {
        return view('default.extrato.equipe', [
            'title' => 'Extrato de Pontos de Equipe',
        ]);
    }

    public function getExtratoPontosEquipe()
    {
        $pessoais = (new PontosEquipeEquiparacao())->with([
            'operacao' => function ($query) {
                $query->select('id', 'name', 'cor');
            },
        ])->whereUserId(Auth::user()->id)->orderBy('created_at', 'desc')->select('id', 'created_at', 'pontos', 'operacao_id')->get();

        $datatables = Datatables::of($pessoais)->editColumn('created_at', function ($pes) {
            return Carbon::parse($pes->created_at)->format('d/m/Y');
        })->addColumn('operacao', function ($pes) {
            return $pes->getRelation('operacao')->name;
        });

        return $datatables->with([
            'pontos' => $pessoais->sum('pontos'),
        ])->make(true);
    }

    /**
     * @return mixed
     */
    public function getExtratoUnilevel()
    {
        $unilevel = (new PontosEquipeUnilevel())->with([
            'operacao' => function ($query) {
                $query->select('id', 'name', 'cor');
            },
        ])->whereUserId(Auth::user()->id)->orderBy('created_at', 'desc')->select('id', 'created_at', 'pontos', 'operacao_id')->get();

        $datatables = Datatables::of($unilevel)->editColumn('created_at', function ($uni) {
            return Carbon::parse($uni->created_at)->format('d/m/Y');
        })->addColumn('operacao', function ($uni) {
            return $uni->getRelation('operacao')->name;
        });

        return $datatables->with([
            'pontos' => $unilevel->sum('pontos'),
        ])->make(true);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function pessoais()
    {
        return view('default.extrato.pessoais', [
            'title' => 'Extrato de Pontos Pessoais',
        ]);
    }

    /**
     * @return mixed
     */
    public function getExtratoPessoais()
    {
        $pessoais = (new PontosPessoais())->with([
            'operacao' => function ($query) {
                $query->select('id', 'name', 'cor');
            },
        ])->whereUserId(Auth::user()->id)->orderBy('created_at', 'desc')->select('id', 'created_at', 'pontos', 'operacao_id')->get();

        $datatables = Datatables::of($pessoais)->editColumn('created_at', function ($pes) {
            return Carbon::parse($pes->created_at)->format('d/m/Y');
        })->addColumn('operacao', function ($pes) {
            return $pes->getRelation('operacao')->name;
        });

        return $datatables->with([
            'pontos' => $pessoais->sum('pontos'),
        ])->make(true);
    }
}
