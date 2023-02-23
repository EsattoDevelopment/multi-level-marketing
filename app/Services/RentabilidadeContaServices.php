<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Services;

use App\Models\User;
use App\Models\Sistema;
use App\Models\Movimentos;
use App\Models\Rentabilidade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RentabilidadeContaServices
{
    private $rentabilidade;
    private $usuarios;
    private $sistema;

    public function __construct()
    {
        $this->sistema = Sistema::findOrFail(1);
    }

    public function rentabilidade(Rentabilidade $rentabilidade)
    {
        $this->rentabilidade = $rentabilidade;

        return $this;
    }

    public function pagar()
    {
        $this->usuarios = DB::table('movimentos as m')
            ->join('users as u', 'u.id', '=', 'm.user_id')
            ->where('m.saldo', '>', 0)
            ->where('u.status', 1)
            ->where('u.id', '>', 2)
            ->whereRaw('m.id = (select max(id) from movimentos where user_id = u.id)')
            ->select([
                'm.id as movimento_id',
                'm.saldo',
                'm.created_at',
                'm.saldo_anterior',
                'm.valor_manipulado',
                'u.id as user_id',
                'u.titulo_id',
                'u.conta',
                'u.name',
            ])
            ->get();

        self::verificaUsuario();
    }

    private function verificaUsuario()
    {
        foreach ($this->usuarios as $usuario) {
            self::capitalizar($usuario);
        }
    }

    private function capitalizar($usuario)
    {
        $valorRentabilidade = round($this->rentabilidade->percentual * $usuario->saldo, 2);

        if ($valorRentabilidade > 0) {
            \Log::info("Usuário #{$usuario->user_id} - {$usuario->name}");
            \Log::info("Entrou para pagar rentabilidade de {$this->sistema->moeda}{$valorRentabilidade}");
            $ultimoMovimento = Movimentos::findOrFail($usuario->movimento_id);

            $dadosMovimento = [
                    'valor_manipulado' => $valorRentabilidade,
                    'saldo_anterior' => ! $ultimoMovimento ? 0 : $ultimoMovimento->saldo,
                    'saldo' => ! $ultimoMovimento ? $valorRentabilidade : $valorRentabilidade + $ultimoMovimento->saldo,
                    'descricao' => 'Correção de Capital da carteira',
                    'responsavel_user_id' => Auth::user()->id,
                    'user_id' => $usuario->user_id,
                    'titulo_id' => $usuario->titulo_id,
                    'operacao_id' => 7,
                    'rentabilidade_id' => $this->rentabilidade->id,
                ];

            \log::info("rentabilidade da carteira  - 0001/{$usuario->conta}");

            Movimentos::create($dadosMovimento);
        }
    }
}
