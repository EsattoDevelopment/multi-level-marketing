<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RedeBinariaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rede_binaria')->insert([
            'user_id' => User::whereHas('roles', function ($query) {
                $query->where('name', 'user-empresa');
            })->first()->id,
            'esquerda' => null,
            'direita' => null,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
