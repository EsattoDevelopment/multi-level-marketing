<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Requests;
use App\Models\Plataforma;
use App\Models\PlataformaConta;
use Illuminate\Support\Facades\Log;
use App\Models\RentabilidadeHistorico;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RentabilidadeHistoricoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.rentabilidade_historico.index', [
            'title'                 => 'Lista de Histórico de Operações',
            'dados'                 => RentabilidadeHistorico::with('plataformaconta')->where('status', 1)->get(),
            'dados_desativados'     => RentabilidadeHistorico::with('plataformaconta')->where('status', 0)->get(), //Desativados
            'plataforma' => Plataforma::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($plataforma_id = 0, $conta_id = 0)
    {
        $conta = null;
        if ($plataforma_id != 0) {
            $plataforma = Plataforma::where('id', $plataforma_id)->first();
        } else {
            $plataforma = Plataforma::all();
        }

        if ($conta_id != 0) {
            $conta = PlataformaConta::where('id', $conta_id)->first();
        }

        return view('default.rentabilidade_historico.create', [
            'title' => 'Cadastro de Histórico de Operações',
            'noimage' => route('imagecache', ['geral', 'no-image.jpeg']),
            'plataforma' => $plataforma,
            'conta' => $conta,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\RentabilidadeHistoricoRequest $request)
    {
        try {
            $dataHora = str_replace(':', '-', Carbon::now());
            $dataHora = str_replace(' ', '_', $dataHora);
            //$rentabilidadeHistorico = RentabilidadeHistorico::create($request->except('arquivo', 'documento'));

            if ($request->hasFile('arquivo')) {
                $nameImage = "rentabilidade_image_{$dataHora}.".strtolower($request->file('arquivo')->getClientOriginalExtension());
                if (Storage::exists('rentabilidade/images/'.$nameImage)) {
                    Storage::delete('rentabilidade/images/'.$nameImage);
                }

                $request->file('arquivo')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/rentabilidade/images/', $nameImage);
                $request->merge(['path_imagem' => $nameImage]);
                //$rentabilidadeHistorico->path_imagem = $nameImage;
            }

            if ($request->hasFile('documento')) {
                $nameDocumento = "rentabilidade_doc_{$dataHora}.".strtolower($request->file('documento')->getClientOriginalExtension());
                if (Storage::exists('rentabilidade/docs/'.$nameDocumento)) {
                    Storage::delete('rentabilidade/docs/'.$nameDocumento);
                }

                $request->file('documento')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/rentabilidade/docs/', $nameDocumento);
                $request->merge(['path_documento' => $nameDocumento]);
                //$rentabilidadeHistorico->path_imagem = $nameImage;
            }

            $request->merge(['valor' => str_replace(',', '', $request->valor)]);
            $request->merge(['percentual' => str_replace(',', '', $request->percentual)]);
            RentabilidadeHistorico::create($request->all());

            Log::info('Histórico de operação Cadastrado');
            flash()->success('Histórico de rentabilidade <strong>'.$request->titulo.'</strong> cadastrado com sucesso!');

            return redirect()->route('operacao-historico.index');
        } catch (ModelNotFoundException $e) {
            Log::info('Erro ao cadastrar histórico de operação');
            flash()->error('Desculpe, erro ao salvar o histórico de operação');

            return redirect()->route('operacao-historico.index');
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
        $rentabilidadeHistorico = RentabilidadeHistorico::findOrFail($id);
        $conta = PlataformaConta::where('id', $rentabilidadeHistorico->plataforma_conta_id)->first();
        $plataforma = Plataforma::all();
        $contaPlataforma = PlataformaConta::where('plataforma_id', $conta->plataforma_id)->get();

        try {
            return view('default.rentabilidade_historico.edit', [
                'dados' => $rentabilidadeHistorico,
                'title' => 'Edição do Histórico de Operações ',
                'noimage' => route('imagecache', ['geral', 'no-image.jpeg']),
                'plataforma' => $plataforma,
                'conta' => $conta,
                'contaPlataforma' => $contaPlataforma,
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar o histórico de operação!');

            return redirect()->route('operacao-historico.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\RentabilidadeHistoricoRequest $request, $id)
    {
        try {
            $rentabilidadeHistorico = RentabilidadeHistorico::findOrFail($id);

            $this->trataArquivo($request, $rentabilidadeHistorico);

            $dataHora = str_replace(':', '-', Carbon::now());
            $dataHora = str_replace(' ', '_', $dataHora);

            if ($request->hasFile('arquivo')) {
                $nameImage = "rentabilidade_img_{$dataHora}.".strtolower($request->file('arquivo')->getClientOriginalExtension());
                $request->merge(['path_imagem' => $nameImage]);
            }

            if ($request->hasFile('arquivo') && ! $request->file('arquivo')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/rentabilidade/images/', $nameImage)) {
                flash()->error('Desculpe, erro ao salvar a imagem. Tente novamente, se o erro persistir contate o Administrador!');

                return redirect()->route('operacao-historico.edit', $id);
            }

            if ($request->hasFile('documento')) {
                $nameDoc = "rentabilidade_doc_{$dataHora}.".strtolower($request->file('documento')->getClientOriginalExtension());
                $request->merge(['path_documento' => $nameDoc]);
            }

            if ($request->hasFile('documento') && ! $request->file('documento')->move(Storage::disk('interno')->getDriver()->getAdapter()->getPathPrefix().'/rentabilidade/docs/', $nameDoc)) {
                flash()->error('Desculpe, erro ao salvar o documento. Tente novamente, se o erro persistir contate o Administrador!');

                return redirect()->route('operacao-historico.edit', $id);
            }
            $request->merge(['valor' => str_replace(',', '', $request->valor)]);
            $request->merge(['percentual' => str_replace(',', '', $request->percentual)]);

            $rentabilidadeHistorico->update($request->all());

            Log::info('Histórico de operação alterado com sucesso');
            flash()->success('Histórico de operação <strong>'.$request->titulo.'</strong> alterado com sucesso!');

            return redirect()->route('operacao-historico.index');
        } catch (ModelNotFoundException $e) {
            Log::info('Erro ao alterar histórico de operação');
            flash()->error('Desculpe, erro ao alterar o histórico de roperação');

            return redirect()->route('operacao-historico.index');
        }
    }

    private function trataArquivo(&$request, &$rentabilidadeHistorico)
    {
        if (isset($request->excluir_arquivo) || $request->hasFile('arquivo')) {
            if (Storage::disk('interno')->exists('rentabilidade/images/'.$rentabilidadeHistorico->path_imagem)) {
                Storage::disk('interno')->delete('rentabilidade/images/'.$rentabilidadeHistorico->path_imagem);
                $request->merge(['path_imagem' => '']);
            }
        }

        if (isset($request->excluir_documento) || $request->hasFile('documento')) {
            if (Storage::disk('interno')->exists('rentabilidade/docs/'.$rentabilidadeHistorico->path_documento)) {
                Storage::disk('interno')->delete('rentabilidade/docs/'.$rentabilidadeHistorico->path_documento);
                $request->merge(['path_documento' => '']);
            }
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

    public function desativar($id)
    {
        try {
            $rentabilidadeHistorico = RentabilidadeHistorico::find($id);
            $rentabilidadeHistorico->status = 0;
            $rentabilidadeHistorico->update();

            flash()->success('Histórico de operação desativado com sucesso!');

            return redirect()->route('operacao-historico.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao desativar o histórico de operação.');

            return redirect()->route('operacao-historico.index');
        }
    }

    public function ativar($id)
    {
        try {
            $rentabilidadeHistorico = RentabilidadeHistorico::find($id);
            $rentabilidadeHistorico->status = 1;
            $rentabilidadeHistorico->update();

            flash()->success('Histórico de operação ativado com sucesso!');

            return redirect()->route('operacao-historico.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao ativar o histórico de operação.');

            return redirect()->route('operacao-historico.index');
        }
    }

    public function visualizarDocumento($nomeArquivo)
    {
        //dd(storage_path('app/rentabilidade/docs/'.$nomeArquivo));
        try {
            return response()->download(storage_path('app/rentabilidade/docs/'.$nomeArquivo));
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'cod_error' => $e->getCode()]);
        }
    }
}
