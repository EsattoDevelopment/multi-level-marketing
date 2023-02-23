<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Filters\Image;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class Favicon implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->widen(32);
    }
}
