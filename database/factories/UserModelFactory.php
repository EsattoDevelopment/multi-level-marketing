<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    //$fakerc = Faker\Factory::create();
    //$fakerc->addProvider(new FakerBR($fakerc));
    return [
        'name' => $faker->name,
        'username' => $faker->firstName,
        'email' => $faker->email,
        'password' => bcrypt('teste123*'),
        'cpf' => (new \App\Models\Cpf())->create(),
        'data_nasc' => $faker->date('d-m-Y'),
        'termo' => 1,
        'status' => 0,
        //'indicador' => 3,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
        'titulo_id' => 1,
        'qualificado' => 0,
        'equipe_preferencial' => 1,
        'equipe_predefinida' => 0,
        'rg' => str_random(9),
        'celular' => $faker->phoneNumber,
        'tipo' => 1,
        'estado_civil' => 0,
    ];
});
