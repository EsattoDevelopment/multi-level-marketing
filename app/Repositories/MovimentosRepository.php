<?php


    namespace App\Repositories;


    use App\Models\Movimentos;
    use App\Models\User;
    use Carbon\Carbon;

    class MovimentosRepository
    {
        private $user;

        /**
         * MovimentosRepository constructor.
         * @param User $user
         */
        public function __construct(User $user)
        {
            $this->user = $user;

            return self::class;
        }

        /**
         * @param $id
         * @return $this
         */
        public function userById($id):self
        {
            return self(User::findOrFail($id));
        }

        public function movimentoMensal():float
        {
            $inicio = Carbon::now()->firstOfMonth()->format('Y-m-d').' 00:00:00';
            $fim = Carbon::now()->lastOfMonth()->format('Y-m-d').' 23:59:59';

            $total = (float) Movimentos::whereBetween('created_at', [$inicio, $fim])
                ->whereIn('operacao_id', [1, 3, 6, 17, 20, 23, 27])
                ->whereUserId($this->user->id)
                ->sum('valor_manipulado');

            return $total;
        }
    }