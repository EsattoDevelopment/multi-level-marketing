<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Middleware;

use Closure;
use App\Models\Sistema;
use App\Models\EnderecosUsuarios;

class CadastroOK
{
    private $sistema;

    public function __construct()
    {
        $this->sistema = Sistema::findOrFail(1);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $cpfOk = true;
        $cpfEtapa = null;
        $enderecoOk = true;

        if ((strpos($request->getPathInfo(), '/dados-usuario') === false) && (strpos($request->getPathInfo(), '/images/') === false) && (strpos($request->getPathInfo(), '/cep') === false) && ! $request->user()->hasRole('master') && ! session()->has('hasClonedUser')) {
            $endereco = EnderecosUsuarios::select('id')->whereUserId($request->user()->id)->first();

            if ($this->sistema->campo_cpf) {
//                if ($request->user()->status_cpf != 'validado') {
//                    $cpfOk = false;
//                }
            }

            if ($this->sistema->endereco_obrigatorio) {
                if (! $endereco) {
                    $enderecoOk = false;
                }
            }

            // se não tem dados minimo redireciona para preenchimento
            if (! $request->user()->name || ! $request->user()->email || ! $request->user()->celular) {
                flash()->warning('Por favor prossiga com o termino do cadastro!');

                return redirect()->route('dados-usuario.pessoais');
            }

            // se não tem endereço redireciona para preenchimento
            if (! $enderecoOk) {
                flash()->warning('Por favor prossiga com o termino do seu endereço!');

                return redirect()->route('dados-usuario.endereco');
            }

            if (! $cpfOk) {
                $mensagem = 'Por favor prossiga com o termino do cadastro!';

//                if (! $cpfOk) {
//                    switch ($request->user()->status_cpf) {
//                        case null:
//                            $mensagem = 'Envio de Documentos pendente!';
//                            break;
//                        case 'em_analise':
//                            $mensagem = 'Documento em analise!';
//                            break;
//                    }
//                }

                flash()->warning($mensagem);

                return redirect()->route('dados-usuario.pessoais');
            }
        }

        return $next($request);
    }
}
