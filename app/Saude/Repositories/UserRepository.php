<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Saude\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function getUserAuth()
    {
        return Auth::User();
    }

    public function getEndereco(User $user)
    {
        $user->load('endereco');

        return $user->getRelation('endereco');
    }

    public function getUsers($id = null, $tipo = null, array $fields = ['*'], $relations = false)
    {
        $user = User::whereNotIn('id', [1, 2]);

        $user->select($fields);

        if ($relations) {
            $user->with($relations);
        }

        if ($id) {
            $user->whereId($id);
        }

        if ($tipo) {
            $user->whereTipo($tipo);
        }

        return $user->get();
    }
}
