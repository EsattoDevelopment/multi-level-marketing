<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Listeners\RodarSistema;

use Log;
use App\Models\Titulos;
use App\Events\RodarSistema;
use App\Models\UpgradeTitulo;

class SubirTitulo
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RodarSistema  $event
     * @return void
     */
    public function handle(RodarSistema $event)
    {
        $usuarios = $event->getObjUsuario()->whereNotIn('status', [0])->where('username', '<>', 'admin')->get();
        Log::info('Entrou subida de titulo');

        //TODO percorre usuarios
        foreach ($usuarios as $usuario) {
            Log::info('Usuario: '.$usuario->id);
            $titulo = $usuario->titulo()->first();

            //TODO veirifca se há titulo superior
            if ($titulo->titulo_superior) {
                $tituloSuperior = $titulo->tituloSuperior;

                $diretosAprovados = $usuario->diretos()->get()->count();

                $extratoBinario = $usuario->extratoBinario()->last();
                if ($extratoBinario) {
                    //TODO verifica qual a menor perna
                    if ($extratoBinario->acumulado_esquerda < $extratoBinario->acumulado_direita) {
                        $binarioPernaMenor = $extratoBinario->acumulado_esquerda;
                    } else {
                        $binarioPernaMenor = $extratoBinario->acumulado_direita;
                    }

                    //TODO verifica se esta aprovado
                    if (($diretosAprovados >= $tituloSuperior->min_diretos_aprovados_binario_perna) && ($binarioPernaMenor >= $tituloSuperior->min_pontuacao_perna_menor)) {
                        Log::info('Diretos necessarios: '.$tituloSuperior->min_diretos_aprovados_binario_perna);
                        Log::info('Tem quantos diretos: '.$diretosAprovados);
                        Log::info('Pontos perna menor necessaria: '.$tituloSuperior->min_pontuacao_perna_menor);
                        Log::info('Tem quantos pontos: '.$binarioPernaMenor);
                        //TODO titulo maximo
                        if ($tituloSuperior->titulo_superior) {
                            $subirPara = $this->verificaTitulosuperior($tituloSuperior, $diretosAprovados, $binarioPernaMenor);
                        } else {
                            Log::info('Não atende aos requisitos para subir de titulo');
                        }

                        /*
                         * Metodos para subir de titulo
                         */
                        UpgradeTitulo::create(['user_id' =>$usuario->id, 'titulo_id' =>$titulo->id]);
                        $usuario->titulo_id = $subirPara->id;

                        $usuario->save();
                        Log::notice('Subiu do titulo: '.$titulo->name);
                        Log::notice('para: '.$subirPara->name);
                        Log::info('Usuario '.$usuario->name.' subiu de titulo', $usuario->toArray());
                    } else {
                        Log::info('Não atende aos requisitos para subir de titulo');
                    }
                } else {
                    Log::info('Não tem extrato binario');
                }
            } else {
                Log::info('Não há titulo superior ao do usuário');
            }

            Log::info('saiu subida de titulo');

            return true;
        }

        Log::info('saiu subida de titulo');

        return true;
    }

    /**
     * @param Titulos $titulo
     * @param $diretosAprovados
     * @param $binarioPernaMenor
     * @return Titulos
     */
    private function verificaTitulosuperior(Titulos $titulo, $diretosAprovados, $binarioPernaMenor)
    {
        Log::info('***********************************************');
        Log::info('Verifica titulo superior');
        $tituloSuperior = $titulo->tituloSuperior;

        if (($diretosAprovados >= $tituloSuperior->min_diretos_aprovados_binario_perna) && ($binarioPernaMenor >= $tituloSuperior->min_pontuacao_perna_menor)) {
            Log::info('Diretos necessarios: '.$tituloSuperior->min_diretos_aprovados_binario_perna);

            Log::info('Tem quantos diretos: '.$diretosAprovados);
            Log::info('Pontos perna menor necessaria: '.$tituloSuperior->min_pontuacao_perna_menor);
            Log::info('Tem quantos pontos: '.$binarioPernaMenor);
            if ($tituloSuperior->titulo_superior) {
                return $this->verificaTitulosuperior($tituloSuperior, $diretosAprovados, $binarioPernaMenor);
            } else {
                Log::info('Verificado ***************');

                return $tituloSuperior;
            }
        } else {
            Log::info('Verificado ***************');

            return $titulo;
        }
    }
}
