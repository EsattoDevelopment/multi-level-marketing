<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/user/indicador',
        '/cep/*',
        'download',
        'download/*',
        '/download/*/*.*',
        'galeria/*/upload',
        'galeria/template',
        'galeria/imagens/*/legenda',
        'galeria/imagens/*/delete',
        'galeria/imagens/*/principal',
        'galeria/*/delete-all',
        'galeria/order',
        'pagseguro/notificacao',
        'api/*/*',
    ];

    public function handle($request, Closure $next)
    {
        if ($this->isReading($request) || $this->shouldPassThrough($request) || $this->tokensMatch($request)) {
            return $this->addCookieToResponse($request, $next($request));
        }

        return redirect()->back();
        //throw new TokenMismatchException;
    }
}
