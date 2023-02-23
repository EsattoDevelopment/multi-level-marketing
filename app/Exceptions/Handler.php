<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Exceptions;

use Log;
use Exception;
use App\Models\User;
use App\Notifications\ErroSistema;
use App\Notifications\ErrorUsuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
            HttpException::class,
            //ModelNotFoundException::class,
        ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldReport($e)) {
            try {
                Notification::send(User::findOrFail(1), new ErroSistema($e));
            } catch (Exception $e) {
            }
        }

        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof HttpException) {
            if ($e->getStatusCode() != 404 && $e->getStatusCode() != 403) {
                self::notificarCliente($e, $request);
            }

            if ($e->getStatusCode() == 403) {
                Log::warning('Acesso negado a página: '.$request->getRequestUri().' ao id:'.Auth::user()->id);
                flash()->error('Você não tem permissões suficiente para realizar esta ação!');

                return redirect()->route('home')->with(['mostrar_erro' => true]);
            }
        }

        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        return parent::render($request, $e);
    }

    private function notificarCliente(Exception $e, $request)
    {
        $exception = [
                'mensagem' => $e->getMessage(),
                'arquivo' => $e->getFile(),
                'linha' => $e->getLine(),
                'status' => $e->getCode(),
                'text' => $e->getTraceAsString(),
                'url' => $request->getRequestUri() ?? '',
                'user' => Auth::user() ? '#'.Auth::user()->id.' - '.Auth::user()->name : null,
                'status_code' => $e->getStatusCode(),
            ];

        // só envia notificação se o erro for diferente de 404 e 403
        if (Auth::user()) {
            Notification::send(Auth::user(), new ErrorUsuario($exception));
        }
    }
}
