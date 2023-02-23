<?php

/*
 * Esse arquivo faz parte de <MasterMundi/Master MDR>
 * (c) Nome Autor zehluiz17[at]gmail.com
 *
 */

namespace App\Modules\TratarTexto;

class TratarTexto
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function abreviar($list, $max_length = 25)
    {
        /* MAIN: aqui é feita a abreviação dos itens */
        if (! is_array($list)) {
            $list = [$list];
        }

        foreach ($list as &$str) {
            if (strlen($str) >= $max_length) {
                $this->abrev($str);
            }

            return $str;
        }
    }

    /** &$str
     *
     * Significa que o parametro será uma referência, não um cópia. As alterações
     * que eu fizer em $str dentro de abrev() são aplicadas à $str "verdadeira".
     */
    private function abrev(&$str)
    {
        /* referencias globais */
        global $max_length;

        /** verifica entradas tabuladas,
         *
         * a contagem com preg_match é usada como offset no proximo
         * loop.
         */
        $matches = 0;

        /* aqui separa-se a string em palavras */
        $s = explode(' ', $str);

        /**        Rua José Jesualdo Garcia Pessoa
         * pos  : 0   1    2        3      4.
         *
         * matches = 1
         * count() = 5
         *
         * inicio
         * i = 5 - 2 = 3 (começa no Garcia, penultimo nome)
         *
         * fim
         * i >= $matches + 1 = 1 + 1 = 2
         * (segunda palavra depois da ultima abreviação tabelada)
         */
        for ($i = count($s) - 2; $i >= $matches + 1; $i--) {
            /* \.$ testa se o ultimo caractere da string é um ponto.
             * Se for, significa que já está abreviado, e se já é uma
             * palavra abreviada, saímos do loop.
             */
            if (preg_match("/\.$/", $s[$i])) {
                break;
            }

            /* unset é importante pra 'n' não ficar com lixo */
            unset($n);

            if (strlen($s[$i]) > 2) {
                /* a primeira letra da palavra atual */
                $n[] = strtoupper($s[$i][0]);
                $n[] = '.';

                /* substituimos s[i] pela versão abreviada */
                $s[$i] = implode('', $n);
            } else {
                unset($s[$i]);
            }

            /* testa o tamanho total da string */
            if ($this->full_length($s) <= $max_length) {
                break;
            }
        }

        /* junta tudo pra "retornar"... o parâmetro é por referência,
         * não por cópia
         */
        $str = implode(' ', $s);
    }

    /** Nada elegante... *Nada* eficiente... mas quebra o galho.
     *
     * Provavelmente tem como fazer a troca de uma palavra por sua primeira letra
     * seguida por um ponto usando expressões regulares.
     */
    private function full_length(array $s)
    {
        $s = implode(' ', $s);

        return strlen($s);
    }
}
