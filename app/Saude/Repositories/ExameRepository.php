<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Repositories;

use App\Saude\Domains\Exame;

class ExameRepository
{
    public function getExame($id, array $relations = [])
    {
        if (count($relations) > 0) {
            return Exame::with($relations)->findOrFail($id);
        } else {
            return Exame::findOrFail($id);
        }
    }

    public function getAll(array $relations = [], array $fields = ['*'])
    {
        $exame = new Exame();

        if (count($relations) > 0) {
            $exame->with($relations);
        }

        return $exame->all($fields);
    }

    public function fillDatatables(array $fields = [])
    {
        return Exame::select($fields);
    }

    public function create(array $request = [])
    {
        if (count($request) > 0) {
            return Exame::create($request);
        } else {
            return false;
        }
    }

    public function destroy($id, $force = false)
    {
        if ($force) {
            return Exame::withTrashed()->findOrFail($id)->forceDelete();
        } else {
            return Exame::destroy($id);
        }
    }

    public function recovery($id)
    {
        return Exame::onlyTrashed()->findOrFail($id)->restore();
    }

    public function update($request, $id)
    {
        return Exame::findOrFail($id)->update($request);
    }
}
