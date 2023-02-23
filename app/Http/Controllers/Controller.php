<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $viewNamespace = '';

    protected function view($view = null, $data = [], $mergeData = [])
    {
        if (! empty($this->viewNamespace) && ! str_contains($view, '::')) {
            $view = $this->viewNamespace.$view;
        }

        return view($view, $data, $mergeData);
    }
}
