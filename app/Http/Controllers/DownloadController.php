<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use App\Models\Download;
use App\Models\DownloadTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DownloadRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DownloadController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master|admin', [
            'except' => [
                'download',
                'show',
            ],
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.downloads.index', [
            'title'                 => 'Lista de Downloads ',
            'dados'                 => Download::all(),
            'dados_desativados'     => Download::onlyTrashed()->get()->sortBy('created_at'), //Desativados
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.downloads.create', [
            'title' => 'Cadastro de Downloads ',
            'tipos' => DownloadTipo::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DownloadRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(DownloadRequest $request)
    {
        try {
            if (getenv('APP_ENV') != 'testing') {
                if ($request->hasFile('arquivo')) {
                    $nomeArquivo = str_slug($request->get('title'), '_').'.'.strtolower($request->file('arquivo')->getClientOriginalExtension());

                    $request->merge(['nomeArquivo' => $nomeArquivo]);
                    $request->merge(['extensao' => $request->file('arquivo')->getClientOriginalExtension()]);
                }

                $download = Download::create($request->all());

                if ($request->hasFile('arquivo') && ! $request->file('arquivo')->move(storage_path('/downloads/'), $nomeArquivo)) {
                    flash()->error('Desculpe, erro ao salvar o arquivo. Tente novamente, se o erro persistir contate o Administrador!');

                    return redirect()->route('download.edit', $download->id);
                }
            }

            flash()->success('Download <strong>'.$request->name.'</strong> adicionado com sucesso!');

            return redirect()->route('download.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao salvar Download');

            return redirect()->route('download.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($tipo = 1)
    {
        return view('default.downloads.show', [
            'title'                 => 'Lista de Downloads ',
            'dados'                 => Download::where('download_tipo_id', $tipo)->get(),
        ]);
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
            return view('default.downloads.edit', [
                'dados' => Download::findOrFail($id),
                'tipos' => DownloadTipo::all(),
                'title' => 'Edição de Download ',
            ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar o Download!');

            return redirect()->route('download.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DownloadRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(DownloadRequest $request, $id)
    {
        try {
            $download = Download::findOrFail($id);

            if ($request->hasFile('arquivo')) {
                $nomeArquivo = str_slug($request->get('title'), '_').'.'.strtolower($request->file('arquivo')->getClientOriginalExtension());

                $request->merge(['nomeArquivo' => $nomeArquivo]);
                $request->merge(['extensao' => $request->file('arquivo')->getClientOriginalExtension()]);
            }

            if ($request->hasFile('arquivo') && ! $request->file('arquivo')->move(storage_path('/downloads/'), $nomeArquivo)) {
                flash()->error('Desculpe, erro ao salvar o arquivo. Tente novamente, se o erro persistir contate o Administrador!');

                return redirect()->route('download.edit', $download->id);
            }

            $download->update($request->all());

            if (getenv('APP_ENV') != 'testing') {
                $download->save();
            }

            flash()->success('Download  '.$request->get('name').' editado com sucesso!');

            return redirect()->route('download.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao editar o Download.');

            return redirect()->route('download.index');
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
                $download = Download::find($id);
                Storage::deleteDirectory('downloads/'.$download->nomeArquivo);

                $download->forceDelete();

                flash()->success('Download deletado da base de dados com sucesso!');

                return redirect()->route('download.index');
            } catch (ModelNotFoundException $e) {
                flash()->error('Erro ao deletar o Download da base de dados!');

                return redirect()->route('download.index');
            }
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('download.index');
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
            Download::destroy($id);

            flash()->warning(sprintf('Download desativado com sucesso. Caso queira reativar o Download <a href="%s">clique aqui</a>.', route('download.recovery', $id)));

            return redirect()->route('download.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao desativar o Download.');

            return redirect()->route('download.index');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recovery($id)
    {
        try {
            Download::onlyTrashed()->findOrFail($id)->restore();

            flash()->success('Download ativado com sucesso!');

            return redirect()->route('download.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao ativar o Download.');

            return redirect()->route('download.index');
        }
    }

    public function download($id, $nomeArquivo)
    {
        try {
            $download = Download::findOrFail($id);

            return response()->download(storage_path('/downloads/'.$download->nomeArquivo));
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'cod_error' => $e->getCode()]);
        }
    }

    public function downloadInterno($nomeArquivo)
    {
        try {
            return response()->download(storage_path('app/retorno_boleto/'.$nomeArquivo));
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'cod_error' => $e->getCode()]);
        }
    }
}
