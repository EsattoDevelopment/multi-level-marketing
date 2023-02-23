<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'username' => $faker->userName,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Saude\Domains\Exame::class, function (Faker\Generator $faker) {
    return [
        'codigo' => $faker->word,
        'nome' => $faker->name,
        'descricao' => $faker->text(150),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ];
});

$factory->define(App\Saude\Domains\Medico::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'crm' => $faker->word,
        'telefone1' => $faker->phoneNumber,
        'telefone2' => $faker->phoneNumber,
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ];
});

$factory->define(App\Saude\Domains\Procedimento::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'codigo' => $faker->ean8,
        'valor' => $faker->randomFloat(2),
        'created_at' => \Carbon\Carbon::now(),
        'updated_at' => \Carbon\Carbon::now(),
    ];
});
