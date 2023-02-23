<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Class EmpresaController.
 */
class EmpresaController extends Controller
{
    /**
     * EmpresaController constructor.
     */
    public function __construct()
    {
        $this->middleware('permission:master|admin', [
            'except' => ['termo'],
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
        $cores = [
                'black' => 'Preto',
                'black-light' => 'Preto/Claro',
                'blue' => 'Azul',
                'blue-light' => 'Azul/Claro',
                'green' => 'Verde',
                'green-light' => 'Verde/Claro',
                'purple' => 'Roxo',
                'purple-light' => 'Roxo/Claro',
                'red' => 'Vermelho',
                'red-light' => 'Vermelho/Claro',
                'yellow' => 'Amarelo',
                'yellow-light' => 'Amarelo/Claro',
                'aquamarine' => 'Verde Azulado',
                'aquamarine-light' => 'Verde Azulado/Claro',
            ];

        return view('default.empresa.edit', [
                'title' => 'Empresa edição',
                'dados' => Empresa::findOrFail(1),
                'cores' => $cores,
            ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $empresa = Empresa::findOrFail($id);
            $empresa->update($request->all());

            Log::info('empresa antes da edição', $empresa->toArray());

            // Termo
            if ($request->has('apagar_termo_inicial')) {
                if (Storage::exists('empresa/arquivo/'.$empresa->apagar_termo_inicial)) {
                    Storage::delete('empresa/arquivo/'.$empresa->apagar_termo_inicial);
                }
                $empresa->apagar_termo_inicial = null;
            }

            if ($request->hasFile('termo_inicial')) {
                if (Storage::exists('empresa/arquivo/'.$empresa->termo_inicial)) {
                    Storage::delete('empresa/arquivo/'.$empresa->termo_inicial);
                }

                $nameFile = 'termo_inicial.'.strtolower($request->file('termo_inicial')->getClientOriginalExtension());

                Storage::put('empresa/arquivo/'.$nameFile, file_get_contents($request->file('termo_inicial')->getRealPath()));

                $empresa->termo_inicial = $nameFile;
            }

            // Logo
            if ($request->has('apagar_logo')) {
                if (Storage::exists('images/empresa/'.$empresa->apagar_logo)) {
                    Storage::delete('images/empresa/'.$empresa->apagar_logo);
                }
                $empresa->apagar_logo = null;
            }

            if ($request->hasFile('logo')) {
                if (Storage::exists('images/empresa/'.$empresa->logo)) {
                    Storage::delete('images/empresa/'.$empresa->logo);
                }

                $nameImage = 'logo.'.strtolower($request->file('logo')->getClientOriginalExtension());

                Storage::put('images/empresa/'.$nameImage, file_get_contents($request->file('logo')->getRealPath()));

                $empresa->logo = $nameImage;
            }

            // Favicon
            if ($request->has('apagar_favicon')) {
                if (Storage::exists('images/empresa/'.$empresa->apagar_favicon)) {
                    Storage::delete('images/empresa/'.$empresa->apagar_favicon);
                }
                $empresa->apagar_favicon = null;
            }

            if ($request->hasFile('favicon')) {
                if (Storage::exists('images/empresa/'.$empresa->favicon)) {
                    Storage::delete('images/empresa/'.$empresa->favicon);
                }

                $nameImage = 'favicon.'.strtolower($request->file('favicon')->getClientOriginalExtension());

                Storage::put('images/empresa/'.$nameImage, file_get_contents($request->file('favicon')->getRealPath()));

                $empresa->favicon = $nameImage;
            }

            if ($request->has('apagar_logo_flutuante')) {
                if (Storage::exists('images/empresa/'.$empresa->logo_flutuante)) {
                    Storage::delete('images/empresa/'.$empresa->logo_flutuante);
                }
                $empresa->logo_flutuante = null;
            }

            // Logo flutuante telas de login
            if ($request->hasFile('logo_flutuante')) {
                if (Storage::exists('images/empresa/'.$empresa->logo_flutuante)) {
                    Storage::delete('images/empresa/'.$empresa->logo_flutuante);
                }

                $nameImage = 'logo_flutuante.'.strtolower($request->file('logo_flutuante')->getClientOriginalExtension());

                Storage::put('images/empresa/'.$nameImage, file_get_contents($request->file('logo_flutuante')->getRealPath()));

                $empresa->logo_flutuante = $nameImage;
            }

            // Logo e-mail
            if ($request->has('apagar_logo_email')) {
                if (Storage::exists('images/empresa/'.$empresa->apagar_logo_email)) {
                    Storage::delete('images/empresa/'.$empresa->apagar_logo_email);
                }
                $empresa->apagar_logo_email = null;
            }

            if ($request->hasFile('logo_email')) {
                if (Storage::exists('images/empresa/'.$empresa->logo_email)) {
                    Storage::delete('images/empresa/'.$empresa->logo_email);
                }

                $nameImage = 'logo_email.'.strtolower($request->file('logo_email')->getClientOriginalExtension());

                Storage::put('images/empresa/'.$nameImage, file_get_contents($request->file('logo_email')->getRealPath()));

                $empresa->logo_email = $nameImage;
            }

            // Background login
            if ($request->has('apagar_background')) {
                if (Storage::exists('images/empresa/'.$empresa->apagar_background)) {
                    Storage::delete('images/empresa/'.$empresa->apagar_background);
                }
                $empresa->apagar_background = null;
            }

            if ($request->has('apagar_background_manutencao')) {
                if (Storage::exists('images/empresa/'.$empresa->background_manutencao)) {
                    Storage::delete('images/empresa/'.$empresa->background_manutencao);
                }
                $empresa->background_manutencao = null;
            }

            if ($request->hasFile('background')) {
                if (Storage::exists('images/empresa/'.$empresa->background)) {
                    Storage::delete('images/empresa/'.$empresa->background);
                }

                $nameImage = 'background.'.strtolower($request->file('background')->getClientOriginalExtension());

                Storage::put('images/empresa/'.$nameImage, file_get_contents($request->file('background')->getRealPath()));

                $empresa->background = $nameImage;
            }

            if ($request->hasFile('background_manutencao')) {
                if (Storage::exists('images/empresa/'.$empresa->background_manutencao)) {
                    Storage::delete('images/empresa/'.$empresa->background_manutencao);
                }

                $nameImage = 'background_manutencao.'.strtolower($request->file('background_manutencao')->getClientOriginalExtension());

                Storage::put('images/empresa/'.$nameImage, file_get_contents($request->file('background_manutencao')->getRealPath()));

                $empresa->background_manutencao = $nameImage;
            }

            $empresa->save();

            flash()->success('Empresa editada com sucesso!');

            $request->merge(['user' => Auth::user()->id]);

            Log::info('empresa editado!', $request->except(['_token']));

            return redirect()->route('empresa.edit', $id);
        } catch (ModelNotFoundException $e) {
            Log::error('Erro ao editar empresa!', ['id' => $id, 'message' => $e->getMessage(), 'user' => Auth::user()->id]);

            flash()->error('Desculpe, erro ao editar o Item.');

            return redirect()->route('empresa.edit', $id);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function termo()
    {
        try {
            $empresa = Empresa::findOrFail(1);

            return response()->download(storage_path('app/public/empresa/arquivo/'.$empresa->termo_inicial));
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage(), 'cod_error' => $e->getCode()]);
        }
    }
}
