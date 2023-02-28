<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_user')->insert([
            [
                'role_id' => Role::whereName('master')->first()->id,
                'user_id' => User::where('username', 'master')->first()->id,
            ],
            [
                'role_id' => Role::whereName('user-empresa')->first()->id,
                'user_id' => User::where('username', 'empresa')->first()->id,
            ],
        ]);
    }
}
