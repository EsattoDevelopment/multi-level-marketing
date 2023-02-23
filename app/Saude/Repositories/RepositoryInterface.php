<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Repositories;

interface RepositoryInterface
{
    public function all(array $collumns = ['*'], array $relations = []);

    public function find($id, array $collumns = ['*'], array $relations = []);

    public function findBy($id, array $collumns = ['*'], array $relations = []);

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function destroy($id);

    public function recovery($id);

    /*    public function fillDatatables(array $fields = [], $disabled = false)
        {
            $medico = Medico::select($fields);

            if($disabled){
                $medico->onlyTrashed();
            }

            return $medico;
        }

        public function save($request, $id = false)
        {
            if(!$id) {
                if (count($request) > 0) {
                    $medico = Medico::create($request->except('user_id'));
                } else {
                    return false;
                }

                if (count($request->get('user_id')) > 0)
                    $medico->clinicas()->attach($request->get('user_id'));
            }else{
                $medico =  Medico::findOrFail($id);

                $medico->update($request->except('user_id'));

                if (count($request->get('user_id')) > 0) {
                    $medico->clinicas()->sync($request->get('user_id'));
                }else {
                    $medico->clinicas()->detach();
                }

            }

            return $medico;
        }

        public function destroy($id, $force = false)
        {
            if($force){
                return Medico::onlyTrashed()->findOrFail($id)->forceDelete();
            }else{
                return Medico::destroy($id);
            }
        }

        public function recovery($id)
        {
            $medico =  Medico::onlyTrashed()->findOrFail($id);
            return $medico->restore();
        }*/
}
