<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\ViewComposers;

use App\Models\DownloadTipo;
use Illuminate\Contracts\View\View;

class ViewDownload
{
    /**
     * Create a new profile composer.
     */
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $tipos = new DownloadTipo();
        if (! \Auth::user()->titulo->habilita_rede) {
            $tipos = $tipos->where('habilita_rede', 0);
        }

        $view->with('downloads', $tipos->get());
    }
}
