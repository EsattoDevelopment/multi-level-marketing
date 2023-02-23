<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Log;
use App\Models\Cidade;
use App\Models\Estados;
use App\Models\Galeria;
use App\Models\Pacotes;
use Illuminate\Http\Request;
use App\Models\TipoAcomodacao;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PacotesRequest;

class PacotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($pacotes, array $textos)
    {
        return view('default.pacotes.index', [
                'title'               => 'Usar GMilhas ',
                'pacotes'             => $pacotes->where('status', 1),
                'pacotes_desativados' => $pacotes->where('status', 0),
                'textos'              => $textos,
            ]);
    }

    public function hospedagemIndex()
    {
        $pacotes = Pacotes::with('acomodacao')->where('tipo_pacote_id', 1)->get();
        $textos = [
                'titulo' => 'hospedagens',
                'todos'  => 'Todas',
                'url'    => route('pacotes.hospedagem.create'),
            ];

        return $this->index($pacotes, $textos);
    }

    public function cruzeiroIndex()
    {
        $pacotes = Pacotes::with('acomodacao')->where('tipo_pacote_id', 3)->get();
        $textos = [
                'titulo' => 'cruzeiros',
                'todos'  => 'Todas',
                'url'    => route('pacotes.cruzeiro.create'),
            ];

        return $this->index($pacotes, $textos);
    }

    public function pacoteIndex()
    {
        $pacotes = Pacotes::with('acomodacao')->where('tipo_pacote_id', 2)->get();
        $textos = [
                'titulo' => 'pacotes',
                'todos'  => 'Todas',
                'url'    => route('pacotes.pacote.create'),
            ];

        return $this->index($pacotes, $textos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function hospedagemCreate()
    {
        $tipo = 1;

        return $this->create($tipo, route('pacotes.hospedagem.index'));
    }

    public function pacoteCreate()
    {
        $tipo = 2;

        return $this->create($tipo, route('pacotes.pacote.index'));
    }

    public function cruzeiroCreate()
    {
        $tipo = 3;

        return $this->create($tipo, route('pacotes.cruzeiro.index'));
    }

    public function create($tipo, $url)
    {
        return view('default.pacotes.create', [
                'title'           => 'Cadastro Uso GMilhas',
                'estados'         => Estados::all(),
                'tipo'            => $tipo,
                'urlVolta'        => $url,
                'tipo_acomodacao' => TipoAcomodacao::all(),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PacotesRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PacotesRequest $request)
    {
        try {
            DB::beginTransaction();

            $pacotes = Pacotes::create($request->all());

            foreach ($request->get('acomodacao') as $key => $value) {
                $pacotes->acomodacao()->attach($key, ['valor' => $value['valor']]);
            }

            DB::commit();

            flash()->success('Pacote cadastrado com sucesso!');

            if ($request->get('botao') == 'galeria') {
                return redirect()->route('pacotes.galeria.create', $pacotes->id);
            }

            Log::info('Pacote cadastrado', $request->except('_token'));

            if ($request->get('tipo_pacote_id') == 1) {
                return redirect()->route('pacotes.hospedagem.index');
            } elseif ($request->get('tipo_pacote_id') == 2) {
                return redirect()->route('pacotes.pacote.index');
            } elseif ($request->get('tipo_pacote_id') == 3) {
                return redirect()->route('pacotes.cruzeiro.index');
            }
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            flash()->error('Desculpe, erro ao cadastrar o pacote. Tente novamente, se o erro persistir entre em contato conosco!');

            Log::info('Erro ao cadastrar pacotes', ['user' => Auth::user()->id]);

            return redirect()->route('pacotes.create');
        }
    }

    /**
     * Acessa a galeria da News.
     *
     * @param int $pacote_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function galeria($pacote_id)
    {
        try {
            $pacotes = Pacotes::findOrFail($pacote_id);

            return redirect()->route('galeria.imagens', [$pacotes->galeria_id]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao acessar a galeria da noticia!');

            return redirect()->route('pacotes.hospedagem.index');
        }
    }

    /**
     * Cria uma galeria com dos dados da News.
     *
     * @param int $pacote_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function galeriaCreate($pacote_id)
    {
        try {
            $pacote = Pacotes::findOrFail($pacote_id);

            $dadosGaleria = [
                    'title'       => $pacote->chamada,
                    'description' => 'Galeria da Pacotes: '.$pacote->chamada,
                ];

            $galeria = Galeria::create($dadosGaleria);

            $pacote->galeria_id = $galeria->id;

            $pacote->save();

            return redirect()->route('galeria.imagens', [$galeria->id]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, erro ao criar a Galeria!');

            return redirect()->route('pacotes.hospedagem.index');
        }
    }

    public function getCidades($estado)
    {
        return \Response::json(Cidade::where('estado', $estado)->get(), 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function hospedagemEdit($id, $tipo)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $pacotes = Pacotes::with('cidade', 'acomodacao')->findOrFail($id);

            switch ($pacotes->tipo_pacote_id) {
                    case 1:
                        $url = route('pacotes.hospedagem.index');
                        break;
                    case 2:
                        $url = route('pacotes.hospedagem.index');
                        break;
                    case 3:
                        $url = route('pacotes.hospedagem.index');
                        break;
                }

            return view('default.pacotes.edit', [
                    'dados'           => $pacotes,
                    'title'           => $pacotes->nome.' - Edição de pacote Admin',
                    'estados'         => Estados::all(),
                    'urlVolta'        => $url,
                    'tipo_acomodacao' => TipoAcomodacao::all(),
                ]);
        } catch (ModelNotFoundException $e) {
            flash()->error('Desculpe, ocorreu um erro ao buscar a pacote!');

            return redirect()->route('pacotes.hospedagem.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PacotesRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PacotesRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $pacotes = Pacotes::findOrFail($id);

            $pacotes->update($request->all());

            if ($request->has('acomodacao')) {
                $pacotes->acomodacao()->detach();

                foreach ($request->get('acomodacao') as $key => $value) {
                    $pacotes->acomodacao()->attach($key, ['valor' => $value['valor']]);
                }
            } else {
                $pacotes->acomodacao()->detach();
            }

            DB::commit();

            flash()->success('Pacote atualizado com sucesso!');

            Log::info('Pacote atualizado', $request->except('_token'));

            if ($pacotes->tipo_pacote_id == 1) {
                return redirect()->route('pacotes.hospedagem.index');
            } elseif ($pacotes->tipo_pacote_id == 2) {
                return redirect()->route('pacotes.pacote.index');
            } elseif ($pacotes->tipo_pacote_id == 3) {
                return redirect()->route('pacotes.cruzeiro.index');
            }
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            flash()->error('Desculpe, erro ao realizar a atualização do pacote. Tente novamente, se o erro persistir entre em contato conosco!');

            Log::info('Erro ao cadastrar pacotes', ['user' => Auth::user()->id]);

            return redirect()->route('pacotes.create');
        }
    }
}
