<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use Image;
use App\Models\Galeria;
use App\Models\GaleriaImagens;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ImagemRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\GaleriaRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GaleriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('default.galerias.index', [
                'title'             => 'Lista de Galerias ',
                'dados'             => Galeria::all()->sortByDesc('id'),
                'dados_desativados' => Galeria::onlyTrashed()->get(),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('default.galerias.create', [
                'title' => 'Cadastro de Galerias ',
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GaleriaRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(GaleriaRequest $request)
    {
        try {
            Galeria::create($request->all());

            flash()->success('Galeria <strong>'.$request->name.'</strong> adicionada com sucesso!');

            return redirect()->route('galeria.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao salvar a galeria');

            return redirect()->route('galeria.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Galeria $galeria
     * @return \Illuminate\Http\Response
     */
    public function show($galeria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Galeria $galeria
     * @return \Illuminate\Http\Response
     */
    public function edit(Galeria $galeria)
    {
        try {
            return view('default.galerias.edit', [
                    'dados' => $galeria,
                    'title' => 'Edição de galeria ',
                ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar a Galeria!');

            return redirect()->route('galeria.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  GaleriaRequest $request
     * @param  Galeria $galeria
     * @return \Illuminate\Http\Response
     */
    public function update(GaleriaRequest $request, Galeria $galeria)
    {
        try {
            $galeria->update($request->all());

            $galeria->save();

            flash()->success('Galeria  '.$request->get('name').' editada com sucesso!');

            return redirect()->route('galeria.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao editar a Galeria.');

            return redirect()->route('galeria.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Galeria $galeria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Galeria $galeria)
    {
        if (Auth::user()->can('master')) {
            try {

                    //deleta as imagens ligadas a galeria
                try {
                    $this->deleteAllImg($galeria);
                } catch (ModelNotFoundException $e) {
                    flash()->error('Erro ao deletar as imagens ligadas a galeria!');

                    return redirect()->route('galeria.index');
                }

                //Procura a associação com outros Model para desfaze-las antes de deletar o registro
                foreach ($galeria->getRelations() as $nomeModel) {
                    if ($galeria->$nomeModel()->count() == 1) {
                        $model = $galeria->$nomeModel()->get()->first();
                        $model->galeria()->dissociate();
                        $model->save();
                        break;
                    }
                }

                $galeria->forceDelete();

                flash()->success('Galeria deletada da base de dados com sucesso!');

                return redirect()->route('galeria.index');
            } catch (ModelNotFoundException $e) {
                flash()->error('Erro ao deletar a galeria da base de dados!');

                return redirect()->route('galeria.index');
            }
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('galeria.index');
        }
    }

    /**
     * Remove the specified resource from storage.(with soft deletes).
     *
     * @param  Galeria $galeria
     * @return \Illuminate\Http\Response
     */
    public function delete(Galeria $galeria)
    {
        try {
            Galeria::destroy($galeria->id);

            flash()->warning(sprintf('Galeria desativada com sucesso. Caso queira reativar a Galeria <a href="%s">clique aqui</a>.', route('galeria.recovery', $galeria->id)));

            return redirect()->route('galeria.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao desativar a galeria.');

            return redirect()->route('galeria.index');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recovery($id)
    {
        try {
            Galeria::onlyTrashed()->findOrFail($id)->restore();

            flash()->success('Galeria ativada com sucesso!');

            return redirect()->route('galeria.index');
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao ativar a Galeria.');

            return redirect()->route('galeria.index');
        }
    }

    /**
     * Carrega as imagens referente a galeria.
     *
     * @param Galeria $galeria
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function imagens(Galeria $galeria)
    {
        try {
            return view('default.galerias.imagens', [
                    'title'   => 'Cadastro Imagens da Galeria'.$galeria->title.' ',
                    'galeria' => $galeria,
                    'imagens' => $galeria->imagens()->orderBy('ordem')->get(),
                ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao carregar as imagens da Galeria.');

            return redirect()->route('galeria.index');
        }
    }

    /**
     * Retorna o template da galeria de imagem.
     *
     * @param GaleriaImagens $imagem
     * @return string
     */
    public function template(GaleriaImagens $imagem)
    {
        try {
            return response()->view('default.galerias.template-galeria', [
                    'imagem' => $imagem,
                ], 200);
        } catch (ModelNotFoundException $e) {
            return 'error';
        }
    }

    /**
     * Faz o upload das imagens.
     *
     * @param Galeria $galeria
     * @param ImagemRequest $request
     * @return mixed
     */
    public function upload(Galeria $galeria, ImagemRequest $request)
    {
        if ($request->file('foto')->isValid()) {
            $message = [
                    'message'     => 'Ocorreu um erro ao salvar a imagem!',
                    'type'        => 'warning',
                    'nome_imagem' => $request->file('foto')->getClientOriginalName(),
                ];

            try {
                DB::beginTransaction();
                Log::info('Entrou para salvar imagem');

                $picture = $request->file('foto');

                $nomeImagem = str_slug(explode('.', $picture->getClientOriginalName())[0], '_');

                $imagem = new GaleriaImagens();

                $imagem->galeria_id = $galeria->id;
                $imagem->name = $nomeImagem.'.'.$picture->getClientOriginalExtension();
                $imagem->caminho = 'galerias/'.$galeria->id.'/';
                $imagem->principal = 0;
                $imagem->extensao = strtolower($picture->getClientOriginalExtension());
                $imagem->ordem = $galeria->imagens()->count() == 0 ? 1 : $galeria->maxOrdem() + 1;

                $imagem->save();

                $path = '/galerias/'.$galeria->id.'/'.$nomeImagem.'.'.$picture->getClientOriginalExtension();

                if (Storage::put($path, file_get_contents($picture->getRealPath()))) {
                    DB::commit();

                    return redirect()->route('galeria.template', $imagem->id);
                }
            } catch (ModelNotFoundException $e) {
                DB::rollBack();
                Log::info('Erro ao salvar');

                return \Response::json('success3', 500);
                //return \Response::json(json_encode($message), 500);
            }
        } else {
            $message = [
                    'message'     => 'Imagens invalida!',
                    'type'        => 'warning',
                    'nome_imagem' => $request->file('foto')->getClientOriginalName(),
                ];

            return \Response::json(json_encode($message), 200);
        }
    }

    /**
     * Atualiza a legenda e o nome do arquivo conforme a legenda.
     *
     * @param GaleriaImagens $imagem
     * @param ImagemRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function legenda(GaleriaImagens $imagem, ImagemRequest $request)
    {
        try {

            /**
             * Renomeia o arquivo utilizando o slug da legenda.
             */
            $newNameImage = str_slug($request->legenda, '_').'.'.pathinfo($imagem->name, PATHINFO_EXTENSION);

            if (Storage::move($imagem->caminho.$imagem->name, $imagem->caminho.$newNameImage)) {
                $imagem->legenda = $request->legenda;
                $imagem->name = $newNameImage;

                $imagem->save();

                return response()->json([
                        'status'    => 'success',
                        'legenda'   => $request->legenda,
                        'url'       => route('imagecache', ['thumb', $imagem->caminho.$imagem->name]),
                        'imagem_id' => $imagem->id,
                        'message'   => 'Legenda salva com sucesso',
                    ], 200);
            }

            return response()->json(['status' => 'danger', 'legenda' => $request->legenda, 'message' => 'Ocorreu um erro ao renomear a imagem'], 500);
        } catch (\League\Flysystem\Exception $e) {
            return response()->json(['status' => 'danger', 'legenda' => $request->legenda, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Muda a imagem principal da galeria.
     *
     * @param GaleriaImagens $imagem
     * @return \Illuminate\Http\JsonResponse
     */
    public function setImagemPrincipal(GaleriaImagens $imagem)
    {
        try {
            GaleriaImagens::where('galeria_id', $imagem->galeria_id)->update(['principal' => 0]);

            $imagem->principal = 1;

            $imagem->save();

            return response()->json(['status' => 'success', 'imagem_id' => $imagem->id, 'message' => 'Imagem principal mudada com sucesso'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'danger', 'imagem_id' => $imagem->id, 'message' => 'Ocorreu um erro ao mudar a imagem principal. Tente novamente, por favor!'], 500);
        }
    }

    public function order(ImagemRequest $request)
    {
        try {
            foreach ($request->image as $key => $imagem_id) {
                GaleriaImagens::where('id', $imagem_id)->update(['ordem' => $key]);
            }

            return response()->json(['status' => 'success', 'message' => 'Imagens ordenadas com sucesso!'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'danger', 'message' => 'Ocorreu um erro ao mudar a imagem principal. Tente novamente, por favor!'], 500);
        }
    }

    /**
     * Deleta imagem.
     *
     * @param GaleriaImagens $imagem
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImg(GaleriaImagens $imagem)
    {
        try {
            if (Storage::exists($imagem->caminho.$imagem->name)) {
                Storage::delete($imagem->caminho.$imagem->name);
            }

            $id = $imagem->id;

            $imagem->delete();

            return response()->json([
                    'status' => 'success',
                    'message' => 'Imagem deletada com sucesso',
                    'id' => $id,
                ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                    'status'  => 'danger',
                    'message' => 'Erro ao deletar arquivo do servidor! Por favor contate o Administrador do sistema.',
                ], 500);
        }
    }

    /**
     * Deleta todas as imagens ligada a galeria.
     *
     * @param Galeria $galeria
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAllImg(Galeria $galeria)
    {
        try {
            $imagens = $galeria->imagens()->get();

            $apagadas = [];
            $erros = [];

            //looping para apagar as imagens
            foreach ($imagens as $imagem) {
                if (json_decode($this->deleteImg($imagem)->content())->status == 'success') {
                    $apagadas[] = $imagem->id;
                    $imagem->delete();
                } else {
                    $erros[] = $imagem->id;
                }
            }

            //se todas as imagens foram apagadas, deleta tbm a pasta
            if (count($erros) == 0) {
                Storage::deleteDirectory('galerias/'.$galeria->id);
            }

            return response()->json(['status' => 'success', 'error' => $erros, 'apagadas' => $apagadas, 'message' => 'Imagens deletadas com sucesso']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['status' => 'danger', 'message' => 'Erro ao deletar imagens!']);
        }
    }

    public function publicar(Galeria $galeria)
    {
        if (Auth::user()->can('master')) {
            $imageOptimizer = new ImageOptimizer();
            try {
                $imagens = $galeria->imagens()->get();
                //pega o tamanho dos cortes
                $tamanhosRecorte = $galeria->galeria_tipo()->with('tamanhos')->first()->getRelation('tamanhos');

                //looping para apagar as imagens
                foreach ($imagens as $imagem) {
                    $novoNome = explode('.', $imagem->name)[0];
                    $extensao = pathinfo($imagem->name, PATHINFO_EXTENSION);
                    if ($extensao) {
                        $path = storage_path('images/'.$imagem->caminho.$imagem->name);
                        if (Storage::exists('images/'.$imagem->caminho.$imagem->name)) {
                            foreach ($tamanhosRecorte as $recorte) {
                                try {
                                    $img = Image::make($path);
                                    if ($recorte->altura == 0) {
                                        $img->widen($recorte->largura);
                                    } elseif ($recorte->crop == 1) {
                                        $img->fit($recorte->largura, $recorte->altura);
                                    } elseif ($recorte->crop == 0) {
                                        $img->resize($recorte->largura, $recorte->altura);
                                    }

                                    $path2 = 'galerias/'.$galeria->id.'/'.$novoNome.$recorte->sufixo.'.'.$extensao;
                                    Storage::put($path2, $img->stream()->__toString());
                                    $imageOptimizer->optimizeImage($path);
                                } catch (Exception $e) {
                                    echo $e->getMessage();
                                }
                            }
                            $img = Image::make(storage_path('images/'.$imagem->caminho.$imagem->name));
                            $img->fit(234, 180);
                            Storage::put('galerias/'.$galeria->id.'/'.$novoNome.'_visualiza.'.$extensao, $img->stream()->__toString());
                            $imageOptimizer->optimizeImage('galerias/'.$galeria->id.'/'.$novoNome.'_visualiza.'.$extensao);

                            $imagem->caminho = 'galerias/'.$galeria->id.'/';
                            $imagem->name = $novoNome;
                            $imagem->extensao = $extensao;
                            $imagem->save();
                        }
                    }
                }

                return redirect()->route('galeria.imagens', $galeria);
            } catch (ModelNotFoundException $e) {
                return redirect()->route('galeria.imagens', $galeria);
            }
        } else {
            flash()->error('Você não tem privilégios suficientes para esta operação!');

            return redirect()->route('galeria.imagens', $galeria);
        }
    }

    public function publicarAll()
    {
        try {
            $galerias = Galeria::with('imagens')->has('imagens')->get();
            foreach ($galerias as $galeria) {
                $this->publicar($galeria);
            }

            return redirect()->route('galeria.index');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('galeria.index');
        }
    }
}
