<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use App\Models\Videos;
use App\Models\Titulos;
use App\Models\VideosTitulos;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\VideosRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VideosController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:master|admin', [
            'only' => [
                'index',
                'create',
                'edit',
                'store',
                'delete',
            ],
        ]);

        $this->middleware('permission:master', [
            'only' => [
                'destroy',
            ],
        ]);
    }

    public function show($categoriaId)
    {
        if (array_key_exists($categoriaId, config('constants.videos_categorias'))) {
            return view('default.videos.show', [
                'title' => 'Todos vídeos #'.config('constants.videos_categorias')[$categoriaId],
                'dados' => Videos::whereStatus(1)->where('categoria', $categoriaId)->whereHas('videosTitulos', function ($query) {
                    $query->where('titulo_id', '=', Auth::user()->titulo->id);
                })->orderBy('created_at', 'desc')->paginate(4),
                'categoria' => config('constants.videos_categorias')[$categoriaId],
            ]);
        }

        return redirect()->back();
    }

    public function index()
    {
        return view('default.videos.index', [
            'title' => 'Todos vídeos',
            'dados' => Videos::get()->sortByDesc('created_at'),
            'dados_desativados' => Videos::onlyTrashed()->get()->sortByDesc('created_at'),
        ]);
    }

    public function create()
    {
        return view('default.videos.create', [
            'title' => 'Cadastro de vídeos',
            'titulos' => Titulos::all()->sortBy('name'),
        ]);
    }

    public function edit($id)
    {
        $video = Videos::with('videosTitulos')->where('id', $id)->firstOrFail();

        if ($video->tipo == 1) {
            $url = 'https://www.youtube.com/watch?v=';
        } elseif ($video->tipo == 2) {
            $url = 'https://vimeo.com/';
        } else {
            $url = '';
        }

        if ($video) {
            try {
                return view('default.videos.edit', [
                    'title' => 'Vídeo #'.$id,
                    'video' => $video,
                    'titulos' => Titulos::all()->sortBy('name'),
                    'url' => $url,
                    'permissoes' => $video->getRelation('videosTitulos')->lists('titulo_id')->toArray(),
                ]);
            } catch (ModelNotFoundException $e) {
            }
        }

        flash()->error('Desculpe, o registro não pode ser carregado!');

        return redirect()->back();
    }

    public function store(VideosRequest $request)
    {
        try {
            $codigo = preg_replace('/^.*?v=(.+)$|^.*\/(.+)$/', '$1$2', $request->codigo);

            if (preg_match('/youtube|youtu/i', $request->codigo)) {
                $tipo = 1;
                $capa = "https://img.youtube.com/vi/$codigo/0.jpg";
            } elseif (preg_match('/vimeo/i', $request->codigo)) {
                $tipo = 2;
                $capa = $this->getImageVideoIdVimeo($codigo);
            } else {
                $tipo = 0;
                $capa = '';
                $codigo = $request->codigo;
            }

            $video = [];

            $video['nome'] = $request->nome;
            $video['descricao'] = $request->descricao;
            $video['codigo'] = $codigo;
            $video['capa'] = $capa;
            $video['tipo'] = $tipo;
            $video['categoria'] = $request->categoria;
            $video['status'] = $request->status;

            DB::beginTransaction();

            $videoSalvo = Videos::create($video);

            foreach ($request->titulosPermissoes as $titulo_id => $titulo) {
                if ($titulo['exibir'] == 1) {
                    VideosTitulos::create(['video_id' => $videoSalvo->id, 'titulo_id' => $titulo_id]);
                }
            }

            DB::commit();

            flash()->success('Vídeo cadastrado com sucesso!');

            Log::info('Vídeo cadastrado', $request->except('_token'));

            return redirect()->route('videos.index', Auth::user());
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            flash()->error('Desculpe, erro ao realizar o cadastro do vídeo. Tente novamente, se o erro persistir entre em contato conosco!');

            Log::info('Erro ao cadastrar vídeo', ['usuario:' => Auth::user()->id, 'video' => $videoSalvo->id]);

            return redirect()->back();
        }
    }

    public function update(VideosRequest $request, $video_id)
    {
        $codigo = preg_replace('/^.*?v=(.+)$|^.*\/(.+)$/', '$1$2', $request->codigo);

        if (preg_match('/youtube|youtu/i', $request->codigo)) {
            $tipo = 1;
            $capa = "https://img.youtube.com/vi/$codigo/0.jpg";
        } elseif (preg_match('/vimeo/i', $request->codigo)) {
            $tipo = 2;
            $capa = $this->getImageVideoIdVimeo($codigo);
        } else {
            $tipo = 0;
            $capa = '';
            $codigo = $request->codigo;
        }
        $videoAtual = Videos::findOrFail($video_id);

        $video = [];

        $video['nome'] = $request->nome;
        $video['descricao'] = $request->descricao;
        $video['codigo'] = $codigo;
        $video['capa'] = $capa;
        $video['tipo'] = $tipo;
        $video['categoria'] = $request->categoria;
        $video['status'] = $request->status;

        try {
            DB::beginTransaction();

            $videoAtual->update($video);

            $videoTitulosAtual = VideosTitulos::whereVideoId($videoAtual->id);

            if ($videoTitulosAtual) {
                $videoTitulosAtual->delete();
            }

            if ($request->titulosPermissoes) {
                foreach ($request->titulosPermissoes as $titulo_id => $titulo) {
                    if ($titulo['exibir'] == 1) {
                        VideosTitulos::create(['video_id' => $videoAtual->id, 'titulo_id' => $titulo_id]);
                    }
                }
            }

            DB::commit();

            flash()->success('Video atualizado com sucesso!');

            Log::info('Vídeo atualização', $request->except('_token'));

            return redirect()->route('videos.index', Auth::user());
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return redirect()->back();
        }
    }

    public function delete($id)
    {
        $videoAtual = Videos::findOrFail($id);

        $videoAtual->delete();

        flash()->success('Video desativado com sucesso!');

        Log::info('Vídeo desativado', ['usuario:' => Auth::user()->id, 'video' => $id]);

        return redirect()->route('videos.index');
    }

    public function recovery($id)
    {
        $videoAtual = Videos::onlyTrashed()->findOrFail($id);

        $videoAtual->restore();

        flash()->success('Video ativado com sucesso!');

        Log::info('Vídeo ativado', ['usuario:' => Auth::user()->id, 'video' => $id]);

        return redirect()->route('videos.index');
    }

    public function destroy($id)
    {
        $videoAtual = Videos::onlyTrashed()->findOrFail($id);

        $videoAtual->forceDelete();

        flash()->success('Video deletado com sucesso!');

        Log::info('Vídeo deletado', ['usuario:' => Auth::user()->id, 'video' => $id]);

        return redirect()->route('videos.index');
    }

    private function getImageVideoIdVimeo($videoId)
    {
        $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$videoId.php"));

        return $hash[0]['thumbnail_medium'];
    }
}
