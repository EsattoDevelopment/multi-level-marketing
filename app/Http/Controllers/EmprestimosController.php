<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracaoEmprestimo;
use App\Models\Emprestimo;
use App\Models\Sistema;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EmprestimosController extends Controller
{
    /**
     * @var Sistema|Sistema[]
     */
    private $sistema;

    public function __construct()
    {
        $this->sistema = Sistema::findOrFail(1);
    }

    /**
     * @return Factory|Application|View
     */
    public function index()
    {
        return view('default.emprestimo.emprestimos', [
            'sistema' => $this->sistema,
            'emprestimos' => Emprestimo::all(),
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|Application|View
     */
    public function calculadora(Request $request)
    {
        return view('default.emprestimo.calculadora', [
            'sistema' => $this->sistema,
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|Application|RedirectResponse|View
     */
    public function simular(Request $request)
    {
        $valor = $this->moneyToFloat($request->get('valor'));
        if ($valor < $this->sistema->min_emprestimo) {
            $valor_minimo_formatado = mascaraMoeda(
                $this->sistema->moeda,
                $this->sistema->min_emprestimo,
                2,
                true
            );
            flash()->error("O valor do empréstimo deve ser de pelo menos $valor_minimo_formatado.");
            return redirect()->back();
        }
        $configuracoes_emprestimos = $this->carregarConfiguracoesEmprestimos();
        foreach ($configuracoes_emprestimos as $nome => $grupo_configuracao_emprestimos) {
            foreach ($grupo_configuracao_emprestimos as $numero => $parcela) {
                $configuracoes_emprestimos[$nome][$numero]['valor_total'] = $this->calcularValorParcela($valor, $parcela);
            }
        }
        return view('default.emprestimo.calculadora', [
            'sistema' => $this->sistema,
            'parcelas' => $configuracoes_emprestimos,
            'valor' => $valor,
        ]);
    }

    /**
     * @return ConfiguracaoEmprestimo[]
     */
    private function carregarConfiguracoesEmprestimos(): array
    {
        $parcelas = ConfiguracaoEmprestimo::all();
        $parcelas_agrupadas = [];
        foreach ($parcelas as $parcela) {
            $parcelas_agrupadas[$parcela->grupo][$parcela->numero] = $parcela->toArray();
        }
        return $parcelas_agrupadas;
    }

    private function calcularValorParcela(float $valor, array $parcela): float
    {

        return ($valor + ($valor * ($parcela['valor_porcentagem'] * $parcela['numero']))) / $parcela['numero'];
    }

    private function moneyToFloat(string $value): float
    {
        $value = str_replace([$this->sistema->moeda, ' '], '', $value);
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        return (float) $value;
    }

    /**
     * @param Request $request
     * @return Factory|Application|RedirectResponse|View
     */
    public function getConfiguracoes(Request $request)
    {
        if (!Auth::user()->can('master')) {
            return redirect()->route('home');
        }
        return view('default.emprestimo.configuracoes', [
            'sistema' => $this->sistema,
            'configuracoes_emprestimos' => $this->carregarConfiguracoesEmprestimos(),
        ]);
    }

    /**
     * @param Request $request
     * @return Factory|Application|RedirectResponse|View
     */
    public function getPagar(Request $request)
    {
        if (!Auth::user()->can('master')) {
            return redirect()->route('home');
        }
        return view('default.emprestimo.pagar', [
            'sistema' => $this->sistema,
            'usuarios' => User::all(),
        ]);
    }

    public function pagar(Request $request): RedirectResponse
    {
        if (!Auth::user()->can('master')) {
            return redirect()->route('home');
        }
        $user_id = $request->get('user_id');
        $valor = $this->moneyToFloat($request->get('valor'));
        $chave_pix = $request->get('chave_pix');
        Emprestimo::create([
            'user_id' => $user_id,
            'valor' => $valor,
            'chave_pix' => $chave_pix,
            'status' => 'PEDIDO_REALIZADO',
        ]);
        return redirect()->route('emprestimos');
    }

    public function atualizarStatus(Request $request)
    {
        if (!Auth::user()->can('master')) {
            return redirect()->route('home');
        }
        $id = $request->get('id');
        $status = $request->get('status');
        Emprestimo::findOrFail($id)->update(['status' => $status]);
        return \Response::json();
    }
}
