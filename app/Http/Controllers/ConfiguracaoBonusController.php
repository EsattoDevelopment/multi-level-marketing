<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracaoBonus;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConfiguracaoBonusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.configuracao_bonus.index', [
            'title' => 'Lista de Configuração de Bônus',
            'data' => ConfiguracaoBonus::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.configuracao_bonus.create', [
            'title' => 'Cadastro de configuração de bônus',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\ConfiguracaoBonusRequest $request)
    {
        try {
            $data = $request->except(['total_fixo', 'total_percentual', '_token']);
            $data['user_id'] = Auth::user()->id;
            ConfiguracaoBonus::create($data);

            flash()->success('Configuração de Bônus <strong>'.$request->nome.'</strong> cadastrada com sucesso!');

            Log::info('Configuração de bônus cadastrada User: ', $data);

            return redirect()->route('configuracao-bonus.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao salvar as configurações de bônus');

            Log::info('Erro ao cadastrar configurações de bônus', ['user' => Auth::user()->id]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $configuracaoBonus = ConfiguracaoBonus::with('titulosBonusAdesao', 'titulosBonusRentabilidade')->find($id);

        return view('default.configuracao_bonus.edit',[
            'title' => 'Edição de configuração de bônus',
            'dados' => $configuracaoBonus,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = $request->except(['total_fixo', 'total_percentual', '_token']);
            $data['user_id'] = Auth::user()->id;
            $configuracaoBonus = ConfiguracaoBonus::where('id', $id)->first();
            $configuracaoBonus->update($data);
            DB::commit();

            flash()->success('Configuração de Bônus <strong>'.$request->nome.'</strong> alterada com sucesso!');

            Log::info('Configuração de bônus alterada User: ', $data);

            return redirect()->route('configuracao-bonus.index');
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            flash()->error('Desculpe, erro ao salvar as alterações das configurações de bônus');

            Log::info('Erro ao alterar configurações de bônus #' . $id . 'Erro: ' . $e->getMessage());
            return redirect()->back()->withInput();
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
        //
    }
}
