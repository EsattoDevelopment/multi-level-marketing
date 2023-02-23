<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Repositories;

use App\Saude\Domains\Medico;
use Illuminate\Support\Facades\Auth;

class MedicoRepository
{
    public function getMedico($id, $relations = false)
    {
        if ($relations) {
            return Medico::with($relations)->find($id);
        } else {
            return Medico::find($id);
        }
    }

    public function getAll($relations = false)
    {
        if ($relations) {
            return Medico::with($relations)->get();
        } else {
            return Medico::all();
        }
    }

    public function fromClinica()
    {
        return Auth::user()->medicos;
    }

    public function getBy($by, $value, array $collumns = ['*'])
    {
        return Medico::where($by, $value)->get($collumns);
    }

    public function fillDatatables(array $fields = [], $disabled = false)
    {
        $medico = Medico::select($fields);

        if ($disabled) {
            $medico->onlyTrashed();
        }

        return $medico;
    }

    public function save($request, $id = false)
    {
        if (! $id) {
            if (count($request) > 0) {
                $medico = Medico::create($request->except('user_id'));
            } else {
                return false;
            }

            if (count($request->get('user_id')) > 0) {
                $medico->clinicas()->attach($request->get('user_id'));
            }

            if (count($request->get('especialidade')) > 0) {
                $medico->especialidades()->attach($request->get('especialidade'));
            }
        } else {
            $medico = Medico::findOrFail($id);

            $medico->update($request->except('user_id', 'especialidade'));

            if (count($request->get('user_id')) > 0) {
                $medico->clinicas()->sync($request->get('user_id'));
            } else {
                $medico->clinicas()->detach();
            }

            if (count($request->get('especialidade')) > 0) {
                $medico->especialidades()->sync($request->get('especialidade'));
            } else {
                $medico->especialidades()->detach();
            }
        }

        return $medico;
    }

    public function destroy($id, $force = false)
    {
        if ($force) {
            return Medico::onlyTrashed()->findOrFail($id)->forceDelete();
        } else {
            return Medico::destroy($id);
        }
    }

    public function recovery($id)
    {
        $medico = Medico::onlyTrashed()->findOrFail($id);

        return $medico->restore();
    }
}
