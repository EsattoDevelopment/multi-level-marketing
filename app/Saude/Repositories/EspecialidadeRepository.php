<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Repositories;

use App\Saude\Domains\Especialidade;

class EspecialidadeRepository
{
    public function getEspecialidade($id, array $relations = [])
    {
        if (count($relations) > 0) {
            return Especialidade::with($relations)->findOrFail($id);
        } else {
            return Especialidade::findOrFail($id);
        }
    }

    public function getAll(array $relations = [], array $fields = ['*'])
    {
        $exame = new Especialidade();

        if (count($relations) > 0) {
            $exame->with($relations);
        }

        return $exame->all($fields);
    }

    public function fillDatatables(array $fields = [])
    {
        return Especialidade::select($fields);
    }

    public function create(array $request = [])
    {
        if (count($request) > 0) {
            return Especialidade::create($request);
        } else {
            return false;
        }
    }

    public function destroy($id, $force = false)
    {
        if ($force) {
            return Especialidade::withTrashed()->findOrFail($id)->forceDelete();
        } else {
            return Especialidade::destroy($id);
        }
    }

    public function recovery($id)
    {
        return Especialidade::onlyTrashed()->findOrFail($id)->restore();
    }

    public function update($request, $id)
    {
        return Especialidade::findOrFail($id)->update($request);
    }
}
