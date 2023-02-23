<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Http\ViewComposers;

use App\Models\Modal;
use Illuminate\Contracts\View\View;

/**
 * Class ModalViews.
 */
class ModalViews
{
    /**
     * ModalViews constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('modais', Modal::all());
    }
}
