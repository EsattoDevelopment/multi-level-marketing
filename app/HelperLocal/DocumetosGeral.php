<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use App\Models\DocumentosRecusados;

function motivoDocumentoRecusadoUser($user_id, $path_documento)
{
    $documento = DocumentosRecusados::where('user_id', $user_id)
        ->where('path_documento', $path_documento)
        ->orderBy('id', 'desc')->first();

    if ($documento != null) {
        return $documento->motivo_recusa;
    } else {
        return '';
    }
}

function motivoDocumentoRecusadoResp($user_id, $responsavel_id, $path_documento)
{
    $documento = DocumentosRecusados::where('user_id', $user_id)
        ->where('responsavel_id', $responsavel_id)
        ->where('path_documento', $path_documento)
        ->orderBy('id', 'desc')->first();

    if ($documento != null) {
        return $documento->motivo_recusa;
    } else {
        return '';
    }
}

function motivoDocumentoRecusadoBanco($user_id, $banco_id, $path_documento)
{
    $documento = DocumentosRecusados::where('user_id', $user_id)
        ->where('banco_id', $banco_id)
        ->where('path_documento', $path_documento)
        ->orderBy('id', 'desc')->first();

    if ($documento != null) {
        return $documento->motivo_recusa;
    } else {
        return '';
    }
}

function getExtensaoDocumento($path_documento)
{
    try {
        $dados = explode('.', $path_documento);

        return strtolower(end($dados));
    } catch (Exception $e) {
        return 'indefinido';
    }
}
