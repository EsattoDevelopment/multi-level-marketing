<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Repositories;

use App;
use App\Saude\Domains\Guia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GuiaRepository
{
    public function getGuia($id, $clinica = false, array $fields = ['*'])
    {
        $guia = $this->getEntity();

        if ($clinica) {
            $guia->where('clinica_id', $clinica);
        }

        return $guia->find($id, $fields);
    }

    public function getAll(array $relations = [], array $fields = ['*'])
    {
        if (count($relations) > 0) {
            return $this->getEntity()->with($relations)->get($fields);
        } else {
            return $this->getEntity()->get($fields);
        }
    }

    public function fillDatatables($clinica, $disabled = false, $autorizadas = false, $aguardando = false)
    {
        $guia = DB::table('guias as g')
            ->leftJoin('medicos as m', 'm.id', '=', 'g.medico_id')
            ->join('users as u', 'u.id', '=', 'g.user_id')
            ->join('users as cp', 'cp.id', '=', 'g.confirmado_por')
            ->join('users as c', 'c.id', '=', 'g.clinica_id')
            ->join('itens as p', 'p.id', '=', 'g.plano_id')
            ->leftJoin('dependentes as d', 'd.id', '=', 'g.dependente_id')
            ->leftJoin('guias as gg', 'g.id', '=', 'gg.guia_referencia')
            ->select([
                'g.id',
                'g.clinica_id',
                'g.autorizado',
                'u.name as titular',
                'm.name as medico',
                'g.dt_autorizado',
                DB::raw("DATE_FORMAT(g.dt_atendimento, '%d/%m/%Y') as 'data'"),
                //DB::raw("u.name as titular"),
                DB::raw('IFNULL(d.name, u.name) as paciente'),
                DB::raw('IFNULL(gg.id, 0) as referencia'),
                //DB::raw("(select deleted_at from guias where id = gg.id) as retorno_cancelado"),
                'g.tipo_atendimento',
            ]);

        if (Auth::user()->can(['master', 'admin', 'guia-visualizar-todas'])) {
            $guia->addSelect(['c.name as clinica']);
        }

        if ($disabled) {
            $guia->whereNotNull('g.deleted_at');
            $guia->addSelect('g.deleted_at');
        } else {
            $guia->where('g.deleted_at', null);
        }

        if ($clinica) {
            $guia->where('g.clinica_id', $clinica);
        }

        if ($autorizadas) {
            $guia->where('g.autorizado', '1');
        }

        if ($aguardando) {
            $guia->where('g.autorizado', '0');
        }

        return $guia;
    }

    public function save($request, $id = false)
    {
        if (! $id) {
            if (count($request) > 0) {
                $guia = $this->getEntity()->create($request);
            } else {
                return false;
            }

            if (array_key_exists('exames', $request)) {
                $guia->exames()->attach($request['exames']);
            }

            if (array_key_exists('procedimentos', $request)) {
                $guia->procedimentos()->attach($request['procedimentos']);
            }
        } else {
            $guia = $this->getEntity()->findOrFail($id);

            $guia->update($request);

            if (array_key_exists('exames', $request)) {
                $guia->exames()->sync($request['exames']);
            } else {
                $guia->exames()->detach();
            }

            if (array_key_exists('procedimentos', $request)) {
//                $proc = [];

//                foreach ($request['procedimentos'] as $key => $value) {
//                    $proc[$value] = ["valor" => 12.1];
//                }

                $guia->procedimentos()->sync($request['procedimentos']);
            } else {
                $guia->procedimentos()->detach();
            }
        }

        return $guia;
    }

    public function destroy($id, $force = false)
    {
        if ($force) {
            return $this->getEntity()->onlyTrashed()->findOrFail($id)->forceDelete();
        } else {
            return $this->getEntity()->destroy($id);
        }
    }

    public function recovery($id)
    {
        $guia = $this->getEntity()->onlyTrashed()->findOrFail($id);

        return $guia->restore();
    }

    public function getEntity()
    {
        return App::make('App\Saude\Domains\Guia');
    }
}
