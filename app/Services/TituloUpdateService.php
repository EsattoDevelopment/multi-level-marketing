<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\User;
use App\Models\Titulos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\UsersTitulosHitorico;
use Illuminate\Support\Facades\Auth;

class TituloUpdateService
{
    private $titulosParaUpdate;

    public function getTitulosParaUpdate()
    {
        return $this->titulosParaUpdate;
    }

    public function __construct()
    {
        $this->titulosParaUpdate = collect();
    }

    public function subirTitulos()
    {
        DB::beginTransaction();
        try {
            $this->sobeTitulos(true);
            DB::commit();
            Log::info('Update de titulos efetuado com sucesso!');

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Erro ao subir os titulos {$e}");

            return false;
        }
    }

    public function titulosUpdate()
    {
        $this->verificarTitulos(true);

        return $this->titulosParaUpdate;
    }

    private function verificarTitulos($verificar)
    {
        if ($verificar) {
            $verificar = false;
            $usuarios = User::with('pontosPessoais')->where('status', 1)
                ->where('titulo_id', '<>', 1)->get();

            foreach ($usuarios as $user) {
                try {
                    $titulosUpdateRequisitos = [];
                    $pontosPessoais = 0;
                    $pontosEquipe = 0;
                    $linha = '<b>Id: '.$user->id." - {$user->name} ({$user->titulo->name})</b><br>";
                    $linha .= '<b>&nbsp;&nbsp;Dados Atuais</b>';
                    if ($user->extratoPessoais() != null) {
                        $pontosPessoais = $user->extratoPessoais()->saldo;
                    }

                    if ($user->pontosEquipe() != null) {
                        $pontosEquipe = $user->pontosEquipe()->saldo;
                    }

                    $linha .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;Diretos Ativos: '.$user->diretosAprovados()->count();
                    $linha .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;GMilhas Pessoais atual: '.$pontosPessoais;
                    $linha .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;GMilhas Equipe atual: '.$pontosEquipe;

                    if ($user->titulo->tituloSuperior != null) {
                        $titulosuperior = $user->titulo->tituloSuperior;

                        $linha .= "<br>&nbsp;&nbsp;<b>Título Superior:</b> {$titulosuperior->name}";
                        $linha .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;Mim Diretos: '.$titulosuperior->min_diretos_aprovados;
                        $linha .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;GMilhas Pessoais: '.$titulosuperior->pontos_pessoais_update;
                        $linha .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;GMilhas Equipe: '.$titulosuperior->pontos_equipe_update;

                        $sobeTitulo = false;
                        $titulosUpdateOk = true;

                        if ($titulosuperior->titulos_update != null && $titulosuperior->titulos_update != '') {
                            foreach ($titulosuperior->titulos_update as $chave => $valor) {
                                //verifico se atinge as condições para subir o titulo
                                $diretos = User::where('indicador_id', $user->id)->where('titulo_id', (int) $chave)->where('status', 1)->count();
                                $t = Titulos::where('id', (int) $chave)->first();
                                $linha .= "<br>&nbsp;&nbsp;&nbsp;&nbsp;Qtde min titulo {$chave} ({$t->name}): {$valor}&nbsp;&nbsp;Qtde Atual: {$diretos}";

                                $titulosUpdateRequisitos[$t->name] = [
                                    'minimo' => (int) $valor,
                                    'atual' => $diretos,
                                ];

                                if ($diretos < (int) $valor) {
                                    $titulosUpdateOk = false;
                                    //break;
                                }
                            }
                        }

                        if ($user->diretosAprovados()->count() >= $titulosuperior->min_diretos_aprovados && $pontosPessoais >= $titulosuperior->pontos_pessoais_update and $pontosEquipe >= $titulosuperior->pontos_equipe_update and $titulosUpdateOk) {
                            $sobeTitulo = true;
                            $verificar = true;
                            $linha .= '<br>&nbsp;&nbsp;<b>Sobe Titulo</b>: Sim';

                            $this->titulosParaUpdate->push([
                                'id' => $user->id,
                                'name' => $user->name,
                                'sobe_titulo' => 'Sim',
                                'titulo_atual_id' => $user->titulo->id,
                                'titulo_atual_name' => $user->titulo->name,
                                'titulo_update_id' => $titulosuperior->id,
                                'titulo_update_name' => $titulosuperior->name,
                                'diretos_aprovados' => $user->diretosAprovados()->count(),
                                'ponto_pessoal_atual' => $pontosPessoais,
                                'ponto_equipe_atual' => $pontosEquipe,
                                'diretos_aprovados_update' => $titulosuperior->min_diretos_aprovados,
                                'ponto_pessoal_update' => $titulosuperior->pontos_pessoais_update,
                                'ponto_equipe_update' => $titulosuperior->pontos_equipe_update,
                                'titulos_update' => $titulosUpdateRequisitos,

                            ]);
                            /*UsersTitulosHitorico::create([
                                'user_id' => $user->id,
                                'titulo_atual_id' => $titulosuperior->id,
                                'titulo_antigo_id' => $user->titulo_id,
                                'responsavel_id' => Auth::user() ? Auth::user()->id : 1,
                                'historico' => $linha,
                            ]);

                            Log::info("Subir titulo {$user->name} titulo_id: {$user->titulo_id} para {$titulosuperior->id}");
                            $user->update(['titulo_id' => $titulosuperior->id]);*/
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Processamento interrompido devido o erro abaixo:');
                    Log::error("Erro ao verificar o update de titulos usúario {$user->id} {$user->name}: {$e}");
                    $this->titulosParaUpdate = collect();
                    /*$this->verificarTitulos(false);*/
                    return false;
                }
            }
            /*$this->verificarTitulos($verificar);*/
        }
    }

    private function sobeTitulos($verificar)
    {
        if ($verificar) {
            //$verificar = false;
            $usuarios = User::with('pontosPessoais')->where('status', 1)
                ->where('titulo_id', '<>', 1)->get();

            foreach ($usuarios as $user) {
                $pontosPessoais = 0;
                $pontosEquipe = 0;
                $linha = '<b>Id: '.$user->id." - {$user->name} ({$user->titulo->name})</b><br>";
                $linha .= '<b>&nbsp;&nbsp;Dados Atuais</b>';
                if ($user->extratoPessoais() != null) {
                    $pontosPessoais = $user->extratoPessoais()->saldo;
                }

                if ($user->pontosEquipe() != null) {
                    $pontosEquipe = $user->pontosEquipe()->saldo;
                }

                $linha .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;Diretos Ativos: '.$user->diretosAprovados()->count();
                $linha .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;GMilhas Pessoais atual: '.$pontosPessoais;
                $linha .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;GMilhas Equipe atual: '.$pontosEquipe;

                if ($user->titulo->tituloSuperior != null) {
                    $titulosuperior = $user->titulo->tituloSuperior;

                    $linha .= "<br>&nbsp;&nbsp;<b>Título Superior:</b> {$titulosuperior->name}";
                    $linha .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;Mim Diretos: '.$titulosuperior->min_diretos_aprovados;
                    $linha .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;GMilhas Pessoais: '.$titulosuperior->pontos_pessoais_update;
                    $linha .= '<br>&nbsp;&nbsp;&nbsp;&nbsp;GMilhas Equipe: '.$titulosuperior->pontos_equipe_update;

                    //$sobeTitulo = false;
                    $titulosUpdateOk = true;

                    if ($titulosuperior->titulos_update != null && $titulosuperior->titulos_update != '') {
                        foreach ($titulosuperior->titulos_update as $chave => $valor) {
                            //verifico se atinge as condições para subir o titulo
                            $diretos = User::where('indicador_id', $user->id)->where('titulo_id', (int) $chave)->where('status', 1)->count();
                            $t = Titulos::where('id', (int) $chave)->first();
                            $linha .= "<br>&nbsp;&nbsp;&nbsp;&nbsp;Qtde min titulo {$chave} ({$t->name}): {$valor}&nbsp;&nbsp;Qtde Atual: {$diretos}";
                            if ($diretos < $valor) {
                                $titulosUpdateOk = false;
                                break;
                            }
                        }
                    }

                    if ($user->diretosAprovados()->count() >= $titulosuperior->min_diretos_aprovados && $pontosPessoais >= $titulosuperior->pontos_pessoais_update and $pontosEquipe >= $titulosuperior->pontos_equipe_update and $titulosUpdateOk) {
                        //$sobeTitulo = true;
                        //$verificar = true;
                        $linha .= '<br>&nbsp;&nbsp;<b>Sobe Titulo</b>: Sim';

                        UsersTitulosHitorico::create([
                            'user_id' => $user->id,
                            'titulo_atual_id' => $titulosuperior->id,
                            'titulo_antigo_id' => $user->titulo_id,
                            'responsavel_id' => Auth::user() ? Auth::user()->id : 1,
                            'historico' => $linha,
                        ]);

                        Log::info("Subir titulo {$user->name} titulo_id: {$user->titulo_id} para {$titulosuperior->id}");
                        $user->update(['titulo_id' => $titulosuperior->id]);
                    }
                }
            }
            /*$this->sobeTitulos($verificar);*/
        }
    }
}
